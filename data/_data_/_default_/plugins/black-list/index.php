<?php

class BlackListPlugin extends \BloonMail\Plugins\AbstractPlugin
{
	public function Init()
	{
		$this->addHook('filter.login-credentials', 'FilterLoginCredentials');
	}

	/**
	 * @param string $sEmail
	 * @param string $sLogin
	 * @param string $sPassword
	 *
	 * @throws \BloonMail\Exceptions\ClientException
	 */
	public function FilterLoginCredentials(&$sEmail, &$sLogin, &$sPassword)
	{
		$sBlackList = \trim($this->Config()->Get('plugin', 'black_list', ''));
		if (0 < \strlen($sBlackList) && \BloonMail\Plugins\Helper::ValidateWildcardValues($sEmail, $sBlackList))
		{
			$sExceptions = \trim($this->Config()->Get('plugin', 'exceptions', ''));
			if (0 === \strlen($sExceptions) || !\BloonMail\Plugins\Helper::ValidateWildcardValues($sEmail, $sExceptions))
			{
				throw new \BloonMail\Exceptions\ClientException(
					$this->Config()->Get('plugin', 'auth_error', true) ?
						\BloonMail\Notifications::AuthError : \BloonMail\Notifications::AccountNotAllowed);
			}
		}
	}

	/**
	 * @return array
	 */
	public function configMapping()
	{
		return array(
			\BloonMail\Plugins\Property::NewInstance('auth_error')->SetLabel('Auth Error')
				->SetType(\BloonMail\Enumerations\PluginPropertyType::BOOL)
				->SetDescription('Throw an authentication error instead of an access error.')
				->SetDefaultValue(true),
			\BloonMail\Plugins\Property::NewInstance('black_list')->SetLabel('Black List')
				->SetType(\BloonMail\Enumerations\PluginPropertyType::STRING_TEXT)
				->SetDescription('Emails black list, space as delimiter, wildcard supported.')
				->SetDefaultValue('*@domain1.com user@domain2.com'),
			\BloonMail\Plugins\Property::NewInstance('exceptions')->SetLabel('Exceptions')
				->SetType(\BloonMail\Enumerations\PluginPropertyType::STRING_TEXT)
				->SetDescription('Exceptions for black list, space as delimiter, wildcard supported.')
				->SetDefaultValue('demo@domain1.com *@domain2.com admin@*')
		);
	}
}
