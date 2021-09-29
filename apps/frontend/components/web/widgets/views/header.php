<?php defined('MW_PATH') || exit('No direct script access allowed');

/**
 * This file is part of the MailWizz EMA application.
 * 
 * @package MailWizz EMA
 * @author Serban George Cristian <cristian.serban@mailwizz.com> 
 * @link https://www.mailwizz.com/
 * @copyright MailWizz EMA (https://www.mailwizz.com)
 * @license https://www.mailwizz.com/license/
 * @since 1.9.15
 */
 
?>

<header class="navbar navbar-default">
    <div class="col-lg-10 col-lg-push-1 col-md-10 col-md-push-1 col-sm-12 col-xs-12">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only"><?php echo Yii::t('app', 'Toggle navigation');?></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo Yii::app()->homeUrl;?>" title="<?php echo Yii::app()->options->get('system.common.site_name');?>">
                <span><span><?php echo Yii::app()->options->get('system.common.site_name');?></span></span>
            </a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
				<?php if (Yii::app()->options->get('system.customer_registration.enabled', 'no') == 'yes') { ?>
                    <li class="hidden-xs">
                        <a href="<?php echo Yii::app()->apps->getAppUrl('customer', 'guest/register');?>" class="btn btn-default btn-flat" title="<?php echo Yii::t('app', 'Sign up');?>">
							<?php echo Yii::t('app', 'Sign up');?>
                        </a>
                    </li>
                    <li class="hidden-lg hidden-md hidden-sm">
                        <a href="<?php echo Yii::app()->apps->getAppUrl('customer', 'guest/register');?>" class="" title="<?php echo Yii::t('app', 'Sign up');?>">
							<?php echo Yii::t('app', 'Sign up');?>
                        </a>
                    </li>
				<?php } ?>
                <li class="">
                    <a href="<?php echo Yii::app()->apps->getAppUrl('customer', 'guest/index');?>" title="<?php echo Yii::t('app', 'Login');?>">
						<?php echo Yii::t('app', 'Login');?>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</header>
