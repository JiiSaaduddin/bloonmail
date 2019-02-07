<?php

class OverrideSmtpCredentialsPlugin extends \BloonMail\Plugins\AbstractPlugin
{
	public function Init()
	{
		$this->addHook('filter.smtp-credentials', 'FilterSmtpCredentials');
	}

	/**
	 * @param \BloonMail\Account $oAccount
	 * @param array $aSmtpCredentials
	 */
	public function FilterSmtpCredentials($oAccount, &$aSmtpCredentials)
	{
		if ($oAccount instanceof \BloonMail\Account && \is_array($aSmtpCredentials))
		{
			$sEmail = $oAccount->Email();

			$sHost = \trim($this->Config()->Get('plugin', 'smtp_host', ''));
			$sWhiteList = \trim($this->Config()->Get('plugin', 'override_users', ''));

			if (0 < strlen($sWhiteList) && 0 < \strlen($sHost) && \BloonMail\Plugins\Helper::ValidateWildcardValues($sEmail, $sWhiteList))
			{
				$aSmtpCredentials['Host'] = $sHost;
				$aSmtpCredentials['Port'] = (int) $this->Config()->Get('plugin', 'smtp_port', 25);

				$sSecure = \trim($this->Config()->Get('plugin', 'smtp_secure', 'None'));
				switch ($sSecure)
				{
					case 'SSL':
						$aSmtpCredentials['Secure'] = MailSo\Net\Enumerations\ConnectionSecurityType::SSL;
						break;
					case 'TLS':
						$aSmtpCredentials['Secure'] = MailSo\Net\Enumerations\ConnectionSecurityType::STARTTLS;
						break;
					default:
						$aSmtpCredentials['Secure'] = MailSo\Net\Enumerations\ConnectionSecurityType::NONE;
						break;
				}

				$aSmtpCredentials['UseAuth'] = (bool) $this->Config()->Get('plugin', 'smtp_auth', true);
				$aSmtpCredentials['Login'] = \trim($this->Config()->Get('plugin', 'smtp_user', ''));
				$aSmtpCredentials['Password'] = (string) $this->Config()->Get('plugin', 'smtp_password', '');
			}
		}
	}

	/**
	 * @return array
	 */
	public function configMapping()
	{
		return array(
			\BloonMail\Plugins\Property::NewInstance('smtp_host')->SetLabel('SMTP Host')
				->SetDefaultValue(''),
			\BloonMail\Plugins\Property::NewInstance('smtp_port')->SetLabel('SMTP Port')
				->SetType(\BloonMail\Enumerations\PluginPropertyType::INT)
				->SetDefaultValue(25),
			\BloonMail\Plugins\Property::NewInstance('smtp_secure')->SetLabel('SMTP Secure')
				->SetType(\BloonMail\Enumerations\PluginPropertyType::SELECTION)
				->SetDefaultValue(array('None', 'SSL', 'TLS')),
			\BloonMail\Plugins\Property::NewInstance('smtp_auth')->SetLabel('Use auth')
				->SetType(\BloonMail\Enumerations\PluginPropertyType::BOOL)
				->SetDefaultValue(true),
			\BloonMail\Plugins\Property::NewInstance('smtp_user')->SetLabel('SMTP User')
				->SetDefaultValue(''),
			\BloonMail\Plugins\Property::NewInstance('smtp_password')->SetLabel('SMTP Password')
				->SetType(\BloonMail\Enumerations\PluginPropertyType::PASSWORD)
				->SetDefaultValue(''),
			\BloonMail\Plugins\Property::NewInstance('override_users')->SetLabel('Override users')
				->SetType(\BloonMail\Enumerations\PluginPropertyType::STRING_TEXT)
				->SetDescription('space as delimiter, wildcard supported.')
				->SetDefaultValue('user@example.com *@example2.com')
		);
	}
}
