<?php defined('MW_PATH') || exit('No direct script access allowed');

/**
 * This file is part of the MailWizz EMA application.
 *
 * @package MailWizz EMA
 * @author Serban George Cristian <cristian.serban@mailwizz.com>
 * @link https://www.mailwizz.com/
 * @copyright MailWizz EMA (https://www.mailwizz.com)
 * @license https://www.mailwizz.com/license/
 * @since 1.0
 */

?>
<!DOCTYPE html>
<html dir="<?php echo $this->htmlOrientation;?>">
<head>
    <meta charset="<?php echo Yii::app()->charset;?>">
    <title><?php echo CHtml::encode($pageMetaTitle);?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo CHtml::encode($pageMetaDescription);?>">
    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="//oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>
<body class="<?php echo $this->bodyClasses;?>">
    <?php $this->afterOpeningBodyTag;?>
    <div class="wrapper">
        <?php $this->widget('frontend.components.web.widgets.HeaderWidget');?>
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row row-large">
                    <div class="container-fluid-large col-lg-10 col-lg-push-1 col-md-10 col-md-push-1 col-sm-12 col-xs-12">
                        <div id="notify-container">
                            <?php echo Yii::app()->notify->show();?>
                        </div>
                        <?php echo $content;?>
                    </div>
                </div>
            </div>
        </div>
	    <?php $this->widget('frontend.components.web.widgets.FooterWidget');?>
    </div>
</body>
</html>