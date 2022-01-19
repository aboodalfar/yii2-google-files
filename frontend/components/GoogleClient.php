<?php

namespace frontend\components;

use Google_Client;
use Google_Service_Drive;
use Google\Service\Drive\DriveFile;

/**
 * Description of GoogleClient
 *
 * @author abdullah
 */
class GoogleClient implements InterfaceMyFiles
{
    protected $client_id = '600785800068-j7fcug07kbpe3eosbq1stvfrpo31kepb.apps.googleusercontent.com';
    protected $client_secret = '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';
    //protected $redirect_uri = 'https://developers.google.com/oauthplayground';
    protected $redirect_uri = 'http://vapco.wewebit.com:8080/index.php?r=google-drive%2Fview';
    
    protected $scopes = array('https://www.googleapis.com/auth/drive',);

    protected $client;
    protected $service;

    /**
     *  Construct an easy to use Google API client.
     */
    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setClientId($this->client_id);
        $this->client->setClientSecret($this->client_secret);
        $this->client->setRedirectUri($this->redirect_uri);
        $this->client->setAccessType('offline');
        $this->client->setScopes($this->scopes);
        if (isset($_SESSION['GOOGLE_ACCESS_TOKEN'])) {
            $this->client->setAccessToken($_SESSION['GOOGLE_ACCESS_TOKEN']);
            //  Checking current access token is expired
            
           
            
            if($this->client->isAccessTokenExpired()){
                // Refresh access token and add it into session
                $this->client->refreshToken($_SESSION['GOOGLE_REFRESH_TOKEN']);
                $access_token = $this->client->getAccessToken();
                $_SESSION['GOOGLE_ACCESS_TOKEN'] = $access_token;
            }
        }
    }

    /**
     *   Check if the user is logged in or not
     */
    public function isLoggedIn()
    {
        if (isset($_SESSION['GOOGLE_ACCESS_TOKEN'])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *  Authenticate the client and set access token and refresh after login
     *  @param string $code redirected code
     */
    public function authenticate($code)
    {
        $this->client->authenticate($code);
        $_SESSION['GOOGLE_ACCESS_TOKEN'] = $this->client->getAccessToken();
        $_SESSION['GOOGLE_REFRESH_TOKEN'] =  $this->client->getRefreshToken();
    }

    /**
     *  To set access token explicitely
     *  @param string $accessToken access token
     */
    public function setAccessToken($accessToken)
    {
        $this->client->setAccessToken($accessToken);
    }

    /**
     *  To get authentication URL if not in session
     *  @return string
     */
    public function getAuthUrl()
    {
        return $this->client->createAuthUrl();
    }

    /**
     *  Returns the google client object
     *  @return Google_Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     *  Initilize drive services object
     */
    public function initDriveService()
    {
        $this->service = new Google_Service_Drive($this->client);
    }

    /**
     *  Get the list of files or folders or both from given folder or root
     *  @return array list of files or folders or both from given parent directory
     */
    public function listFilesFolders()
    {

        $optParams = array(
           'pageSize' => 10,
           'fields' => 'nextPageToken, files(id, name,createdTime,thumbnailLink,size,exportLinks)'
        );
        // Returns the list of files and folders as object
        $results = $this->service->files->listFiles($optParams);
        // Return false if nothing is found
        if (count($results->getFiles()) == 0) {
            return array();
        }

        // Converting array to object
        $result = array();
        /* @var $file DriveFile */
        foreach ($results->getFiles() as $file) {
            $result[] = [
                'id' => $file->getId(),
                'name' => $file->getName(),
                'size' =>$file->getSize(),
                'created_at' => $file->getCreatedTime(),
                'hasThumbnail' =>$file->getThumbnailLink(),
                'exportLinks' => $file->getExportLinks()
            ];
        }
        return $result;
    }
}
