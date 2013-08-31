<?php

/***************************************************************
 *  Copyright notice - MIT License (MIT)
 *
 *  (c) 2013 b:dreizehn GmbH,
 * 		Benjamin Mack <benjamin.mack@b13.de>
 *  All rights reserved
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy
 *  of this software and associated documentation files (the "Software"), to deal
 *  in the Software without restriction, including without limitation the rights
 *  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *  copies of the Software, and to permit persons to whom the Software is
 *  furnished to do so, subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in
 *  all copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 *  THE SOFTWARE.
 ***************************************************************/

/**
 * The domain model of a Frontend User
 * 
 * @package Tx_Adobedps
 * @subpackage Domain\Model
 * @entity
 */
class Tx_Adobedps_Domain_Model_FrontendUser extends TYPO3\CMS\Extbase\Domain\Model\FrontendUser {
	
	/**
	 * Username (= email address)
	 * @var string
	 */
	protected $username;

	/**
	 * Password
	 * @var string
	 */
	protected $password;

	/**
	 * Auth Token
	 * @var string
	 */
	protected $txAdobedpsAuthtoken;

	/**
	 * Uuids
	 * @var string
	 */
	protected $txAdobedpsUuids;


	/**
	 * sets the username attribute
	 * 
	 * @param	string $username
	 * @return	void
	 */
	public function setUsername($username) {
		$this->username = $username;
	}

	/**
	 * returns the username attribute
	 * 
	 * @return	string
	 */
	public function getUsername() {
		return $this->username;
	}

	/**
	 * sets the password attribute
	 * 
	 * @param	string	 $password
	 * @return	void
	 */
	public function setPassword($password) {
		if (t3lib_extMgm::isLoaded('saltedpasswords')) {
			if (tx_saltedpasswords_div::isUsageEnabled('FE')) {
		 		$objSalt = tx_saltedpasswords_salts_factory::getSaltingInstance(NULL);
				if (is_object($objSalt)) {
					$password = $objSalt->getHashedPassword($password);
				}
			}
		}
		$this->password = $password;
	}

	/**
	 * returns the password attribute
	 * 
	 * @return	string
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * checks if the given password matches
	 */
	public function checkPassword($password2) {
		$password = $password2;  // plain-text password
		$saltedPassword = $this->password;  // salted user password hash
		$success = FALSE; // keeps status if plain-text password matches given salted user password hash

		if (t3lib_extMgm::isLoaded('saltedpasswords')) {
			if (tx_saltedpasswords_div::isUsageEnabled('FE')) {
			 	$objSalt = tx_saltedpasswords_salts_factory::getSaltingInstance($saltedPassword);
				if (is_object($objSalt)) {
					$success = $objSalt->checkPassword($password, $saltedPassword);
				}
			}
		} else {
			$success = ($this->password == $password2) ? TRUE : FALSE;
		}

		return $success;
	}

	/**
	 * sets the txAdobedpsAuthtoken attribute
	 * 
	 * @param	string $txAdobedpsAuthtoken
	 * @return	void
	 */
	public function setAuthtoken($authtoken) {
		$this->txAdobedpsAuthtoken = $authtoken;
	}

	/**
	 * returns the txAdobedpsAuthtoken attribute
	 * 
	 * @return	string
	 */
	public function getAuthtoken() {
		return $this->txAdobedpsAuthtoken;
	}


	public function createAuthtoken() {
		return md5('AUTH' . $this->uid . '-' . $this->username);
	}


	/**
	 * sets the txAdobedpsAuthtoken attribute
	 * 
	 * @param	array $txAdobedpsAuthtoken
	 * @return	void
	 */
	public function setAllUuids($uuids) {
		if (is_array($uuids)) {
			$this->txAdobedpsUuids = implode(',', $uuids);
		} else {
			$this->txAdobedpsUuids = NULL;
		}
	}

	/**
	 * returns the txAdobedpsUuids attribute
	 * 
	 * @return	array
	 */
	public function getAllUuids() {
		if ($this->txAdobedpsUuids) {
			return explode(',', $this->txAdobedpsUuids);
		} else {
			return array();
		}
	}

	/**
	 * adds a uuid to the list of all uuids
	 * 
	 * @param	string $uuid
	 * @return	void
	 */
	public function addUuid($uuid) {
		if (!$this->hasUuid($uuid)) {
			$allUuids = $this->getAllUuids();
			$allUuids[] = $uuid;
			$this->setAllUuids($allUuids);
		}
	}

	/**
	 * checks whether the uuid attribute as a specific uuid
	 * 
	 * @return	boolean
	 */
	public function hasUuid($uuid) {
		$allUuids = $this->getAllUuids();
		return (in_array($uuid, $allUuids) ? TRUE : FALSE);
	}

}