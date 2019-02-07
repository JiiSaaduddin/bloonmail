<?php

class PoppassdChangePasswordPlugin extends \BloonMail\Plugins\AbstractPlugin
{
	public function Init()
	{
		$this->addHook('main.fabrica', 'MainFabrica');
	}

	/**
	 * @param string $sName
	 * @param mixed $oProvider
	 */
	public function MainFabrica($sName, &$oProvider)
	{
		switch ($sName)
		{
			case 'change-password':

				include_once __DIR__.'/ChangePasswordPoppassdDriver.php';

				$oProvider = new ChangePasswordPoppassdDriver();

				$oProvider
					->SetHost($this->Config()->Get('plugin', 'host', ''))
					->SetPort((int) $this->Config()->Get('plugin', 'port', 106))
					->SetAllowedEmails(\strtolower(\trim($this->Config()->Get('plugin', 'allowed_emails', ''))))
					->SetLogger($this->Manager()->Actions()->Logger())
				;

				break;
		}
	}

	/**
	 * @return array
	 */
	public function configMapping()
	{
		return array(
			\BloonMail\Plugins\Property::NewInstance('host')->SetLabel('POPPASSD Host')
				->SetDefaultValue('127.0.0.1'),
			\BloonMail\Plugins\Property::NewInstance('port')->SetLabel('POPPASSD Port')
				->SetType(\BloonMail\Enumerations\PluginPropertyType::INT)
				->SetDefaultValue(106),
			\BloonMail\Plugins\Property::NewInstance('allowed_emails')->SetLabel('Allowed emails')
				->SetType(\BloonMail\Enumerations\PluginPropertyType::STRING_TEXT)
				->SetDescription('Allowed emails, space as delimiter, wildcard supported. Example: user1@domain1.net user2@domain1.net *@domain2.net')
				->SetDefaultValue('*')
		);
	}
}
