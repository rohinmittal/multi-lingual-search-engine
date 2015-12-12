<?php
class Bing {
	private $_client_id = '';
	private $_client_secret = '';
	private $_grant_type = 'client_credentials';
	private $_scope_url = 'http://api.microsofttranslator.com';
	//public function __construct($clientID, $clientSecret) {
	public function __construct($bingCred) {
		$this->_client_id = $bingCred['clientID'];
		$this->_client_secret = $bingCred['clientSecret'];
	}

	public function getResponse($url) {
		$curlHandler = curl_init();
		curl_setopt($curlHandler, CURLOPT_URL, $url);
		curl_setopt($curlHandler, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$this->getToken(), 'Content-Type: text/xml'));
		curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curlHandler, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($curlHandler);
		curl_close($curlHandler);
		return $response;
	}

	public function getToken($clientID = '', $clientSecret = '') {
		$clientID = (trim($clientID) === '') ? $this->_client_id : $clientID;
		$clientSecret = (trim($clientSecret) === '') ? $this->_client_secret : $clientSecret;
		$curlHandler = curl_init();
		$request = 'grant_type='.urlencode($this->_grant_type).'&scope='.urlencode($this->_scope_url).'&client_id='.urlencode($clientID).'&client_secret='.urlencode($clientSecret);

		curl_setopt($curlHandler, CURLOPT_URL, 'https://datamarket.accesscontrol.windows.net/v2/OAuth2-13/');
		curl_setopt($curlHandler, CURLOPT_POST, true);
		curl_setopt($curlHandler, CURLOPT_POSTFIELDS, $request);
		curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curlHandler, CURLOPT_SSL_VERIFYPEER, false);

		$response = curl_exec($curlHandler);

		curl_close($curlHandler);

		$responseObject = json_decode($response);
		return $responseObject->access_token;
	}

	public function getTranslation($fromLanguage, $toLanguage, $text) {
		$response = $this->getResponse($this->getURL($fromLanguage, $toLanguage, $text));
		return strip_tags($response);
	}

	public function getURL($fromLanguage, $toLanguage, $text) {
		return 'http://api.microsofttranslator.com/v2/Http.svc/Translate?text='.urlencode($text).'&to='.$toLanguage.'&from='.$fromLanguage;
	}
}
?>
