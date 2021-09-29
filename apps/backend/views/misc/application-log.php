<?php defined('MW_PATH') || exit('No direct script access allowed');

/**
 * This file is part of the MailWizz EMA application.
 * 
 * @package MailWizz EMA
 * @author Serban George Cristian <cristian.serban@mailwizz.com> 
 * @link https://www.mailwizz.com/
 * @copyright MailWizz EMA (https://www.mailwizz.com)
 * @license https://www.mailwizz.com/license/
 * @since 1.3.3
 */

/** @var string $category */
/** @var string $applicationLog */
 
?>
<div class="box box-primary borderless">
    <div class="box-header">
        <div class="pull-left">
            <h3 class="box-title">
                <?php echo IconHelper::make('glyphicon-file') .  $pageHeading;?>
            </h3>
        </div>
        <div class="pull-right">
            <?php echo CHtml::form();?>
            <button type="submit" name="delete" value="1" class="btn btn-danger btn-flat delete-app-log" data-message="<?php echo Yii::t('app', 'Are you sure you want to remove the application log?')?>"><?php echo Yii::t('app', 'Delete');?></button>
            <?php echo CHtml::endForm();?>
        </div>
        <div class="clearfix"><!-- --></div>
    </div>
    <div class="box-body">
        <ul class="nav nav-tabs" style="border-bottom: 0px;">
            <li class="<?php echo $category === 'application' ? 'active' : ''; ?>">
                <a href="<?php echo $this->createUrl('misc/application_log'); ?>"><?php echo Yii::t('settings', 'General'); ?></a>
            </li>
            <li class="<?php echo $category === '404' ? 'active' : ''; ?>">
                <a href="<?php echo $this->createUrl('misc/application_log', array('category' => '404')); ?>"><?php echo Yii::t('settings', 'Pages not found'); ?></a>
            </li>
        </ul>
        <textarea class="form-control" rows="30"><?php echo $applicationLog;?></textarea>  
    </div>
</div>