<?php

namespace BloonMail\Providers\AddressBook;

interface AddressBookInterface
{
	/**
	 * @return bool
	 */
	public function IsSupported();
}