<?php defined('MW_PATH') || exit('No direct script access allowed');

/**
 * Campaigns_abuse_complaintsController
 *
 * Handles the actions for campaigns related tasks
 *
 * @package MailWizz EMA
 * @author Serban George Cristian <cristian.serban@mailwizz.com>
 * @link https://www.mailwizz.com/
 * @copyright MailWizz EMA (https://www.mailwizz.com)
 * @license https://www.mailwizz.com/license/
 * @since 1.9.19
 */

class Campaigns_abuse_complaintsController extends Controller
{
	/**
	 * List all abuse complaints for all campaigns
	 */
	public function actionIndex()
	{
		$request = Yii::app()->request;
		$model = new CampaignComplainLog('search');
		$model->attributes = (array)$request->getQuery($model->modelName, array());
		$model->customer_id = (int)Yii::app()->customer->getId();

		$this->setData(array(
			'pageMetaTitle'     => $this->data->pageMetaTitle . ' | '. Yii::t('campaigns', 'Abuse complaints'),
			'pageHeading'       => Yii::t('campaigns', 'Abuse complaints'),
			'pageBreadcrumbs'   => array(
				Yii::t('campaigns', 'Campaigns') => $this->createUrl('campaigns/index'),
				Yii::t('campaigns', 'Abuse complaints') => $this->createUrl('campaigns_abuse_complaints/index'),
				Yii::t('app', 'View all')
			)
		));

		$this->render('list', compact('model'));
	}
}
