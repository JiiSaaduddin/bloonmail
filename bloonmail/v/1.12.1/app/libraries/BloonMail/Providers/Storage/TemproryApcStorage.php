<?php

namespace BloonMail\Providers\Storage;

class TemproryApcStorage extends \BloonMail\Providers\Storage\FileStorage
{
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
		return !!@\apc_store($this->generateFileName($oAccount, $iStorageType, $sKey, true), $sValue);
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
		$bValue = false;
		$mValue = @\apc_fetch($this->generateFileName($oAccount, $iStorageType, $sKey), $bValue);
		if (!$bValue)
		{
			$mValue = $mDefault;
		}

		return $mValue;
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
		@\apc_delete($this->generateFileName($oAccount, $iStorageType, $sKey));

		return true;
	}

	/**
	 * @param \BloonMail\Model\Account|string $oAccount
	 *
	 * @return bool
	 */
	public function DeleteStorage($oAccount)
	{
		return !!$oAccount;
	}

	/**
	 * @param \BloonMail\Model\Account|string|null $mAccount
	 * @param int $iStorageType
	 * @param string $sKey
	 * @param bool $bMkDir = false
	 * @param bool $bForDeleteAction = false
	 *
	 * @return string
	 */
	public function generateFileName($mAccount, $iStorageType, $sKey, $bMkDir = false, $bForDeleteAction = false)
	{
		$sFileName = parent::generateFileName($mAccount, $iStorageType, $sKey, false, false);
		return $sFileName.'/'.\BloonMail\Utils::GetConnectionToken().'/';
	}
}
