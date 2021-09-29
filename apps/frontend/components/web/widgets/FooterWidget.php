<?php defined('MW_PATH') || exit('No direct script access allowed');

/**
 * FooterWidget
 * 
 * @package MailWizz EMA
 * @author Serban George Cristian <cristian.serban@mailwizz.com> 
 * @link https://www.mailwizz.com/
 * @copyright MailWizz EMA (https://www.mailwizz.com)
 * @license https://www.mailwizz.com/license/
 * @since 1.9.15
 */
 
class FooterWidget extends CWidget
{
    public function run()
    {
	    $view = 'footer';
	    if ($this->getViewFile($view . '-custom') !== false) {
		    $view .= '-custom';
	    }

	    $this->render($view);
    }
}