<?php

namespace BloonMail\Providers\Files;

interface IFiles
{
	/**
	 * @param \BloonMail\Model\Account $oAccount
	 * @param string $sKey
	 *
	 * @return string
	 */
	public function GenerateLocalFullFileName($oAccount, $sKey);

	/**
	 * @param \BloonMail\Model\Account $oAccount
	 * @param string $sKey
	 * @param resource $rSource
	 *
	 * @return bool
	 */
	public function PutFile($oAccount, $sKey, $rSource);

	/**
	 * @param CAccount $oAccount
	 * @param string $sKey
	 * @param string $sSource
	 *
	 * @return bool
	 */
	public function MoveUploadedFile($oAccount, $sKey, $sSource);

	/**
	 * @param \BloonMail\Model\Account $oAccount
	 * @param string $sKey
	 * @param string $sOpenMode = 'rb'
	 *
	 * @return resource|bool
	 */
	public function GetFile($oAccount, $sKey, $sOpenMode = 'rb');

	/**
	 * @param \BloonMail\Model\Account $oAccount
	 * @param string $sKey
	 *
	 * @return string|bool
	 */
	public function GetFileName($oAccount, $sKey);

	/**
	 * @param \BloonMail\Model\Account $oAccount
	 * @param string $sKey
	 *
	 * @return bool
	 */
	public function Clear($oAccount, $sKey);

	/**
	 * @param \BloonMail\Model\Account $oAccount
	 * @param string $sKey
	 *
	 * @return int | bool
	 */
	public function FileSize($oAccount, $sKey);

	/**
	 * @param \BloonMail\Model\Account $oAccount
	 * @param string $sKey
	 *
	 * @return bool
	 */
	public function FileExists($oAccount, $sKey);

	/**
	 * @param int $iTimeToClearInHours = 24

	 * @return bool
	 */
	public function GC($iTimeToClearInHours = 24);
}
