<?php defined('MW_PATH') || exit('No direct script access allowed');

/**
 * DeliveryServerInboxroadWebApi
 *
 * @package MailWizz EMA
 * @author Serban George Cristian <cristian.serban@mailwizz.com>
 * @link https://www.mailwizz.com/
 * @copyright MailWizz EMA (https://www.mailwizz.com)
 * @license https://www.mailwizz.com/license/
 * @since 1.9.17
 *
 */

class DeliveryServerInboxroadWebApi extends DeliveryServer
{
	/**
	 * @var string
	 */
	protected $serverType = 'inboxroad-web-api';

	/**
	 * @var string
	 */
	protected $_initStatus;

	/**
	 * @var string
	 */
	protected $_preCheckError;

	/**
	 * @var string
	 */
	protected $_providerUrl = 'https://inboxroad.com/';

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		$rules = array(
			array('password', 'required'),
			array('password', 'length', 'max' => 255),
		);
		return CMap::mergeArray($rules, parent::rules());
	}

	/**
	 * @return array
	 */
	public function attributeLabels()
	{
		$texts = array(
			'password'  => Yii::t('servers', 'Api key'),
		);

		return CMap::mergeArray(parent::attributeLabels(), $texts);
	}

	/**
	 * @return array
	 */
	public function attributeHelpTexts()
	{
		$texts = array(
			'password'  => Yii::t('servers', 'One of your inboxroad api keys.'),
		);

		return CMap::mergeArray(parent::attributeHelpTexts(), $texts);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DeliveryServer the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @param array $params
	 * @return array|bool
	 */
	public function sendEmail(array $params = array())
	{
		$params = (array)Yii::app()->hooks->applyFilters('delivery_server_before_send_email', $this->getParamsArray($params), $this);

		if (!ArrayHelper::hasKeys($params, array('from', 'to', 'subject', 'body'))) {
			return false;
		}

		list($toEmail, $toName) = $this->getMailer()->findEmailAndName($params['to']);
		list($fromEmail)        = $this->getMailer()->findEmailAndName($params['from']);

		$replyToEmail = null;
		if (!empty($params['replyTo'])) {
			list($replyToEmail) = $this->getMailer()->findEmailAndName($params['replyTo']);
		}

		$headers = array();
		if (!empty($params['headers'])) {
			$headers = $this->parseHeadersIntoKeyValue($params['headers']);
		}
		
		$sent = false;

		try {

			if (!$this->preCheckWebHook()) {
				throw new Exception($this->_preCheckError);
			}
			
			$onlyPlainText = !empty($params['onlyPlainText']) && $params['onlyPlainText'] === true;
			
			$data = array(
				'orig'       => $fromEmail,
				'reply_to'   => array(!empty($replyToEmail) ? $replyToEmail : $fromEmail),
				'recipients' => array($toEmail),
				'to_name'    => !empty($toName) ? $toName : $toEmail,
				'subject'    => $params['subject'],
				'message'    => !empty($params['plainText']) ? $params['plainText'] : CampaignHelper::htmlToText($params['body']),
				'html_body'  => $onlyPlainText ? '' : $params['body'],
				'headers'    => !empty($headers) ? $headers : new StdClass(),
				'files'      => array(),
			);
			
			if (!$onlyPlainText && !empty($params['attachments']) && is_array($params['attachments'])) {
				$attachments = array_filter(array_unique($params['attachments']));
				foreach ($attachments as $attachment) {
					if (is_file($attachment)) {
						$data['files'][] = array(
							'mime_type' => 'application/octet-stream',
							'file_data' => base64_encode(file_get_contents($attachment)),
							'filename'  => basename($attachment),
						);
					}
				}
			}
			
			$result = AppInitHelper::makeRemoteRequest('https://webapi.inboxroad.com/api/v1/messages/', array(
				CURLOPT_POST           => true,
				CURLOPT_POSTFIELDS     => json_encode($data),
				CURLOPT_HTTPHEADER     => array(
					'Content-Type: application/json',
					sprintf('Authorization: Basic %s', (string)$this->password)
				),
			));
			
			if ($result['status'] !== 'success') {
				throw new Exception($result['message']);
			}

			$result['message'] = !empty($result['message']) ? $result['message'] : '{}';
			$message = CJSON::decode($result['message']);
			if (empty($message) || empty($message['message_id'])) {
				throw new Exception($result['message']);
			}

			$sent = array(
				'message_id' => str_replace(array('<', '>'), '', $message['message_id'])
			);

		} catch (Exception $e) {
			$this->getMailer()->addLog($e->getMessage());
		}

		if ($sent) {
			$this->logUsage();
		}

		Yii::app()->hooks->doAction('delivery_server_after_send_email', $params, $this, $sent);

		return $sent;
	}

	/**
	 * @param array $params
	 * @return array
	 */
	public function getParamsArray(array $params = array())
	{
		$params['transport'] = self::TRANSPORT_INBOXROAD_WEB_API;
		return parent::getParamsArray($params);
	}
	
	/**
	 * @inheritdoc
	 */
	protected function afterConstruct()
	{
		parent::afterConstruct();
		$this->_initStatus = $this->status;
		$this->hostname    = 'web-api.inboxroad.com';
	}

	/**
	 * @inheritdoc
	 */
	protected function afterFind()
	{
		$this->_initStatus = $this->status;
		parent::afterFind();
	}

	/**
	 * @return bool
	 */
	protected function preCheckWebHook()
	{
		if (MW_IS_CLI || $this->isNewRecord || $this->_initStatus !== self::STATUS_INACTIVE) {
			return true;
		}
		
		if ($this->_preCheckError) {
			return false;
		}

		return true;
	}
	
	/**
	 * @param array $params
	 * @return array
	 */
	public function getFormFieldsDefinition(array $params = array())
	{
		return parent::getFormFieldsDefinition(CMap::mergeArray(array(
			'username'                => null,
			'hostname'                => null,
			'port'                    => null,
			'protocol'                => null,
			'timeout'                 => null,
			'signing_enabled'         => null,
			'max_connection_messages' => null,
			'bounce_server_id'        => null,
			'force_sender'            => null,
		), $params));
	}
}
