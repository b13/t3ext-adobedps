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
 * The application logic for authentication
 *
 * @package B13\Adobedps
 * @subpackage Controller
 */
class Tx_Adobedps_Controller_AuthController extends Tx_Extbase_MVC_Controller_ActionController {

	/**
	 * @var Tx_Adobedps_Domain_Repository_FrontendUserRepository
	 */
	protected $frontendUserRepository = NULL;

	/**
	 * number of Uuids = different devices at once
	 */
	protected $maxUuids = 50;

	/**
	 * 
	 */
	public function initializeAction() {
		$this->frontendUserRepository = $this->objectManager->get('Tx_Adobedps_Domain_Repository_FrontendUserRepository');
	}


	/**
	 * Validates the request parameters by the app and forwards to the appropriate action
	 * entry point for any API call from the App
	 *
	 * @param string $mode
	 * @return	void	taken care by the view
	 */
	public function dispatchAction($mode = NULL) {

		// Gather POST variables.  For ease of testing, we are agnostic of http method used.
		$appId        = $_REQUEST['appId'];
		$appVersion   = $_REQUEST['appVersion'];
		$uuid         = $_REQUEST['uuid'];
		$emailAddress = $_REQUEST['emailAddress'];
		$password     = $_REQUEST['password'];
		
		// used to validate the auth token
		$authToken    = $_REQUEST['authToken'];

		// used for validating an issue
		$productId   = $_REQUEST['productId'];
/*
mail('steeb@b13.de', 'APP genutzt: ' .$mode, 'Request:

appId: ' . $appId . '
appVersion: ' . $appVersion . '
uuid: ' . $uuid . '
emailAddress: ' . $emailAddress . '
password: ' . $password . '

authToken: ' . $authToken . '

productId: ' . $productId . '

');
*/
		switch (strtolower($mode)) {
			case 'signinwithcredentials':
				$this->forward('login', NULL, NULL, array('email' => $emailAddress, 'password' => $password, 'uuid' => $uuid));
			break;

			case 'renewauthtoken':
				$this->forward('renewauthtoken', NULL, NULL, array('authtoken' => $authToken));
			break;

				// get all product IDs from the Adobe DPS Server, and check if they are valid
			case 'entitlements':
				$this->forward('getvalidissues', NULL, NULL, array('authtoken' => $authToken));
			break;

			case 'verifyentitlement':
				$this->forward('allowsubscription', NULL, NULL, array('authtoken' => $authToken, 'issueId' => $productId));
			break;
		}
		
		return '<ERROR>';
	}

	/**
	 * Checks for valid username and password
	 *
	 * @param string $email
	 * @param string $password
	 * @param string $uuid
	 * @dontvalidate $email
	 * @dontvalidate $password
	 * @dontvalidate $uuid
	 * @return	void	taken care by the view
	 */
	public function loginAction($email = NULL, $password = NULL, $uuid = NULL) {
		$success = FALSE;
		$user = $this->frontendUserRepository->findOneByUsername($email);
		if ($user) {
			$authtoken = $user->createAuthtoken();
			if ($user->checkPassword($password)) {
				if ($user->hasUuid($uuid)) {
					$success = TRUE;
				} elseif (count($user->getAllUuids()) < $this->maxUuids) {
					$success = TRUE;
					$user->addUuid($uuid);
					$this->frontendUserRepository->update($user);
				} else {
					// This is a failure because either the UUID isn't found OR the max number of UUIDs have been hit
					// for test reasons this is enabled for now, added by bmack on 2013-02-05
					$success = TRUE;
				}
			} else {
				// wrong passwords, unset the auth token
				$user->setAuthtoken(NULL);
				$this->frontendUserRepository->update($user);
			}
			
		}

		if ($success === TRUE) {
			if ($authtoken != $user->getAuthtoken()) {
				$user->setAuthtoken($authtoken);
				$this->frontendUserRepository->update($user);
			}
			return $this->wrapInResult('<authToken>' . $authtoken . '</authToken>', 200);
		} else {
			return $this->wrapInResult('', 401);
		}
	}


	/**
	 * Checks if the user has the auth token (= thus, valid)
	 * always called when the update library call is done (according to adobe)
	 *
	 * @param string $authtoken
	 * @return	void	taken care by the view
	 */
	public function renewauthtokenAction($authtoken = NULL) {
		$user = $this->frontendUserRepository->findOneByTxAdobedpsAuthtoken($authtoken);
		if ($authtoken && $user) {
			return $this->wrapInResult('<authToken>' . $authtoken . '</authToken>', 200);
		} else {
			return $this->wrapInResult('', 401);
		}
	}


