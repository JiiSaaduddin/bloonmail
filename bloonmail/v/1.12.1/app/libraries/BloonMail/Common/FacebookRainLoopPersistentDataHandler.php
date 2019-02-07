<?php

namespace BloonMail\Common;

class FacebookBloonMailPersistentDataHandler implements \Facebook\PersistentData\PersistentDataInterface
{
	/**
	 * @var string
	 */
	private $rlUserHash;

	/**
	 * @var mixed
	 */
	private $rlAccount;

	/**
	 * @var mixed
	 */
	private $rlStorageProvaider;

	public function __construct($rlAccount, $rlUserHash, $rlStorageProvaider)
	{
		$this->rlAccount = $rlAccount;
		$this->rlUserHash = $rlUserHash;
		$this->rlStorageProvaider = $rlStorageProvaider;

		if (!class_exists('BloonMail\Providers\Storage\Enumerations\StorageType') || '' === $this->rlUserHash)
		{
			$this->rlStorageProvaider = null;
		}
	}

	/**
     * Get a value from a persistent data store.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
	{
		if ($this->rlStorageProvaider)
		{
			return $this->rlStorageProvaider->Get(
				$this->rlAccount ? $this->rlAccount : null,
				$this->rlAccount ? \BloonMail\Providers\Storage\Enumerations\StorageType::USER :
					\BloonMail\Providers\Storage\Enumerations\StorageType::NOBODY,
				$this->generateSessionVariableName($key), false);
		}

		return null;
	}

    /**
     * Set a value in the persistent data store.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function set($key, $value)
	{
		if ($this->rlStorageProvaider)
		{
			$this->rlStorageProvaider->Put(
				$this->rlAccount ? $this->rlAccount : null,
				$this->rlAccount ? \BloonMail\Providers\Storage\Enumerations\StorageType::USER :
					\BloonMail\Providers\Storage\Enumerations\StorageType::NOBODY,
				$this->generateSessionVariableName($key), $value);
		}
	}

	/**
	 * @param string $key
	 * @return string
	 */
	private function generateSessionVariableName($key)
	{
		return implode('/', array('Fackebook', \md5($this->rlUserHash), 'Storage', $key));
	}
}
