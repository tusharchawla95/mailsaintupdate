<?php defined('MW_PATH') || exit('No direct script access allowed');

/**
 * @package MailWizz EMA
 * @author Serban George Cristian <cristian.serban@mailwizz.com> 
 * @link https://www.mailwizz.com/
 * @copyright MailWizz EMA (https://www.mailwizz.com)
 * @license https://www.mailwizz.com/license/
 */
?>
<div class="row domains-keys-pair-item">
	<div class="col-lg-5">
		<div class="form-group">
			<?php echo $form->labelEx($domainsKeysPair, '[' . $counter . ']domain');?>
			<?php echo $form->textField($domainsKeysPair, '[' . $counter . ']domain', $domainsKeysPair->getHtmlOptions('domain')); ?>
			<?php echo $form->error($domainsKeysPair, 'domain');?>
		</div>
	</div>
	<div class="col-lg-3">
		<div class="form-group">
			<?php echo $form->labelEx($domainsKeysPair, '[' . $counter . ']site_key');?>
			<?php echo $form->textField($domainsKeysPair, '[' . $counter . ']site_key', $domainsKeysPair->getHtmlOptions('site_key')); ?>
			<?php echo $form->error($domainsKeysPair, 'site_key');?>
		</div>
	</div>
	<div class="col-lg-3">
		<div class="form-group">
			<?php echo $form->labelEx($domainsKeysPair, '[' . $counter . ']secret_key');?>
			<?php echo $form->textField($domainsKeysPair, '[' . $counter . ']secret_key', $domainsKeysPair->getHtmlOptions('secret_key')); ?>
			<?php echo $form->error($domainsKeysPair, 'secret_key');?>
		</div>
	</div>
	<div class="col-lg-1">
		<div class="form-group">
			<label>&nbsp;</label>
			<div class="clearfix"><!-- --></div>
			<a href="javascript:;" class="btn btn-danger btn-flat btn-remove-domains-keys-pair"><?php echo IconHelper::make('delete'); ?></a>
		</div>
	</div>
</div>