<?php defined('MW_PATH') || exit('No direct script access allowed');

/**
 * UpdateWorkerFor_1_9_13
 *
 * @package MailWizz EMA
 * @author Serban George Cristian <cristian.serban@mailwizz.com>
 * @link https://www.mailwizz.com/
 * @copyright MailWizz EMA (https://www.mailwizz.com)
 * @license https://www.mailwizz.com/license/
 * @since 1.9.13
 */

class UpdateWorkerFor_1_9_13 extends UpdateWorkerAbstract
{
    public function run()
    {
        // run the sql from file
        $this->runQueriesFromSqlFile('1.9.13');
        
        try {
	        CommonEmailTemplate::reinstallCoreTemplateByDefinitionId('list-import-finished');
        } catch (Exception $e) {
        	
        }
    }
}
