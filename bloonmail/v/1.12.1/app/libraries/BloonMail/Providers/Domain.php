<?php

namespace BloonMail\Providers;

class Domain extends \BloonMail\Providers\AbstractProvider
{
	/**
	 * @var \BloonMail\Providers\Domain\DomainInterface
	 */
	private $oDriver;

	/**
	 * @var \BloonMail\Plugins\Manager
	 */
	private $oPlugins;

	/**
	 * @var bool
	 */
	private $bAdmin;

	/**
	 * @param \BloonMail\Providers\Domain\DomainInterface $oDriver
	 *
	 * @return void
	 */
	public function __construct(\BloonMail\Providers\Domain\DomainInterface $oDriver,
		\BloonMail\Plugins\Manager $oPlugins)
	{
		$this->oDriver = $oDriver;
		$this->oPlugins = $oPlugins;
		$this->bAdmin = $this->oDriver instanceof \BloonMail\Providers\Domain\DomainAdminInterface;
	}

	/**
	 * @return bool
	 */
	public function IsAdmin()
	{
		return $this->bAdmin;
	}

	/**
	 * @param string $sName
	 * @param bool $bFindWithWildCard = false
	 * @param bool $bCheckDisabled = true
	 * @param bool $bCheckAliases = true
	 *
	 * @return \BloonMail\Model\Domain|null
	 */
	public function Load($sName, $bFindWithWildCard = false, $bCheckDisabled = true, $bCheckAliases = true)
	{
		$oDomain = $this->oDriver->Load($sName, $bFindWithWildCard, $bCheckDisabled, $bCheckAliases);
		if ($oDomain instanceof \BloonMail\Model\Domain)
		{
			$this->oPlugins->RunHook('filter.domain', array(&$oDomain));
		}

		return $oDomain;
	}

	/**
	 * @param \BloonMail\Model\Domain $oDomain
	 *
	 * @return bool
	 */
	public function Save(\BloonMail\Model\Domain $oDomain)
	{
		return $this->bAdmin ? $this->oDriver->Save($oDomain) : false;
	}

	/**
	 * @param string $sName
	 * @param string $sAlias
	 *
	 * @return bool
	 */
	public function SaveAlias($sName, $sAlias)
	{
		if ($this->Load($sName, false, false))
		{
			throw new \BloonMail\Exceptions\ClientException(\BloonMail\Notifications::DomainAlreadyExists);
		}

		return $this->bAdmin ? $this->oDriver->SaveAlias($sName, $sAlias) : false;
	}

	/**
	 * @param string $sName
	 *
	 * @return bool
	 */
	public function Delete($sName)
	{
		return $this->bAdmin ? $this->oDriver->Delete($sName) : false;
	}

	/**
	 * @param string $sName
	 * @param bool $bDisabled
	 *
	 * @return bool
	 */
	public function Disable($sName, $bDisabled)
	{
		return $this->bAdmin ? $this->oDriver->Disable($sName, $bDisabled) : false;
	}

	/**
	 * @param int $iOffset = 0
	 * @param int $iLimit = 20
	 * @param string $sSearch = ''
	 * @param bool $bIncludeAliases = true
	 *
	 * @return array
	 */
	public function GetList($iOffset = 0, $iLimit = 20, $sSearch = '', $bIncludeAliases = true)
	{
		$sSearch = \trim($sSearch);

		if ($iOffset < 0)
		{
			$iOffset = 0;
		}

		if ($iLimit < 20)
		{
			$iLimit = 20;
		}

		return $this->bAdmin ? $this->oDriver->GetList($iOffset, $iLimit, $sSearch, $bIncludeAliases) : array();
	}

	/**
	 * @param string $sSearch = ''
	 *
	 * @return int
	 */
	public function Count($sSearch = '')
	{
		return $this->oDriver->Count($sSearch);
	}

