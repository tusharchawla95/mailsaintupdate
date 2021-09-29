<?php defined('MW_PATH') || exit('No direct script access allowed');

/**
 * Campaigns_abuse_reportsController
 *
 * @package MailWizz EMA
 * @author Serban George Cristian <cristian.serban@mailwizz.com>
 * @link https://www.mailwizz.com/
 * @copyright MailWizz EMA (https://www.mailwizz.com)
 * @license https://www.mailwizz.com/license/
 * @since 1.9.23
 */

class Campaigns_abuse_reportsController extends Controller
{
	/**
	 * List all abuse reports for all campaigns
	 */
	public function actionIndex()
	{
		$request = Yii::app()->request;
		$model = new CampaignAbuseReport('search');
		$model->unsetAttributes();
		$model->attributes = (array)$request->getQuery($model->modelName, array());
		$model->customer_id = (int)Yii::app()->customer->getId();

		$this->setData(array(
			'pageMetaTitle'     => $this->data->pageMetaTitle . ' | '. Yii::t('campaigns', 'Abuse reports'),
			'pageHeading'       => Yii::t('campaigns', 'Abuse reports'),
			'pageBreadcrumbs'   => array(
				Yii::t('campaigns', 'Campaigns') => $this->createUrl('campaigns/index'),
				Yii::t('campaigns', 'Abuse reports') => $this->createUrl('campaigns_abuse_reports/index'),
				Yii::t('app', 'View all')
			)
		));

		$this->render('list', compact('model'));
	}
}
