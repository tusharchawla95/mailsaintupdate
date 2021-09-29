<?php defined('MW_PATH') || exit('No direct script access allowed');

/**
 * Campaign_unsubscribesController
 *
 * @package MailWizz EMA
 * @author Serban George Cristian <cristian.serban@mailwizz.com>
 * @link https://www.mailwizz.com/
 * @copyright MailWizz EMA (https://www.mailwizz.com)
 * @license https://www.mailwizz.com/license/
 * @since 1.9.15
 */

class Campaign_unsubscribesController extends Controller
{
    // access rules for this controller
    public function accessRules()
    {
        return array(
            // allow all authenticated users on all actions
            array('allow', 'users' => array('@')),
            // deny all rule.
            array('deny'),
        );
    }

    /**
     * Handles the listing of the bounces for a campaign.
     * The listing is based on page number and number of lists per page.
     * This action will produce a valid ETAG for caching purposes.
     */
    public function actionIndex($campaign_uid)
    {
        $campaign = $this->loadCampaignByUid($campaign_uid);
        if (empty($campaign)) {
            return $this->renderJson(array(
                'status'    => 'error',
                'error'     => Yii::t('api', 'The campaign does not exist!')
            ), 404);
        }
        
        $request    = Yii::app()->request;
        $perPage    = (int)$request->getQuery('per_page', 10);
        $page       = (int)$request->getQuery('page', 1);
        $maxPerPage = 50;
        $minPerPage = 10;

        if ($perPage < $minPerPage) {
            $perPage = $minPerPage;
        }

        if ($perPage > $maxPerPage) {
            $perPage = $maxPerPage;
        }

        if ($page < 1) {
            $page = 1;
        }

        $data = array(
            'count'        => null,
            'total_pages'  => null,
            'current_page' => null,
            'next_page'    => null,
            'prev_page'    => null,
            'records'      => array(),
        );

        $criteria = new CDbCriteria();
        $criteria->compare('t.campaign_id', (int)$campaign->campaign_id);

        $count = CampaignTrackUnsubscribe::model()->count($criteria);

        if ($count == 0) {
            return $this->renderJson(array(
                'status' => 'success',
                'data'   => $data
            ), 200);
        }

        $totalPages = ceil($count / $perPage);

        $data['count']        = $count;
        $data['current_page'] = $page;
        $data['next_page']    = $page < $totalPages ? $page + 1 : null;
        $data['prev_page']    = $page > 1 ? $page - 1 : null;
        $data['total_pages']  = $totalPages;

        $criteria->order  = 't.id DESC';
        $criteria->limit  = $perPage;
        $criteria->offset = ($page - 1) * $perPage;

	    $models = CampaignTrackUnsubscribe::model()->findAll($criteria);

        foreach ($models as $model) {
            
            $data['records'][] = array(
	            'ip_address'    => $model->ip_address,
	            'user_agent'    => $model->user_agent,
	            'reason'        => $model->reason,
	            'note'          => $model->note,
	            'date_added'    => $model->date_added,
                'subscriber'  => array(
                    'subscriber_uid' => $model->subscriber->subscriber_uid,
                    'email'          => $model->subscriber->displayEmail,
                ),
            );
        }

        return $this->renderJson(array(
            'status' => 'success',
            'data'   => $data
        ), 200);
    }
    
    /**
     * @param $campaign_uid
     * @return Campaign|null
     */
    public function loadCampaignByUid($campaign_uid)
    {
        if (empty($campaign_uid)) {
            return null;
        }
        $criteria = new CDbCriteria();
        $criteria->compare('customer_id', (int)Yii::app()->user->getId());
        $criteria->compare('campaign_uid', $campaign_uid);
        return Campaign::model()->find($criteria);
    }
}
