<?php

namespace BloonMail\Providers;

class Filters extends \BloonMail\Providers\AbstractProvider
{
	/**
	 * @var \BloonMail\Providers\Filters\FiltersInterface
	 */
	private $oDriver;

	/**
	 * @return void
	 */
	public function __construct($oDriver)
	{
		$this->oDriver = $oDriver instanceof \BloonMail\Providers\Filters\FiltersInterface ? $oDriver : null;
	}

	/**
	 * @param \BloonMail\Account $oAccount
	 * @param bool $bAllowRaw = false
	 *
	 * @return array
	 */
	public function Load($oAccount, $bAllowRaw = false)
	{
		try
		{
			return $this->IsActive() ? $this->oDriver->Load($oAccount, $bAllowRaw) : array();
		}
		catch (\MailSo\Net\Exceptions\SocketCanNotConnectToHostException $oException)
		{
			throw new \BloonMail\Exceptions\ClientException(\BloonMail\Notifications::ConnectionError, $oException);
		}
		catch (\Exception $oException)
		{
			throw new \BloonMail\Exceptions\ClientException(\BloonMail\Notifications::CantGetFilters, $oException);
		}

		return false;
	}

	/**
	 * @param \BloonMail\Account $oAccount
	 * @param array $aFilters
	 * @param string $sRaw = ''
	 * @param bool $bRawIsActive = false
	 *
	 * @return bool
	 */
	public function Save($oAccount, $aFilters, $sRaw = '', $bRawIsActive = false)
	{
		try
		{
			return $this->IsActive() ? $this->oDriver->Save(
				$oAccount, $aFilters, $sRaw, $bRawIsActive) : false;
		}
		catch (\MailSo\Net\Exceptions\SocketCanNotConnectToHostException $oException)
		{
			throw new \BloonMail\Exceptions\ClientException(\BloonMail\Notifications::ConnectionError, $oException);
		}
		catch (\MailSo\Sieve\Exceptions\NegativeResponseException $oException)
		{
			throw new \BloonMail\Exceptions\ClientException(
				\BloonMail\Notifications::ClientViewError, $oException,
					\implode("\r\n", $oException->GetResponses()));
		}
		catch (\Exception $oException)
		{
			throw new \BloonMail\Exceptions\ClientException(\BloonMail\Notifications::CantSaveFilters, $oException);
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public function IsActive()
	{
		return $this->oDriver instanceof \BloonMail\Providers\Filters\FiltersInterface;
	}
}