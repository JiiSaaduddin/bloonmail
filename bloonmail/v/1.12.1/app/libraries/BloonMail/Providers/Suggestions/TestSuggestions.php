<?php

namespace BloonMail\Providers\Suggestions;

class TestSuggestions implements \BloonMail\Providers\Suggestions\ISuggestions
{
	/**
	 * @param \BloonMail\Model\Account $oAccount
	 * @param string $sQuery
	 * @param int $iLimit = 20
	 *
	 * @return array
	 */
	public function Process($oAccount, $sQuery, $iLimit = 20)
	{
		return array(
			array($oAccount->Email(), ''),
			array('email@domain.com', 'name')
		);
	}
}
