<?php defined('MW_PATH') || exit('No direct script access allowed');

/**
 * This file is part of the MailWizz EMA application.
 *
 * @package MailWizz EMA
 * @author Serban George Cristian <cristian.serban@mailwizz.com>
 * @link https://www.mailwizz.com/
 * @copyright MailWizz EMA (https://www.mailwizz.com)
 * @license https://www.mailwizz.com/license/
 * @since 1.9.17
 */

/** @var Campaign[] $campaigns */

?>
<div class="sticky-cols-wrapper">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table class="table table-striped table-condensed">
                <thead>
                    <tr>
                        <th class="sticky-col campaign-name"><?php echo Yii::t('campaign_reports', 'Campaign');?></th>
                        <th><?php echo Yii::t('campaign_reports', 'Recipients');?></th>
    
                        <th><?php echo Yii::t('campaign_reports', 'Opens rate');?></th>
                        <th><?php echo Yii::t('campaign_reports', 'Unique opens');?></th>
                        <th><?php echo Yii::t('campaign_reports', 'Total opens');?></th>
    
                        <th><?php echo Yii::t('campaign_reports', 'Clicks rate');?></th>
                        <th><?php echo Yii::t('campaign_reports', 'Unique clicks');?></th>
                        <th><?php echo Yii::t('campaign_reports', 'Total clicks');?></th>
                        
                        <th><?php echo Yii::t('campaign_reports', 'Bounce rate');?></th>
                        <th><?php echo Yii::t('campaign_reports', 'Hard bounces');?></th>
                        <th><?php echo Yii::t('campaign_reports', 'Soft bounces');?></th>
                        <th><?php echo Yii::t('campaign_reports', 'Internal bounces');?></th>
                        
                        <th><?php echo Yii::t('campaign_reports', 'Unsubscribe rate');?></th>
                        <th><?php echo Yii::t('campaign_reports', 'Unsubscribes');?></th>
                        
                        <th><?php echo Yii::t('campaign_reports', 'Complaints rate');?></th>
                        <th><?php echo Yii::t('campaign_reports', 'Complaints');?></th>

                        <th><?php echo Yii::t('campaign_reports', 'Status');?></th>
                        <th><?php echo Yii::t('campaign_reports', 'Total delivery time');?></th>
                    </tr>
                </thead>
                <tbody>
		        <?php foreach ($campaigns as $campaign) { ?>
                    <tr>
                        <td class="sticky-col campaign-name"><?php echo $campaign->name; ?></td>
                        <td><?php echo $campaign->stats->getProcessedCount(true); ?></td>

                        <td><?php echo $campaign->stats->getUniqueOpensRate(true);?>%</td>
                        <td><?php echo $campaign->stats->getUniqueOpensCount(true);?> / <?php echo $campaign->stats->getUniqueOpensRate(true);?>%</td>
                        <td><?php echo $campaign->stats->getOpensCount(true);?> / <?php echo $campaign->stats->getOpensRate(true);?>%</td>

                        <td><?php echo $campaign->stats->getUniqueClicksRate(true);?>%</td>
                        <td><?php echo $campaign->stats->getUniqueClicksCount(true);?> / <?php echo $campaign->stats->getUniqueClicksRate(true);?>%</td>
                        <td><?php echo $campaign->stats->getClicksCount(true);?> / <?php echo $campaign->stats->getClicksRate(true);?>%</td>

                        <td><?php echo $campaign->stats->getBouncesRate(true);?>%</td>
                        <td><?php echo $campaign->stats->getHardBouncesCount(true);?> / <?php echo $campaign->stats->getHardBouncesRate(true);?>%</td>
                        <td><?php echo $campaign->stats->getSoftBouncesCount(true);?> / <?php echo $campaign->stats->getSoftBouncesRate(true);?>%</td>
                        <td><?php echo $campaign->stats->getInternalBouncesCount(true);?> / <?php echo $campaign->stats->getInternalBouncesRate(true);?>%</td>
                        
                        <td><?php echo $campaign->stats->getUnsubscribesRate(true);?>%</td>
                        <td><?php echo $campaign->stats->getUnsubscribesCount(true);?></td>

                        <td><?php echo $campaign->stats->getComplaintsRate(true);?>%</td>
                        <td><?php echo $campaign->stats->getComplaintsCount(true);?></td>

                        <td><?php echo $campaign->getStatusName();?></td>
                        <td><?php echo $campaign->totalDeliveryTime; ?></td>
                    </tr>
		        <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>