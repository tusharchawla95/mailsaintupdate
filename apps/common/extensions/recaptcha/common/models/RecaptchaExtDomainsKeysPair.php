<?php defined('MW_PATH') || exit('No direct script access allowed');

/**
 * RecaptchaExtDomainsKeysPair
 * 
 * @package MailWizz EMA
 * @subpackage Recaptcha
 * @author Serban George Cristian <cristian.serban@mailwizz.com> 
 * @link https://www.mailwizz.com/
 * @copyright MailWizz EMA (https://www.mailwizz.com)
 * @license https://www.mailwizz.com/license/
 */
 
class RecaptchaExtDomainsKeysPair extends FormModel
{
    public $domain = '';

    public $site_key = '';

    public $secret_key = '';

    public function rules()
    {
        $rules = array(
            array('domain, site_key, secret_key', 'required'),
	        array('domain', '_validateDomain'),
        );
        return CMap::mergeArray($rules, parent::rules());    
    }
    
    public function attributeLabels()
    {
        $labels = array(
            'domain'     => Yii::t('ext_recaptcha', 'Domain'),
            'site_key'   => Yii::t('ext_recaptcha', 'Site key'),
            'secret_key' => Yii::t('ext_recaptcha', 'Secret key'),
        );
        return CMap::mergeArray($labels, parent::attributeLabels());    
    }
    
    public function attributePlaceholders()
    {
        $placeholders = array(
        	'domain'     => 'domain-1.com, domain-2.com, domain-3.com',
            'site_key'   => '6LegYwsTBBBCCPdpjWct69ScnOMG9ZRv2vy8Xbbj',
            'secret_key' => '6LegYwsTBBBCCxQmCT54Q_0bIwZH94ogQwNQCpE',
        );
        return CMap::mergeArray($placeholders, parent::attributePlaceholders());
    }
    
    public function attributeHelpTexts()
    {
        $texts = array(
            'domain'        => Yii::t('ext_recaptcha', 'The domain(s) where this key pair will be applied'),
            'site_key'      => Yii::t('ext_recaptcha', 'The site key for recaptcha service'),
            'secret_key'    => Yii::t('ext_recaptcha', 'The secret key for recaptcha service'),
        );
        return CMap::mergeArray($texts, parent::attributeHelpTexts());
    }
    
    public function _validateDomain($attribute, $params)
    {
	    $domains      = CommonHelper::getArrayFromString($this->$attribute);
	    $errorDomains = array();
	    
	    foreach ($domains as $index => $domain) {
		    if (strpos($domain, 'http') === 0 || !FilterVarHelper::url('https://' . $domain)) {
			    $errorDomains[] = $domain;
		    }
	    }
	    
	    if (!empty($errorDomains)) {
	    	$this->addError($attribute, Yii::t('ext_recaptcha', 'Invalid domains: {domains}', array(
	    		'{domains}' => implode(', ', $errorDomains),
		    )));
	    }
    }

	/**
	 * @return array
	 */
    public function getDomainsList()
    {
    	return CommonHelper::getArrayFromString($this->domain);
    }

	/**
	 * @return bool
	 */
    public function getContainsCurrentDomain() 
    {
    	if (MW_IS_CLI) {
    		return false;
	    }
    	
    	$currentDomain = parse_url(Yii::app()->request->getHostInfo(), PHP_URL_HOST);
    	return in_array($currentDomain, $this->getDomainsList());
    }
}
