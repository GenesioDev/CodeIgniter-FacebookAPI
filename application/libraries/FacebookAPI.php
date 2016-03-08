<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Facebook\Facebook;

/**
 * CodeIgniter Facebook
 */
class FacebookAPI {

    /**
     * CI
     *
     * CodeIgniter instance
     * @var 	object
     */
    private $_ci;

    /**
     * FB
     *
     * Facebook instance
     * @var 	object
     */
    private $_fb;

    /**
     * App ID
     *
     * @var 	string
     */
    private $_appId;

    /**
     * App Secret
     *
     * Secret key for Facebook API
     * @var		string
     */
    private $_appSecret;

    /**
     * Redirect URI
     *
     * URI to redirect to after Facebook connection
     * @var		string
     */
    private $_redirectUri;

    /**
     * Token URL
     *
     * Facebook Access Token URL
     * @var		string
     */
    private $_tokenUrl;

    /**
     * Permissions
     *
     * List permission
     * @var		array
     */
    private $_permissions;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Load Config File
        $this->_ci = get_instance();
        $this->_ci->load->config('facebook');

        $this->_appId       = $this->_ci->config->item('facebook_appId');
        $this->_appSecret   = $this->_ci->config->item('facebook_appSecret');
        $this->_redirectUri = $this->_ci->config->item('facebook_redirectUri');
        $this->_permissions = $this->_ci->config->item('facebook_permissions');
        $this->_tokenUrl    = $this->_ci->config->item('facebook_accessTokenUrl');
    }

    /**
     * Init Facebook API
     */
    public function init($token = FALSE) {
        $this->_fb = new Facebook(array(
            'app_id'        => $this->_appId,
            'app_secret'    => $this->_appSecret,
        ));
    }

    /**
     * Get Facebook login url
     */
    public function getLoginUrl() {
        $this->init();
        $helper = $this->_fb->getRedirectLoginHelper();
        $loginUrl = $helper->getLoginUrl($this->_redirectUri, $this->_permissions);

        return $loginUrl;
    }

    /**
     * Get token
     */
    public function getToken() {
        $this->init();
        $helper = $this->_fb->getRedirectLoginHelper();
        $tempToken = $helper->getAccessToken();

        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $this->_tokenUrl);
        curl_setopt($c, CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/x-www-form-urlencoded'
            )
        );
        curl_setopt($c,	CURLOPT_POST, TRUE);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($c, CURLOPT_POSTFIELDS, 'grant_type=fb_exchange_token&client_id=' . $this->_appId . '&client_secret=' . $this->_appSecret . '&fb_exchange_token=' . $tempToken);

        $result = curl_exec($c);

        curl_close($c);

        $aData = explode('&', $result);
        $aData[0] = explode('=', $aData[0]);
        $aData[1] = explode('=', $aData[1]);

        $return = array(
            'access_token' => $aData[0][1],
            'expires' => $aData[1][1],
        );

        return $return;
    }
}
