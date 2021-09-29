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

<ul class="nav nav-tabs" style="border-bottom: 0px;">
    <?php foreach ($tabs as $tab) { ?>
        <li class="<?php echo $tab['id'] === 'common' ? 'active' : '';?>">
            <a href="#tab-<?php echo $tab['id']; ?>" data-toggle="tab"><?php echo $tab['label'];?></a>
        </li>
    <?php }?>
</ul>

<div class="tab-content">
    <?php foreach ($tabs as $tab) { ?>
        <div class="tab-pane <?php echo $tab['id'] === 'common' ? 'active' : '';?>" id="tab-<?php echo $tab['id']; ?>">
            <?php $this->renderPartial($tab['view'], array('model' => $tab['model'], 'form' => $form));?>
        </div>
    <?php }?>
</div>
