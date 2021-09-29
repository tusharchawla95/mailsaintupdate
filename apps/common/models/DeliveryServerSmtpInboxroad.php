<?php defined('MW_PATH') || exit('No direct script access allowed');

/**
 * DeliveryServerSmtpInboxroad
 *
 * @package MailWizz EMA
 * @author Serban George Cristian <cristian.serban@mailwizz.com>
 * @link https://www.mailwizz.com/
 * @copyright MailWizz EMA (https://www.mailwizz.com)
 * @license https://www.mailwizz.com/license/
 * @since 1.9.15
 */

class DeliveryServerSmtpInboxroad extends DeliveryServerSmtp
{
    /**
     * @var string 
     */
    protected $serverType = 'smtp-inboxroad';

    /**
     * @var string 
     */
    protected $_providerUrl = 'https://www.inboxroad.com/';

	/**
	 * @var string
	 */
	public $inboxroad_return_path = '';

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		$rules = array(
			array('inboxroad_return_path', 'required'),
			array('inboxroad_return_path', 'email', 'validateIDN' => true),
		);

		return CMap::mergeArray($rules, parent::rules());
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
     * @return array
     */
    public function getParamsArray(array $params = array())
    {
        $params['transport'] = self::TRANSPORT_SMTP;
        if (!empty($this->inboxroad_return_path)) {
	        $params['returnPath'] = (string)$this->inboxroad_return_path;	
        }
        return parent::getParamsArray($params);
    }

	/**
	 * @return array
	 */
	public function attributeLabels()
	{
		$labels = array(
			'inboxroad_return_path'  => Yii::t('servers', 'Return Path'),
		);

		return CMap::mergeArray(parent::attributeLabels(), $labels);
	}

	/**
	 * @inheritdoc
	 */
	protected function afterConstruct()
	{
		parent::afterConstruct();
		$this->inboxroad_return_path = $this->getModelMetaData()->itemAt('inboxroad_return_path');
	}

	/**
	 * @inheritdoc
	 */
	protected function afterFind()
	{
		$this->inboxroad_return_path = $this->getModelMetaData()->itemAt('inboxroad_return_path');
		parent::afterFind();
	}

	/**
	 * @return bool
	 */
	protected function beforeSave()
	{
		$this->getModelMetaData()->add('inboxroad_return_path', $this->inboxroad_return_path);
		return parent::beforeSave();
	}

    /**
     * @inheritdoc
     */
    public function getDswhUrl()
    {
        $url = Yii::app()->options->get('system.urls.frontend_absolute_url') . 'dswh/inboxroad';
        if (MW_IS_CLI) {
            return $url;
        }
        if (Yii::app()->request->isSecureConnection && parse_url($url, PHP_URL_SCHEME) == 'http') {
            $url = substr_replace($url, 'https', 0, 4);
        }
        return $url;
    }

	/**
	 * @param array $params
	 * @return array
	 */
	public function getFormFieldsDefinition(array $params = array())
	{
		$form = new CActiveForm();
		return parent::getFormFieldsDefinition(CMap::mergeArray(array(
			'bounce_server_id'      => null,
			'inboxroad_return_path' => array(
				'visible'   => true,
				'fieldHtml' => $form->textField($this, 'inboxroad_return_path', $this->getHtmlOptions('inboxroad_return_path')),
			),
		), $params));
	}
}
