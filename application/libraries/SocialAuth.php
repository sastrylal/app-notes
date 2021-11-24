<?php

require_once APPPATH . 'third_party/facebook-php-graph-sdk/autoload.php';
require_once dirname(APPPATH) . '/vendor/autoload.php';
require_once APPPATH . 'third_party/oauth/http.php';
require_once APPPATH . 'third_party/oauth/oauth_client.php';

use Facebook\Facebook as FB;
use Facebook\Authentication\AccessToken;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Helpers\FacebookJavaScriptHelper;
use Facebook\Helpers\FacebookRedirectLoginHelper;

class SocialAuth {

    public function __construct() {
        
    }

    public function getFacebookUrl() {
        $fb = new Facebook\Facebook([
            'app_id' => FACEBOOK_APP_ID, // Replace {app-id} with your app id
            'app_secret' => FACEBOOK_APP_SECRET,
            'default_graph_version' => 'v3.2',
        ]);
        $helper = $fb->getRedirectLoginHelper();
        $permissions = ['email']; // Optional permissions

        return $helper->getLoginUrl(base_url() . 'social/fb-fb_callback/', $permissions);
    }
    
    public function getGoogleUrl() {
        $redirect_uri = base_url() . 'social/google_oauth/';

        //Create and Request to access Google API
        $client = new Google_Client();
        $client->setApplicationName("Google OAuth Login With PHP");
        $client->setClientId(GOOGLE_CLIENT_ID);
        $client->setClientSecret(GOOGLE_CLIENT_SECRET);
        //$client->setAuthConfig('client_secrets.json');
        $client->setAccessType('offline');
        $client->addScope('profile');
        $client->addScope('email');
        $client->setRedirectUri($redirect_uri);

        return $client->createAuthUrl();
    }

    public function getLinkedinUrl() {
        $linkedin_url = 'https://www.linkedin.com/oauth/v2/authorization?'.http_build_query([
            'response_type' => 'code',
            'client_id' => LINKEDIN_CLIENT_ID,
            'redirect_uri' => base_url()."social/linkedin_oauth/",
            'state' => "",
            'scope' => LINKEDIN_SCOPE
        ]);
        return $linkedin_url;
    }

}