	/**
	 * Fetches all valid issues for the user
	 * the user should only see the issues he can access (!) according to the adobe guy
	 *
	 * @param string $authtoken
	 * @return	void	taken care by the view
	 */
	public function getvalidissuesAction($authtoken = NULL) {
		$user = $this->frontendUserRepository->findOneByTxAdobedpsAuthtoken($authtoken);
		if ($user) {
			$validIssues = $this->getAllValidIssuesForUser($user);
			$xmlContent = '';
			foreach ($validIssues as $issueId => $issueInfo) {
				$xmlContent .= '<productId>' . $issueId . '</productId>';
			}
// mail('steeb@b13.de', 'getvalidissuesAction' , '<entitlements>' . $xmlContent . '</entitlements>');
			return $this->wrapInResult('<entitlements>' . $xmlContent . '</entitlements>', 200);
		} else {
// mail('steeb@b13.de', 'getvalidissuesAction' , 'empty');
			return $this->wrapInResult('', 401);
		}
	}

	/**
	 * Validates whether a user is allowed to download an issue. executed when somebody hits "download"
	 *
	 * @param string $issueId
	 * @param string $authtoken
	 * @return	void	taken care by the view
	 */
	public function allowsubscriptionAction($issueId = NULL, $authtoken = NULL) {
		$isAllowedForIssue = FALSE;
		$user = $this->frontendUserRepository->findOneByTxAdobedpsAuthtoken($authtoken);

		$validIssues = $this->getAllValidIssuesForUser($user);

		if ($user && $issueId && isset($validIssues[$issueId])) {
			return $this->wrapInResult('<entitled>true</entitled>', 200);
		} else {
			return $this->wrapInResult('<entitled>false</entitled>', 403);
		}
	}

	/**
	 * helper functions
	 */

	protected function wrapInResult($data, $responseCode = 200) {
		return '<result httpResponseCode="' . $responseCode . '">' . $data . '</result>';
	}


	/**
	 * fetches all issues as an XML from the Server
	 * example from http://edge.adobe-dcfs.com/ddp/issueServer/issues?accountId=3c578d9af6cb45399421364eac157592&targetDimension=all
	 *
	 *	<issue id="fa7ed537-8c4d-4889-97b7-efb77312f2d6" productId="com.theanalyticalscientist.tas.2013_01" formatVersion="2.0.0" targetViewer="20.0.0" version="1" subpath="">
	 *		<magazineTitle>Nr 1</magazineTitle>
	 *		<issueNumber>2013_1</issueNumber>
	 *		<publicationDate>2013-01-22T11:54:03Z</publicationDate>
	 *		<targetDimensions>
	 *			<targetDimension>1024x768</targetDimension>
	 *		</targetDimensions>
	 *		<description>sfhkfsh</description>
	 *		<manifestXRef>jan 01</manifestXRef>
	 *		<state>production</state>
	 *		<libraryPreviewUrl landscapeVersion="1" portraitVersion="1">
	 *			http://edge.adobe-dcfs.com/ddp/issueServer/issues/fa7ed537-8c4d-4889-97b7-efb77312f2d6/libraryPreview
	 *		</libraryPreviewUrl>
	 *		<brokers>
	 *			<broker>appleStore</broker>
	 *		</brokers>
	 *	</issue>
	 */
	protected function fetchAllIssuesFromDPSServer() {
		$xmlContent = t3lib_div::getUrl($this->settings['accountBaseUrl'] . $this->settings['accountId']);
		$xmlContent = new SimpleXMLElement($xmlContent);
		return $xmlContent->xpath('/results/issues/issue');
	}
	
	
	/**
	 * checks what the issues user is allowed to see
	 */
	protected function getAllValidIssuesForUser($user) {
		$validIssues = array();
		
			// fetch all issues we have at the DPS account
		$allIssues = $this->fetchAllIssuesFromDPSServer();

		foreach ($allIssues as $issue) {
			$isAllowedForIssue = FALSE;
			$issueId = (string) $issue['productId'];

			$publicationDate = strtotime($node->publicationDate);
			#if ($publicationDate >= $timeStart && $publicationDate <= $timeStop) {	// used to allow users access only for a given timeframe

			if ($publicationDate <= time()) {	// only render published issues (not the ones in the future)
				
					// it's not an application note => everybody is allowed to see it
				if (stripos($issueId, '_an') === FALSE) {
					$isAllowedForIssue = TRUE;
					// it's an application note and the user is in the specific group => allow
				} else {
					$allUsergroups = $user->getUsergroup();
					foreach ($allUsergroups as $usergroup) {
						if ($usergroup->getUid() == 4) {
							$isAllowedForIssue = TRUE;
							break;
						}
					}
				}
			}

			if ($isAllowedForIssue) {
				$validIssues[$issueId] = $issue;
			}
		}
		
		return $validIssues;
	}
}
