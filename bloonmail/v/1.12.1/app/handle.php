<?php

if (!\defined('JMDREVOLUTION_APP_LIBRARIES_PATH'))
{
	\define('JMDREVOLUTION_APP_PATH', \rtrim(\realpath(__DIR__), '\\/').'/');
	\define('JMDREVOLUTION_APP_LIBRARIES_PATH', JMDREVOLUTION_APP_PATH.'libraries/');
	\define('JMDREVOLUTION_MB_SUPPORTED', \function_exists('mb_strtoupper'));

	\define('JMDREVOLUTION_INCLUDE_AS_API_DEF', isset($_ENV['JMDREVOLUTION_INCLUDE_AS_API']) && $_ENV['JMDREVOLUTION_INCLUDE_AS_API']);

	if (!defined('RL_BACKWARD_CAPABILITY'))
	{
		\define('RL_BACKWARD_CAPABILITY', true);
		include_once JMDREVOLUTION_APP_LIBRARIES_PATH.'BloonMail/Common/BackwardCapability/Account.php';
	}

	/**
	 * @param string $sClassName
	 *
	 * @return mixed
	 */
	function rainLoopSplAutoloadNamespaces()
	{
		return JMDREVOLUTION_INCLUDE_AS_API_DEF ? array('BloonMail', 'Predis') :
			array('BloonMail', 'Facebook', 'PHPThumb', 'Predis', 'SabreForBloonMail', 'Imagine', 'Detection');
	}

	/**
	 * @param string $sClassName
	 *
	 * @return mixed
	 */
	function rainLoopSplAutoloadRegisterFunction($sClassName)
	{
		if ($sClassName && '\\' === $sClassName[0])
		{
			$sClassName = \substr($sClassName, 1);
		}

		foreach (rainLoopSplAutoloadNamespaces() as $sNamespaceName)
		{
			if (0 === \strpos($sClassName, $sNamespaceName.'\\'))
			{
				$sPrefix = '';
				if ('Detection' === $sNamespaceName)
				{
					$sPrefix = 'Mobile_Detect/namespaced/';
				}

				if ('SabreForBloonMail' === $sNamespaceName && !JMDREVOLUTION_MB_SUPPORTED && !defined('RL_MB_FIXED'))
				{
					\define('RL_MB_FIXED', true);
					include_once JMDREVOLUTION_APP_LIBRARIES_PATH.'BloonMail/Common/MbStringFix.php';
				}

				return include JMDREVOLUTION_APP_LIBRARIES_PATH.$sPrefix.\strtr($sClassName, '\\', '/').'.php';
			}
		}

		return false;
	}

	\spl_autoload_register('rainLoopSplAutoloadRegisterFunction', false);
}

if (\class_exists('BloonMail\Api'))
{
	if (!\class_exists('MailSo\Version', false))
	{
		include APP_VERSION_ROOT_PATH.'app/libraries/MailSo/MailSo.php';
	}

	if (!\function_exists('spyc_load_file'))
	{
		include APP_VERSION_ROOT_PATH.'app/libraries/spyc/Spyc.php';
	}

	if (\class_exists('MailSo\Version'))
	{
		if (JMDREVOLUTION_INCLUDE_AS_API_DEF)
		{
			if (!\defined('APP_API_STARTED'))
			{
				\define('APP_API_STARTED', true);

				\BloonMail\Api::Handle();
			}
		}
		else if (!\defined('APP_STARTED'))
		{
			\define('APP_STARTED', true);

			\BloonMail\Api::Handle();
			\BloonMail\Service::Handle();

			\BloonMail\Api::ExitOnEnd();
		}
	}
}
else if (\function_exists('rainLoopSplAutoloadRegisterFunction'))
{
	\spl_autoload_unregister('rainLoopSplAutoloadRegisterFunction');
}
