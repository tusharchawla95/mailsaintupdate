<?php defined('MW_PATH') || exit('No direct script access allowed');

/**
 * HeaderWidget
 * 
 * @package MailWizz EMA
 * @author Serban George Cristian <cristian.serban@mailwizz.com> 
 * @link https://www.mailwizz.com/
 * @copyright MailWizz EMA (https://www.mailwizz.com)
 * @license https://www.mailwizz.com/license/
 * @since 1.9.15
 */
 
class HeaderWidget extends CWidget
{
    public function run()
    {
	    $view = 'header';
	    if ($this->getViewFile($view . '-custom') !== false) {
		    $view .= '-custom';
	    }

	    $this->render($view);
    }
}