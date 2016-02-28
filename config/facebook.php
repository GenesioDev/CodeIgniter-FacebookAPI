<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @see ../libraries/FacebookAPI.php
 */

$config['facebook_appId']               = 'YOUR_APP_ID';
$config['facebook_appSecret']           = 'YOUR_APP_SECRET';
$config['facebook_redirectUri']         = 'YOUR_RETURN_URL_TO_GET_TOKEN';
$config['facebook_permissions']         = array(
); // Array of permissions
$config['facebook_accessTokenUrl']      = 'https://graph.facebook.com/oauth/access_token';
