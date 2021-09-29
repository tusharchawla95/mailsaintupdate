<?php defined('MW_PATH') || exit('No direct script access allowed');

/**
 * This file is part of the MailWizz EMA application.
 * 
 * @package MailWizz EMA
 * @author Serban George Cristian <cristian.serban@mailwizz.com> 
 * @link https://www.mailwizz.com/
 * @copyright MailWizz EMA (https://www.mailwizz.com)
 * @license https://www.mailwizz.com/license/
 * @since 1.3.4
 */
 
?>
<div class="callout callout-info">
    <?php echo Yii::t('settings', 'Please note that most of the customer settings will also be found in customer groups allowing you a fine grained control over your customers and their limits/permissions.');?>
</div>
<?php 

// since 1.9.17
$tabs = Yii::app()->hooks->applyFilters('backend_controller_settings_customer_options_tabs', array(
    array(
        'id'     => 'customer_common',
        'active' => $this->getAction()->getId() == 'customer_common',
        'url'    => $this->createUrl('settings/customer_common'),
        'label'  => Yii::t('settings', 'Common'),
    ),
	array(
		'id'     => 'customer_servers',    
		'active' => $this->getAction()->getId() == 'customer_servers',
		'url'    => $this->createUrl('settings/customer_servers'),
		'label'  => Yii::t('settings', 'Servers'),
	),
	array(
		'id'     => 'customer_domains',
		'active' => $this->getAction()->getId() == 'customer_domains',
		'url'    => $this->createUrl('settings/customer_domains'),
		'label'  => Yii::t('settings', 'Domains'),
	),
	array(
		'id'     => 'customer_lists',
		'active' => $this->getAction()->getId() == 'customer_lists',
		'url'    => $this->createUrl('settings/customer_lists'),
		'label'  => Yii::t('settings', 'Lists'),
	),
	array(
		'id'     => 'customer_campaigns',
		'active' => $this->getAction()->getId() == 'customer_campaigns',
		'url'    => $this->createUrl('settings/customer_campaigns'),
		'label'  => Yii::t('settings', 'Campaigns'),
	),
	array(
		'id'     => 'customer_surveys',
		'active' => $this->getAction()->getId() == 'customer_surveys',
		'url'    => $this->createUrl('settings/customer_surveys'),
		'label'  => Yii::t('settings', 'Surveys'),
	),
	array(
		'id'     => 'customer_quota_counters',
		'active' => $this->getAction()->getId() == 'customer_quota_counters',
		'url'    => $this->createUrl('settings/customer_quota_counters'),
		'label'  => Yii::t('settings', 'Quota counters'),
	),
	array(
		'id'     => 'customer_sending',
		'active' => $this->getAction()->getId() == 'customer_sending',
		'url'    => $this->createUrl('settings/customer_sending'),
		'label'  => Yii::t('settings', 'Sending'),
	),
	array(
		'id'     => 'customer_cdn',
		'active' => $this->getAction()->getId() == 'customer_cdn',
		'url'    => $this->createUrl('settings/customer_cdn'),
		'label'  => Yii::t('settings', 'CDN'),
	),
	array(
		'id'     => 'customer_registration',
		'active' => $this->getAction()->getId() == 'customer_registration',
		'url'    => $this->createUrl('settings/customer_registration'),
		'label'  => Yii::t('settings', 'Registration'),
	),
	array(
		'id'     => 'customer_api',
		'active' => $this->getAction()->getId() == 'customer_api',
		'url'    => $this->createUrl('settings/customer_api'),
		'label'  => Yii::t('settings', 'API'),
	),
));
?>
<ul class="nav nav-tabs" style="border-bottom: 0px;">
    <?php foreach ($tabs as $tab) { 
        if (!isset($tab['id'], $tab['active'], $tab['url'], $tab['label'])) {
            continue;
        }
        ?>
        <li class="<?php echo $tab['active'] ? 'active' : 'inactive';?>">
            <a href="<?php echo $tab['url']; ?>">
                <?php echo CHtml::encode($tab['label']); ?>
            </a>
        </li>
    <?php } ?>
</ul>