<?php

class IspconfigChangePasswordPlugin extends \BloonMail\Plugins\AbstractPlugin
{
	public function Init()
	{
		$this->addHook('main.fabrica', 'MainFabrica');
	}

	/**
	 * @return string
	 */
	public function Supported()
	{
		if (!extension_loaded('pdo') || !class_exists('PDO'))
		{
			return 'The PHP exention PDO (mysql) must be installed to use this plugin';
		}

		$aDrivers = \PDO::getAvailableDrivers();
		if (!is_array($aDrivers) || !in_array('mysql', $aDrivers))
		{
			return 'The PHP exention PDO (mysql) must be installed to use this plugin';
		}

		return '';
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

				$sDsn = \trim($this->Config()->Get('plugin', 'pdo_dsn', ''));
				$sUser = (string) $this->Config()->Get('plugin', 'user', '');
				$sPassword = (string) $this->Config()->Get('plugin', 'password', '');

				if (!empty($sDsn) && 0 < \strlen($sUser) && 0 < \strlen($sPassword))
				{
					include_once __DIR__.'/IspConfigChangePasswordDriver.php';

					$oProvider = new IspConfigChangePasswordDriver();
					$oProvider->SetLogger($this->Manager()->Actions()->Logger());
					$oProvider->SetConfig($sDsn, $sUser, $sPassword);
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
			\BloonMail\Plugins\Property::NewInstance('pdo_dsn')->SetLabel('ISPConfig PDO dsn')
				->SetDefaultValue('mysql:host=127.0.0.1;dbname=dbispconfig'),
			\BloonMail\Plugins\Property::NewInstance('user')->SetLabel('DB User')
				->SetDefaultValue('root'),
			\BloonMail\Plugins\Property::NewInstance('password')->SetLabel('DB Password')
				->SetType(\BloonMail\Enumerations\PluginPropertyType::PASSWORD)
				->SetDefaultValue(''),
			\BloonMail\Plugins\Property::NewInstance('allowed_emails')->SetLabel('Allowed emails')
				->SetType(\BloonMail\Enumerations\PluginPropertyType::STRING_TEXT)
				->SetDescription('Allowed emails, space as delimiter, wildcard supported. Example: user1@domain1.net user2@domain1.net *@domain2.net')
				->SetDefaultValue('*')
		);
	}
}