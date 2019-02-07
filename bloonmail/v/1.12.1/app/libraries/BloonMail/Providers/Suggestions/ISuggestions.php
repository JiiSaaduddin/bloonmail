<?php

namespace BloonMail\Providers\Suggestions;

interface ISuggestions
{
	/**
	 * @param \BloonMail\Model\Account $oAccount
	 * @param string $sQuery
	 * @param int $iLimit = 20
	 *
	 * @return array [['email@1', 'name_1'], ['email@2', 'name_2']]
	 */
	public function Process($oAccount, $sQuery, $iLimit = 20);
}