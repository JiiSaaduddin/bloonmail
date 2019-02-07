<?php

namespace BloonMail\Providers;

class ChangePassword extends \BloonMail\Providers\AbstractProvider
{
	/**
	 * @var \BloonMail\Actions
	 */
	private $oActions;

	/**
	 * @var \BloonMail\Providers\ChangePassword\ChangePasswordInterface
	 */
	private $oDriver;

	/**
	 * @var bool
	 */
	private $bCheckWeak;

	/**
	 * @param \BloonMail\Actions $oActions
	 * @param \BloonMail\Providers\ChangePassword\ChangePasswordInterface|null $oDriver = null
	 * @param bool $bCheckWeak = true
	 *
	 * @return void
	 */
	public function __construct($oActions, $oDriver = null, $bCheckWeak = true)
	{
		$this->oActions = $oActions;
		$this->oDriver = $oDriver;
		$this->bCheckWeak = !!$bCheckWeak;
	}

	/**
	 * @param \BloonMail\Account $oAccount
	 *
	 * @return bool
	 */
	public function PasswordChangePossibility($oAccount)
	{
		return $this->IsActive() &&
			$oAccount instanceof \BloonMail\Account &&
			$this->oDriver && $this->oDriver->PasswordChangePossibility($oAccount)
		;
	}

	/**
	 * @param \BloonMail\Account $oAccount
	 * @param string $sPrevPassword
	 * @param string $sNewPassword
	 */
	public function ChangePassword(\BloonMail\Account $oAccount, $sPrevPassword, $sNewPassword)
	{
		$mResult = false;
		
		if ($this->oDriver instanceof \BloonMail\Providers\ChangePassword\ChangePasswordInterface &&
			$this->PasswordChangePossibility($oAccount))
		{
			if ($sPrevPassword !== $oAccount->Password())
			{
				throw new \BloonMail\Exceptions\ClientException(\BloonMail\Notifications::CurrentPasswordIncorrect);
			}

			$sPasswordForCheck = \trim($sNewPassword);
			if (6 > \strlen($sPasswordForCheck))
			{
				throw new \BloonMail\Exceptions\ClientException(\BloonMail\Notifications::NewPasswordShort);
			}

			if (!\MailSo\Base\Utils::PasswordWeaknessCheck($sPasswordForCheck))
			{
				throw new \BloonMail\Exceptions\ClientException(\BloonMail\Notifications::NewPasswordWeak);
			}

			if (!$this->oDriver->ChangePassword($oAccount, $sPrevPassword, $sNewPassword))
			{
				throw new \BloonMail\Exceptions\ClientException(\BloonMail\Notifications::CouldNotSaveNewPassword);
			}

			$oAccount->SetPassword($sNewPassword);
			$this->oActions->SetAuthToken($oAccount);

			$mResult = $this->oActions->GetSpecAuthToken();
		}
		else
		{
			throw new \BloonMail\Exceptions\ClientException(\BloonMail\Notifications::CouldNotSaveNewPassword);
		}

		return $mResult;
	}

	/**
	 * @return bool
	 */
	public function IsActive()
	{
		return $this->oDriver instanceof \BloonMail\Providers\ChangePassword\ChangePasswordInterface;
	}
}
