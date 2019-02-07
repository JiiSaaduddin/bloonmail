<?php

namespace BloonMail\Providers\Filters;

interface FiltersInterface
{
	/**
	 * @param \BloonMail\Account $oAccount
	 * @param bool $bAllowRaw = false
	 *
	 * @return array
	 */
	public function Load($oAccount, $bAllowRaw = false);

	/**
	 * @param \BloonMail\Account $oAccount
	 * @param array $aFilters
	 *
	 * @return bool
	 */
	public function Save($oAccount, $aFilters);
}
