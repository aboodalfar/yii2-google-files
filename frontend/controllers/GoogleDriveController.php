<?php
namespace frontend\controllers;

use yii\web\Controller;
use frontend\components\GoogleClient;
use frontend\components\MyFilesFactory;
use Yii;


/**
 *
 * @author Abdullah Alfar <abood.elfar@yahoo.com>
 * @since 1.0
 */
class GoogleDriveController extends Controller{
    
    /**
     * retrieve all my files from google drive
     * @return mixed
     */
    public function actionView()
    {
        $request = Yii::$app->request;
        $googleClient = MyFilesFactory::create('google');
        if($request->get('code')){
            // Authenticate and start the session
           $googleClient->authenticate($request->get('code'));
        }
        if(!$googleClient->isLoggedIn()){ 
            // Go to Google Login Page
            return Yii::$app->getResponse()->redirect($googleClient->getAuthURL());
        }
       
        $googleClient->initDriveService();
        return $this->render('index', [
            'data'=>$googleClient->listFilesFolders()
        ]);
    }
}