<?php

class PiwikAnalyticsPlugin extends \BloonMail\Plugins\AbstractPlugin
{
	/**
	 * @return void
	 */
	public function Init()
	{
		if ('' !== $this->Config()->Get('plugin', 'piwik_url', '') &&
			'' !== $this->Config()->Get('plugin', 'site_id', ''))
		{
			$this->addJs('js/include.js');
		}
	}

	/**
	 * @return array
	 */
	public function configMapping()
	{
		$oUrl = \BloonMail\Plugins\Property::NewInstance('piwik_url')->SetLabel('Piwik URL')
			->SetAllowedInJs(true);

		$oSiteID = \BloonMail\Plugins\Property::NewInstance('site_id')->SetLabel('Site ID')
			->SetAllowedInJs(true);

		if (\method_exists($oUrl, 'SetPlaceholder'))
		{
			$oUrl->SetPlaceholder('http://');
			$oSiteID->SetPlaceholder('');
		}

		return array($oUrl, $oSiteID);
	}
}
