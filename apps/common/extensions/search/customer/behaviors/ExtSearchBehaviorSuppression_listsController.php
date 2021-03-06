<?php defined('MW_PATH') || exit('No direct script access allowed');

/**
 * @package MailWizz EMA
 * @author Serban George Cristian <cristian.serban@mailwizz.com>
 * @link https://www.mailwizz.com/
 * @copyright MailWizz EMA (https://www.mailwizz.com)
 * @license https://www.mailwizz.com/license/
 */

class ExtSearchBehaviorSuppression_listsController extends CBehavior
{
	/**
	 * @return array
	 */
	public function searchableActions()
	{
		return array(
			'index' => array(
				'keywords'          => array('suppress list', 'lists suppression'),
                'skip'              => array($this, '_skip'),
                'childrenGenerator' => array($this, '_indexChildrenGenerator')
            ),
            'create' => array(
                'keywords' => array('create suppression list'),
                'skip'     => array($this, '_skip')
            ),
		);
	}

    /**
     * @return bool
     */
    public function _skip()
    {
        return Yii::app()->customer->getModel()->getGroupOption('lists.can_use_own_blacklist', 'no') != 'yes';
    }

    /**
     * @param $term
     * @param SearchExtSearchItem|null $parent
     *
     * @return array
     */
    public function _indexChildrenGenerator($term, SearchExtSearchItem $parent = null)
    {
        $criteria = new CDbCriteria();
	    $criteria->addCondition('customer_id = :cid');
        $criteria->addCondition('name LIKE :term');
        $criteria->params[':cid']  = (int)Yii::app()->customer->getId();
	    $criteria->params[':term'] = '%'. $term .'%';
        $criteria->order = 'list_id DESC';
        $criteria->limit = 5;
        
        $models = CustomerSuppressionList::model()->findAll($criteria);
        $items  = array();
        foreach ($models as $model) {
            $item        = new SearchExtSearchItem();
            $item->title = $model->name;
            $item->url   = Yii::app()->createUrl('suppression_lists/update', array('list_uid' => $model->list_uid));
            $item->score++;
            $items[] = $item->fields;
        }
        return $items;
    }
}
	