<?php

namespace BloonMail;

class Api
{
	/**
	 * @return void
	 */
	private function __construct()
	{
	}

	/**
	 * @return bool
	 */
	public static function RunResult()
	{
		return true;
	}

	/**
	 * @staticvar bool $bOne
	 * @return bool
	 */
	public static function Handle()
	{
		static $bOne = null;
		if (null === $bOne)
		{
			$bOne = \class_exists('MailSo\Version');
			if ($bOne)
			{
				\BloonMail\Api::SetupDefaultMailSoConfig();

				$bOne = \BloonMail\Api::RunResult();
			}
		}

		return $bOne;
	}

	/**
	 * @return \BloonMail\Actions
	 */
	public static function Actions()
	{
		static $oActions = null;
		if (null === $oActions)
		{
			$oActions = \BloonMail\Actions::NewInstance();
		}

		return $oActions;
	}

	/**
	 * @return \BloonMail\Application
	 */
	public static function Config()
	{
		return \BloonMail\Api::Actions()->Config();
	}

	/**
	 * @return \MailSo\Log\Logger
	 */
	public static function Logger()
	{
		return \BloonMail\Api::Actions()->Logger();
	}

	/**
	 * @return string
	 */
	public static function SetupDefaultMailSoConfig()
	{
		if (\class_exists('MailSo\Config'))
		{
			if (\BloonMail\Api::Config()->Get('labs', 'disable_iconv_if_mbstring_supported', false) &&
				 \MailSo\Base\Utils::IsMbStringSupported() && \MailSo\Config::$MBSTRING)
			{
				\MailSo\Config::$ICONV = false;
			}

			\MailSo\Config::$MessageListFastSimpleSearch =
				!!\BloonMail\Api::Config()->Get('labs', 'imap_message_list_fast_simple_search', true);

			\MailSo\Config::$MessageListCountLimitTrigger =
				(int) \BloonMail\Api::Config()->Get('labs', 'imap_message_list_count_limit_trigger', 0);

			\MailSo\Config::$MessageListDateFilter =
				(int) \BloonMail\Api::Config()->Get('labs', 'imap_message_list_date_filter', 0);

			\MailSo\Config::$MessageListPermanentFilter =
				\trim(\BloonMail\Api::Config()->Get('labs', 'imap_message_list_permanent_filter', ''));

			\MailSo\Config::$MessageAllHeaders =
				!!\BloonMail\Api::Config()->Get('labs', 'imap_message_all_headers', false);

			\MailSo\Config::$LargeThreadLimit =
				(int) \BloonMail\Api::Config()->Get('labs', 'imap_large_thread_limit', 50);

			\MailSo\Config::$ImapTimeout =
				(int) \BloonMail\Api::Config()->Get('labs', 'imap_timeout', 300);

			\MailSo\Config::$BoundaryPrefix = '_BloonMail_';

			\MailSo\Config::$SystemLogger = \BloonMail\Api::Logger();

			$sSslCafile = \BloonMail\Api::Config()->Get('ssl', 'cafile', '');
			$sSslCapath = \BloonMail\Api::Config()->Get('ssl', 'capath', '');

			\BloonMail\Utils::$CookieDefaultPath = \BloonMail\Api::Config()->Get('labs', 'cookie_default_path', '');
			if (\BloonMail\Api::Config()->Get('labs', 'cookie_default_secure', false))
			{
				\BloonMail\Utils::$CookieDefaultSecure = true;
			}

			if (!empty($sSslCafile) || !empty($sSslCapath))
			{
				\MailSo\Hooks::Add('Net.NetClient.StreamContextSettings/Filter', function (&$aStreamContextSettings) use ($sSslCafile, $sSslCapath) {
					if (isset($aStreamContextSettings['ssl']) && \is_array($aStreamContextSettings['ssl']))
					{
						if (empty($aStreamContextSettings['ssl']['cafile']) && !empty($sSslCafile))
						{
							$aStreamContextSettings['ssl']['cafile'] = $sSslCafile;
						}

						if (empty($aStreamContextSettings['ssl']['capath']) && !empty($sSslCapath))
						{
							$aStreamContextSettings['ssl']['capath'] = $sSslCapath;
						}
					}
				});
			}

			\MailSo\Config::$HtmlStrictDebug = !!\BloonMail\Api::Config()->Get('debug', 'enable', false);

			\MailSo\Config::$CheckNewMessages = !!\BloonMail\Api::Config()->Get('labs', 'check_new_messages', true);

			if (\BloonMail\Api::Config()->Get('labs', 'strict_html_parser', true))
			{
				\MailSo\Config::$HtmlStrictAllowedAttributes = array(
					// bloonmail
					'data-wrp',
					// defaults
					'name',
					'dir', 'lang', 'style', 'title',
					'background', 'bgcolor', 'alt', 'height', 'width', 'src', 'href',
					'border', 'bordercolor', 'charset', 'direction', 'language',
					// a
					'coords', 'download', 'hreflang', 'shape',
					// body
					'alink', 'bgproperties', 'bottommargin', 'leftmargin', 'link', 'rightmargin', 'text', 'topmargin', 'vlink',
					'marginwidth', 'marginheight', 'offset',
					// button,
					'disabled', 'type', 'value',
					// col
					'align', 'valign',
					// font
					'color', 'face', 'size',
					// form
					'novalidate',
					// hr
					'noshade',
					// img
					'hspace', 'sizes', 'srcset', 'vspace', 'usemap',
					// input, textarea
					'checked', 'max', 'min', 'maxlength', 'multiple', 'pattern', 'placeholder', 'readonly', 'required', 'step', 'wrap',
					// label
					'for',
					// meter
					'low', 'high', 'optimum',
					// ol
					'reversed', 'start',
					// option
					'selected', 'label',
					// table
					'cols', 'rows', 'frame', 'rules', 'summary', 'cellpadding', 'cellspacing',
					// td
					'abbr', 'axis', 'colspan', 'rowspan', 'headers', 'nowrap'
				);
			}
		}
	}

