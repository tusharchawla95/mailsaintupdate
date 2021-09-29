<?php defined('MW_PATH') || exit('No direct script access allowed');

/**
 * CampaignCompareWidget
 *
 * @package MailWizz EMA
 * @author Serban George Cristian <cristian.serban@mailwizz.com>
 * @link https://www.mailwizz.com/
 * @copyright MailWizz EMA (https://www.mailwizz.com)
 * @license https://www.mailwizz.com/license/
 * @since 1.9.17
 */

class CampaignsCompareWidget extends CWidget
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        Yii::app()->clientScript->registerScriptFile(Yii::app()->apps->getBaseUrl('assets/js/campaigns-compare.js'));
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->render('campaigns-compare');
    }
}