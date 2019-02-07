<?php

namespace BloonMail\Providers\Settings;

class DefaultSettings implements \BloonMail\Providers\Settings\ISettings
{
	const FILE_NAME = 'settings';
	const FILE_NAME_LOCAL = 'settings_local';

	/**
	 * @var \BloonMail\Providers\Storage
	 */
	private $oStorageProvider;

	/**
	 * @param \BloonMail\Providers\Storage $oStorageProvider
	 */
	public function __construct(\BloonMail\Providers\Storage $oStorageProvider)
	{
		$this->oStorageProvider = $oStorageProvider;
	}

	/**
	 * @param \BloonMail\Model\Account $oAccount
	 *
	 * @return array
	 */
	public function Load($oAccount)
	{
		$sValue = $this->oStorageProvider->Get($oAccount,
			\BloonMail\Providers\Storage\Enumerations\StorageType::CONFIG,
			$this->oStorageProvider->IsLocal() ?
				\BloonMail\Providers\Settings\DefaultSettings::FILE_NAME_LOCAL :
				\BloonMail\Providers\Settings\DefaultSettings::FILE_NAME
		);

		$aSettings = array();
		if (\is_string($sValue))
		{
			$aData = \json_decode($sValue, true);
			if (\is_array($aData))
			{
				$aSettings = $aData;
			}
		}

		return $aSettings;
	}

	/**
	 * @param \BloonMail\Model\Account $oAccount
	 * @param array $aSettings
	 *
	 * @return bool
	 */
	public function Save($oAccount, array $aSettings)
	{
		return $this->oStorageProvider->Put($oAccount,
			\BloonMail\Providers\Storage\Enumerations\StorageType::CONFIG,
			$this->oStorageProvider->IsLocal() ?
				\BloonMail\Providers\Settings\DefaultSettings::FILE_NAME_LOCAL :
				\BloonMail\Providers\Settings\DefaultSettings::FILE_NAME,
			\json_encode($aSettings));
	}

	/**
	 * @param \BloonMail\Model\Account $oAccount
	 *
	 * @return bool
	 */
	public function Delete($oAccount)
	{
		return $this->oStorageProvider->Clear($oAccount,
			\BloonMail\Providers\Storage\Enumerations\StorageType::CONFIG,
			$this->oStorageProvider->IsLocal() ?
				\BloonMail\Providers\Settings\DefaultSettings::FILE_NAME_LOCAL :
				\BloonMail\Providers\Settings\DefaultSettings::FILE_NAME);
	}
}