	/**
	 * @return string
	 */
	public static function Version()
	{
		return APP_VERSION;
	}

	/**
	 * @param string $sEmail
	 * @param string $sPassword
	 * @param array $aAdditionalOptions = array()
	 * @param bool $bUseTimeout = true
	 *
	 * @return string
	 */
	public static function GetUserSsoHash($sEmail, $sPassword, $aAdditionalOptions = array(), $bUseTimeout = true)
	{
		$sSsoHash = \MailSo\Base\Utils::Sha1Rand(\md5($sEmail).\md5($sPassword));

		return \BloonMail\Api::Actions()->Cacher()->Set(\BloonMail\KeyPathHelper::SsoCacherKey($sSsoHash),
			\BloonMail\Utils::EncodeKeyValuesQ(array(
				'Email' => $sEmail,
				'Password' => $sPassword,
				'AdditionalOptions' => $aAdditionalOptions,
				'Time' => $bUseTimeout ? \time() : 0
			))) ? $sSsoHash : '';
	}

	/**
	 * @param string $sSsoHash
	 *
	 * @return bool
	 */
	public static function ClearUserSsoHash($sSsoHash)
	{
		return \BloonMail\Api::Actions()->Cacher()->Delete(\BloonMail\KeyPathHelper::SsoCacherKey($sSsoHash));
	}

	/**
	 * @param string $sEmail
	 *
	 * @return bool
	 */
	public static function ClearUserData($sEmail)
	{
		if (0 < \strlen($sEmail))
		{
			$sEmail = \MailSo\Base\Utils::IdnToAscii($sEmail);

			$oStorageProvider = \BloonMail\Api::Actions()->StorageProvider();
			if ($oStorageProvider && $oStorageProvider->IsActive())
			{
				$oStorageProvider->DeleteStorage($sEmail);
			}

			if (\BloonMail\Api::Actions()->AddressBookProvider() &&
				\BloonMail\Api::Actions()->AddressBookProvider()->IsActive())
			{
				\BloonMail\Api::Actions()->AddressBookProvider()->DeleteAllContacts($sEmail);
			}

			return true;
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public static function LogoutCurrentLogginedUser()
	{
		\BloonMail\Utils::ClearCookie('rlsession');
		return true;
	}

	/**
	 * @return void
	 */
	public static function ExitOnEnd()
	{
		if (!\defined('JMDREVOLUTION_EXIT_ON_END'))
		{
			\define('JMDREVOLUTION_EXIT_ON_END', true);
		}
	}
}