	/**
	 * @param \BloonMail\Actions $oActions
	 * @param string $sNameForTest = ''
	 *
	 * @return \BloonMail\Model\Domain | null
	 */
	public function LoadOrCreateNewFromAction(\BloonMail\Actions $oActions, $sNameForTest = '')
	{
		$oDomain = null;

		if ($this->bAdmin)
		{
			$bCreate = '1' === (string) $oActions->GetActionParam('Create', '0');
			$sName = (string) $oActions->GetActionParam('Name', '');
			$sIncHost = (string) $oActions->GetActionParam('IncHost', '');
			$iIncPort = (int) $oActions->GetActionParam('IncPort', 143);
			$iIncSecure = (int) $oActions->GetActionParam('IncSecure', \MailSo\Net\Enumerations\ConnectionSecurityType::NONE);
			$bIncShortLogin = '1' === (string) $oActions->GetActionParam('IncShortLogin', '0');
			$bUseSieve = '1' === (string) $oActions->GetActionParam('UseSieve', '0');
			$bSieveAllowRaw = '1' === (string) $oActions->GetActionParam('SieveAllowRaw', '0');
			$sSieveHost = (string) $oActions->GetActionParam('SieveHost', '');
			$iSievePort = (int) $oActions->GetActionParam('SievePort', 4190);
			$iSieveSecure = (int) $oActions->GetActionParam('SieveSecure', \MailSo\Net\Enumerations\ConnectionSecurityType::NONE);
			$sOutHost = (string) $oActions->GetActionParam('OutHost', '');
			$iOutPort = (int) $oActions->GetActionParam('OutPort', 25);
			$iOutSecure = (int) $oActions->GetActionParam('OutSecure', \MailSo\Net\Enumerations\ConnectionSecurityType::NONE);
			$bOutShortLogin = '1' === (string) $oActions->GetActionParam('OutShortLogin', '0');
			$bOutAuth = '1' === (string) $oActions->GetActionParam('OutAuth', '1');
			$bOutUsePhpMail = '1' === (string) $oActions->GetActionParam('OutUsePhpMail', '0');
			$sWhiteList = (string) $oActions->GetActionParam('WhiteList', '');

			if (0 < \strlen($sName) && 0 < strlen($sNameForTest) && false === \strpos($sName, '*'))
			{
				$sNameForTest = '';
			}

			if (0 < strlen($sName) || 0 < strlen($sNameForTest))
			{
				$oDomain = 0 < strlen($sNameForTest) ? null : $this->Load($sName);
				if ($oDomain instanceof \BloonMail\Model\Domain)
				{
					if ($bCreate)
					{
						throw new \BloonMail\Exceptions\ClientException(\BloonMail\Notifications::DomainAlreadyExists);
					}
					else
					{
						$oDomain->UpdateInstance(
							$sIncHost, $iIncPort, $iIncSecure, $bIncShortLogin,
							$bUseSieve, $sSieveHost, $iSievePort, $iSieveSecure,
							$sOutHost, $iOutPort, $iOutSecure, $bOutShortLogin, $bOutAuth, $bOutUsePhpMail,
							$sWhiteList);
					}
				}
				else
				{
					$oDomain = \BloonMail\Model\Domain::NewInstance(0 < strlen($sNameForTest) ? $sNameForTest : $sName,
						$sIncHost, $iIncPort, $iIncSecure, $bIncShortLogin,
						$bUseSieve, $sSieveHost, $iSievePort, $iSieveSecure,
						$sOutHost, $iOutPort, $iOutSecure, $bOutShortLogin, $bOutAuth, $bOutUsePhpMail,
						$sWhiteList);
				}
			}

			if ($oDomain)
			{
				$oDomain->SetSieveAllowRaw($bSieveAllowRaw);
			}
		}

		return $oDomain;
	}

