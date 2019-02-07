<?php

class GoogleAnalyticsPlugin extends \BloonMail\Plugins\AbstractPlugin
{
	/**
	 * @return void
	 */
	public function Init()
	{
		if ('' !== $this->Config()->Get('plugin', 'account', ''))
		{
			$this->addJs('js/include.js');
		}
	}
	
	/**
	 * @return array
	 */
	public function configMapping()
	{
		return array(
			\BloonMail\Plugins\Property::NewInstance('account')->SetLabel('Account')
				->SetAllowedInJs(true)
				->SetDescription('UA-XXXXXXXX-X')
				->SetDefaultValue(''),
			\BloonMail\Plugins\Property::NewInstance('domain_name')->SetLabel('Domain Name')
				->SetAllowedInJs(true)
				->SetDefaultValue(''),
			\BloonMail\Plugins\Property::NewInstance('universal_analytics')->SetLabel('Use Universal Analytics')
				->SetType(\BloonMail\Enumerations\PluginPropertyType::BOOL)
				->SetAllowedInJs(true)
				->SetDefaultValue(true),
			\BloonMail\Plugins\Property::NewInstance('track_pageview')->SetLabel('Track Pageview')
				->SetType(\BloonMail\Enumerations\PluginPropertyType::BOOL)
				->SetAllowedInJs(true)
				->SetDefaultValue(true),
			\BloonMail\Plugins\Property::NewInstance('send_events')->SetLabel('Send Events')
				->SetType(\BloonMail\Enumerations\PluginPropertyType::BOOL)
				->SetAllowedInJs(true)
				->SetDefaultValue(false)
		);
	}
}
