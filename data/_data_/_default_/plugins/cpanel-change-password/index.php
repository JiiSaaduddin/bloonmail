<?php

class CpanelChangePasswordPlugin extends \BloonMail\Plugins\AbstractPlugin
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

				$sHost = \trim($this->Config()->Get('plugin', 'host', ''));
				$iPost = (int) $this->Config()->Get('plugin', 'port', 2087);
				$sUser = (string) $this->Config()->Get('plugin', 'user', '');
				$sPassword = (string) $this->Config()->Get('plugin', 'password', '');
				$bSsl = (bool) $this->Config()->Get('plugin', 'ssl', false);

				if (!empty($sHost) && 0 < $iPost && 0 < \strlen($sUser) && 0 < \strlen($sPassword))
				{
					include_once __DIR__.'/CpanelChangePasswordDriver.php';

					$oProvider = new CpanelChangePasswordDriver();
					$oProvider->SetLogger($this->Manager()->Actions()->Logger());
					$oProvider->SetConfig($sHost, $iPost, $bSsl, $sUser, $sPassword);
					$oProvider->SetAllowedEmails(\strtolower(\trim($this->Config()->Get('plugin', 'allowed_emails', ''))));
				}

				break;
		}
	}

	/**
	 * @return array
	 */
	public function configMapping()
	{
		return array(
			\BloonMail\Plugins\Property::NewInstance('host')->SetLabel('cPanel Host')
				->SetDefaultValue(''),
			\BloonMail\Plugins\Property::NewInstance('port')->SetLabel('cPanel Port')
				->SetType(\BloonMail\Enumerations\PluginPropertyType::INT)
				->SetDefaultValue(2087),
			\BloonMail\Plugins\Property::NewInstance('ssl')->SetLabel('Use SSL')
				->SetType(\BloonMail\Enumerations\PluginPropertyType::BOOL)
				->SetDefaultValue(false),
			\BloonMail\Plugins\Property::NewInstance('user')->SetLabel('cPanel User')
				->SetDefaultValue(''),
			\BloonMail\Plugins\Property::NewInstance('password')->SetLabel('cPanel Password')
				->SetType(\BloonMail\Enumerations\PluginPropertyType::PASSWORD)
				->SetDefaultValue(''),
			\BloonMail\Plugins\Property::NewInstance('allowed_emails')->SetLabel('Allowed emails')
				->SetType(\BloonMail\Enumerations\PluginPropertyType::STRING_TEXT)
				->SetDescription('Allowed emails, space as delimiter, wildcard supported. Example: user1@domain1.net user2@domain1.net *@domain2.net')
				->SetDefaultValue('*')
		);
	}
}