	/**
	 * @param \BloonMail\Actions $oActions
	 * @param string $sNameForTest = ''
	 *
	 * @return \BloonMail\Model\Domain | null
	 */
	public function CreateNewAliasFromAction(\BloonMail\Actions $oActions, $sNameForTest = '')
	{
		$oDomain = null;

		if ($this->bAdmin)
		{
			$bCreate = '1' === (string) $oActions->GetActionParam('Create', '0');
			$sName = (string) $oActions->GetActionParam('Name', '');
			$sIncHost = (string) $oActions->GetActionParam('IncHost', '');
			$iIncPort = (int) $oActions->GetActionParam('IncPort', 143);
			$iIncSecure = (int) $oActions->GetActionParam('IncSecure', \MailSo\Net\Enumerations\ConnectionSecurityType::NONE);
			$bIncShortLogin = '1' === (string) $oActions->GetActionParam('IncShortLogin', '0');
			$bUseSieve = '1' === (string) $oActions->GetActionParam('UseSieve', '0');
			$bSieveAllowRaw = '1' === (string) $oActions->GetActionParam('SieveAllowRaw', '0');
			$sSieveHost = (string) $oActions->GetActionParam('SieveHost', '');
			$iSievePort = (int) $oActions->GetActionParam('SievePort', 4190);
			$iSieveSecure = (int) $oActions->GetActionParam('SieveSecure', \MailSo\Net\Enumerations\ConnectionSecurityType::NONE);
			$sOutHost = (string) $oActions->GetActionParam('OutHost', '');
			$iOutPort = (int) $oActions->GetActionParam('OutPort', 25);
			$iOutSecure = (int) $oActions->GetActionParam('OutSecure', \MailSo\Net\Enumerations\ConnectionSecurityType::NONE);
			$bOutShortLogin = '1' === (string) $oActions->GetActionParam('OutShortLogin', '0');
			$bOutAuth = '1' === (string) $oActions->GetActionParam('OutAuth', '1');
			$bOutUsePhpMail = '1' === (string) $oActions->GetActionParam('OutUsePhpMail', '0');
			$sWhiteList = (string) $oActions->GetActionParam('WhiteList', '');

			if (0 < \strlen($sName) && 0 < strlen($sNameForTest) && false === \strpos($sName, '*'))
			{
				$sNameForTest = '';
			}

			if (0 < strlen($sName) || 0 < strlen($sNameForTest))
			{
				$oDomain = 0 < strlen($sNameForTest) ? null : $this->Load($sName);
				if ($oDomain instanceof \BloonMail\Model\Domain)
				{
					if ($bCreate)
					{
						throw new \BloonMail\Exceptions\ClientException(\BloonMail\Notifications::DomainAlreadyExists);
					}
					else
					{
						$oDomain->UpdateInstance(
							$sIncHost, $iIncPort, $iIncSecure, $bIncShortLogin,
							$bUseSieve, $sSieveHost, $iSievePort, $iSieveSecure,
							$sOutHost, $iOutPort, $iOutSecure, $bOutShortLogin, $bOutAuth, $bOutUsePhpMail,
							$sWhiteList);
					}
				}
				else
				{
					$oDomain = \BloonMail\Model\Domain::NewInstance(0 < strlen($sNameForTest) ? $sNameForTest : $sName,
						$sIncHost, $iIncPort, $iIncSecure, $bIncShortLogin,
						$bUseSieve, $sSieveHost, $iSievePort, $iSieveSecure,
						$sOutHost, $iOutPort, $iOutSecure, $bOutShortLogin, $bOutAuth, $bOutUsePhpMail,
						$sWhiteList);
				}
			}

			if ($oDomain)
			{
				$oDomain->SetSieveAllowRaw($bSieveAllowRaw);
			}
		}

		return $oDomain;
	}

	/**
	 * @return bool
	 */
	public function IsActive()
	{
		return $this->oDriver instanceof \BloonMail\Providers\Domain\DomainInterface;
	}
}