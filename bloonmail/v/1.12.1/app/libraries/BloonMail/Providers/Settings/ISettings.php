<?php

namespace BloonMail\Providers\Settings;

interface ISettings
{
	/**
	 * @param \BloonMail\Model\Account $oAccount
	 *
	 * @return array
	 */
	public function Load($oAccount);

	/**
	 * @param \BloonMail\Model\Account $oAccount
	 * @param array $aSettings
	 *
	 * @return bool
	 */
	public function Save($oAccount, array $aSettings);

	/**
	 * @param \BloonMail\Model\Account $oAccount
	 *
	 * @return bool
	 */
	public function Delete($oAccount);
}