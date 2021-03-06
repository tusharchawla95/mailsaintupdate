<?php defined('MW_PATH') || exit('No direct script access allowed');

/**
 * SurveyFieldType
 *
 * @package MailWizz EMA
 * @author Serban George Cristian <cristian.serban@mailwizz.com>
 * @link https://www.mailwizz.com/
 * @copyright MailWizz EMA (https://www.mailwizz.com)
 * @license https://www.mailwizz.com/license/
 * @since 1.7.8
 */

/**
 * This is the model class for table "{{survey_field_type}}".
 *
 * The followings are the available columns in table '{{survey_field_type}}':
 * @property integer $type_id
 * @property string $name
 * @property string $identifier
 * @property string $class_alias
 * @property string $description
 * @property string $date_added
 * @property string $last_updated
 *
 * The followings are the available model relations:
 * @property SurveyField[] $surveyFields
 */
class SurveyFieldType extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{survey_field_type}}';
	}

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        $relations = array(
            'fields' => array(self::HAS_MANY, 'SurveyField', 'type_id'),
        );

        return CMap::mergeArray($relations, parent::relations());
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        $labels = array(
            'type_id'       => Yii::t('survey_fields', 'Type'),
            'name'          => Yii::t('survey_fields', 'Name'),
            'identifier'    => Yii::t('survey_fields', 'Identifier'),
            'class_alias'   => Yii::t('survey_fields', 'Class alias'),
            'description'   => Yii::t('survey_fields', 'Description'),
        );

        return CMap::mergeArray($labels, parent::attributeLabels());
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return SurveyFieldType the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
