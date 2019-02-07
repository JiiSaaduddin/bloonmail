<?php

namespace BloonMail\Providers;

class Storage extends \BloonMail\Providers\AbstractProvider
{
	/**
	 * @var \BloonMail\Providers\Storage\IStorage
	 */
	private $oDriver;

	/**
	 * @return void
	 */
	public function __construct(\BloonMail\Providers\Storage\IStorage $oDriver)
	{
		$this->oDriver = $oDriver;
	}

	/**
	 * @param \BloonMail\Model\Account|string|null $mAccount
	 * @param int $iStorageType
	 *
	 * @return bool
	 */
	private function verifyAccount($mAccount, $iStorageType)
	{
		if (\BloonMail\Providers\Storage\Enumerations\StorageType::NOBODY !== $iStorageType &&
			!($mAccount instanceof \BloonMail\Model\Account || \is_string($mAccount)))
		{
			return false;
		}

		return true;
	}

	/**
	 * @param \BloonMail\Model\Account|string|null $oAccount
	 * @param int $iStorageType
	 * @param string $sKey
	 * @param string $sValue
	 *
	 * @return bool
	 */
	public function Put($oAccount, $iStorageType, $sKey, $sValue)
	{
		if (!$this->verifyAccount($oAccount, $iStorageType))
		{
			return false;
		}

		return $this->oDriver->Put($oAccount, $iStorageType, $sKey, $sValue);
	}

	/**
	 * @param \BloonMail\Model\Account|string|null $oAccount
	 * @param int $iStorageType
	 * @param string $sKey
	 * @param mixed $mDefault = false
	 *
	 * @return mixed
	 */
	public function Get($oAccount, $iStorageType, $sKey, $mDefault = false)
	{
		if (!$this->verifyAccount($oAccount, $iStorageType))
		{
			return $mDefault;
		}

		return $this->oDriver->Get($oAccount, $iStorageType, $sKey, $mDefault);
	}

	/**
	 * @param \BloonMail\Model\Account|string|null $oAccount
	 * @param int $iStorageType
	 * @param string $sKey
	 *
	 * @return bool
	 */
	public function Clear($oAccount, $iStorageType, $sKey)
	{
		if (!$this->verifyAccount($oAccount, $iStorageType))
		{
			return false;
		}

		return $this->oDriver->Clear($oAccount, $iStorageType, $sKey);
	}

	/**
	 * @param \BloonMail\Model\Account|string $oAccount
	 *
	 * @return bool
	 */
	public function DeleteStorage($oAccount)
	{
		return $this->oDriver->DeleteStorage($oAccount);
	}

	/**
	 * @return bool
	 */
	public function IsActive()
	{
		return $this->oDriver instanceof \BloonMail\Providers\Storage\IStorage;
	}

	/**
	 * @return bool
	 */
	public function IsLocal()
	{
		return $this->oDriver instanceof \BloonMail\Providers\Storage\IStorage &&
			$this->oDriver->IsLocal();
	}
}
