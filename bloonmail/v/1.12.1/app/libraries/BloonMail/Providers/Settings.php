<?php

namespace BloonMail\Providers;

class Settings extends \BloonMail\Providers\AbstractProvider
{
	/**
	 * @var \BloonMail\Providers\Settings\ISettings
	 */
	private $oDriver;

	/**
	 * @param \BloonMail\Providers\Settings\ISettings $oDriver
	 *
	 * @return void
	 */
	public function __construct(\BloonMail\Providers\Settings\ISettings $oDriver)
	{
		$this->oDriver = $oDriver;
	}

	/**
	 * @param \BloonMail\Model\Account $oAccount
	 *
	 * @return \BloonMail\Settings
	 */
	public function Load(\BloonMail\Model\Account $oAccount)
	{
		$oSettings = new \BloonMail\Settings();
		$oSettings->InitData($this->oDriver->Load($oAccount));
		return $oSettings;
	}

	/**
	 * @param \BloonMail\Model\Account $oAccount
	 * @param \BloonMail\Settings $oSettings
	 *
	 * @return bool
	 */
	public function Save(\BloonMail\Model\Account $oAccount, \BloonMail\Settings $oSettings)
	{
		return $this->oDriver->Save($oAccount, $oSettings->DataAsArray());
	}

	/**
	 * @return bool
	 */
	public function IsActive()
	{
		return $this->oDriver instanceof \BloonMail\Providers\Settings\ISettings;
	}
}
