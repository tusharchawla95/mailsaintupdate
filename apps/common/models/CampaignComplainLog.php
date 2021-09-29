<?php defined('MW_PATH') || exit('No direct script access allowed');

/**
 * CampaignComplainLog
 * 
 * @package MailWizz EMA
 * @author Serban George Cristian <cristian.serban@mailwizz.com> 
 * @link https://www.mailwizz.com/
 * @copyright MailWizz EMA (https://www.mailwizz.com)
 * @license https://www.mailwizz.com/license/
 * @since 1.4.4
 */
 
/**
 * This is the model class for table "campaign_complain_log".
 *
 * The followings are the available columns in table 'campaign_complain_log':
 * @property string $log_id
 * @property integer $campaign_id
 * @property integer $subscriber_id
 * @property string $message
 * @property string $date_added
 *
 * The followings are the available model relations:
 * @property Campaign $campaign
 * @property ListSubscriber $subscriber
 */
class CampaignComplainLog extends ActiveRecord
{
	/**
	 * @var int 
	 */
	public $customer_id = 0;
	
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{campaign_complain_log}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        $rules = array(

	        // The following rule is used by search().
        	array('campaign_id, subscriber_id, message', 'safe', 'on' => 'search'),
        );
        return CMap::mergeArray($rules, parent::rules());
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        $relations = array(
            'campaign'   => array(self::BELONGS_TO, 'Campaign', 'campaign_id'),
            'subscriber' => array(self::BELONGS_TO, 'ListSubscriber', 'subscriber_id'),
        );
        return CMap::mergeArray($relations, parent::relations());
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        $labels = array(
            'log_id'        => Yii::t('campaigns', 'Log'),
            'campaign_id'   => Yii::t('campaigns', 'Campaign'),
            'subscriber_id' => Yii::t('campaigns', 'Subscriber'),
            'message'       => Yii::t('campaigns', 'Message'),
        );

        return CMap::mergeArray($labels, parent::attributeLabels());
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('t.campaign_id', (int)$this->campaign_id);
        
        return new CActiveDataProvider(get_class($this), array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => $this->paginationOptions->getPageSize(),
                'pageVar' => 'page',
            ),
            'sort' => array(
                'defaultOrder' => array(
                    't.log_id' => CSort::SORT_DESC,
                ),
            ),
        ));
    }

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function searchCustomer()
	{
		$criteria = new CDbCriteria;
		$criteria->with['campaign'] = array(
			'joinType'  => 'INNER JOIN',
			'together'  => true,
			'condition' => 'campaign.customer_id = :ccid',
			'params'    => array(
				':ccid' => (int)$this->customer_id
			),
		);
		$criteria->with['subscriber'] = array(
			'joinType' => 'INNER JOIN',
			'together' => true,
			'with'     => array(
				'list' => array(
					'joinType'  => 'INNER JOIN',
					'together'  => true,
					'condition' => 'list.customer_id = :lcid',
					'params'    => array(
						':lcid' => (int)$this->customer_id
					),
				)
			),
		);
		
		if (!empty($this->campaign_id)) {
			if (is_numeric($this->campaign_id)) {
				$criteria->compare('t.campaign_id', (int)$this->campaign_id);
			} elseif (is_string($this->campaign_id)) {
				$criteria->addCondition('(campaign.name LIKE :campaign_id OR campaign.campaign_uid LIKE :campaign_id)');
				$criteria->params[':campaign_id'] = '%'. (string)$this->campaign_id .'%';
			}
		}

		if (!empty($this->subscriber_id)) {
			if (is_numeric($this->subscriber_id)) {
				$criteria->compare('t.subscriber_id', (int)$this->subscriber_id);
			} elseif (is_string($this->subscriber_id)) {
				$criteria->addCondition('(subscriber.email LIKE :subscriber_id OR subscriber.subscriber_uid LIKE :subscriber_id)');
				$criteria->params[':subscriber_id'] = '%'. (string)$this->subscriber_id .'%';
			}
		}
		
		$criteria->compare('message', $this->message, true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria' => $criteria,
			'pagination' => array(
				'pageSize' => $this->paginationOptions->getPageSize(),
				'pageVar' => 'page',
			),
			'sort' => array(
				'defaultOrder' => array(
					't.log_id' => CSort::SORT_DESC,
				),
			),
		));
	}
    
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return CampaignBounceLog the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
