<?php

namespace BloonMail\Providers\ChangePassword;

interface ChangePasswordInterface
{
	/**
	 * @param \BloonMail\Account $oAccount
	 *
	 * @return bool
	 */
	public function PasswordChangePossibility($oAccount);

	/**
	 * @param \BloonMail\Account $oAccount
	 * @param string $sPrevPassword
	 * @param string $sNewPassword
	 *
	 * @return bool
	 */
	public function ChangePassword(\BloonMail\Account $oAccount, $sPrevPassword, $sNewPassword);
}
