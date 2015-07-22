<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}
        /**
         * Returns list of languages
         */
        public function actionGetLanguages(){
            $model = new Languages();
            
            $response = $model->listLanguages();
            if(isset($response)){
                echo json_encode($response);
            }
            
            
        }
        /**
         * According to the post request from user
         * returns corresponding albums of that language
         */
        public function actionGetAlbums(){

            $model = new Albums();
            
            $languageslist = @$_POST['languages'];
            if($languageslist != ""){
                
            $languageslist = substr($languageslist,1);
            $languageslist = substr($languageslist,0,-1);
            
            //$languageslist = explode(",", $languageslist);
            //print_r($languageslist);
            $response = $model->getAlbums($languageslist);
            
            if(isset($response)){
                echo json_encode($response);
            }
           }
            
        }
        /**
         * According to post request from user
         * returns corresponding songs of the selected albums
         */
        public function actionGetSongs(){
            //sleep(10);
            $model = new Songs();
            //print_r($_POST);
            $albumlist = @$_POST['albumlist'];
            //print_r($albumlist);
            $albumlist= "[1,2]";
            if($albumlist != ""){
                
            $albumlist = substr($albumlist,1);
            $albumlist = substr($albumlist,0,-1);
            //echo $albumlist;
//            $data = Songs::model()->findAll('album1 in ('.$albumlist.') || album2 in ('.$albumlist.')'
//                    . '|| album3 in ('.$albumlist.') || album4 in ('.$albumlist.') || album5 in ('.$albumlist.')');
            $data = Songs::model()->findAll();
            $i = 0;
            $response = null;
            foreach ($data as $song){
                $response[$i] = ["id"=>$song->id,"title"=>$song->song_name,"url"=>Yii::app()->params['siteUrl']."/songs/kannada/".$song->song_url];
                $i++;
            }
            
            if(isset($response)){
                echo json_encode($response);
            }
            
        }
        }
//        public function actionGetSongs(){
//            sleep(10);
//            $model = new Songs();
//            //print_r($_POST);
//            $albumlist = @$_POST['albumlist'];
//            //print_r($albumlist);
//            $albumlist= "[1,2]";
//            if($albumlist != ""){
//                
//            $albumlist = substr($albumlist,1);
//            $albumlist = substr($albumlist,0,-1);
//            //echo $albumlist;
//            $response = $model->getSongs($albumlist);
//            if(isset($response)){
//                echo json_encode($response);
//            }
//            
//        }
//        }
        /**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}