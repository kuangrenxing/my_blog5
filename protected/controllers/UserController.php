<?php

class UserController extends Controller
{
	const PAGE_SIZE=10;
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	
	public $defaultAction='list';
	private $_model;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}
/* 	public function actions()
	{
		return array(
		// captcha action renders the CAPTCHA image
		// this is used by the contact page
				'captcha'=>array(
						'class'=>'CCaptchaAction',
				),
		);
	} */
	public function actions()
	{
		return array(
		// captcha action renders the CAPTCHA image displayed on the contact page
				'captcha'=>array(
						'class'=>'CCaptchaAction',
						'backColor'=>0xFFFFFF,  //背景颜色
						'minLength'=>4,  //最短为4位
						'maxLength'=>4,   //是长为4位
						'transparent'=>true,  //显示为透明，当关闭该选项，才显示背景颜色
						),
		);
	}

	

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
        $id=1;
        if(isset($_GET['id']))
            $id=$_GET['id'];

		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('show','index','view','captcha','registration','lostpass','login'),
				'users'=>array('*'),
			),
            array('allow',
                    'expression'=>'Yii::app()->user->status=='.User::STATUS_ADMIN,
                ),
            array('allow',
            'actions'=>array('update'),
            'expression'=>'Yii::app()->user->id=='.$id,
            ),

			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','list','ajaxBanned','bookmarks'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

    public function actionShow()
    {
        $model=$this->loadUser();
        $this->pageTitle=Yii::t('lan','View User').' '.$model->username;
        $this->render('show',
            array('model'=>$model));
    }

    public function actionBookmarks()
    {
        $criteria=new CDbCriteria;
        $page=new CPagination(User::model()->count($criteria));
        $page->pagesize=Yii::app()->params['postsPerPage'];
        $page->applyLimit($criteria);

        $this->render('bookmarks',array(
            'models'=>$this->loadUser(Yii::app()->user->id)->bookmarks,
            'pages'=>$page,
        ));
    }
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
    public function actionCreate()
    {
        $model=new User;
        if(isset($_POST['User']))
        {
            $model->attributes=$_POST['User'];
            if($model->password && $model->validate())
                $model->password=md5($model->password);
           
            $model->avatar=CUploadedFile::getInstance($model,'avatar');
            if($model->avatar && $model->validate()) 
            {
                $imagename=User::getRIN().'.'.$model->avatar->getExtensionName();
                //$image=Yii::app()->image->load($model->avatar->getTempName());
                //$image->resize(Yii::app()->params['avatarWidth'],Yii::app()->params['avatarHeight']);
                //$image->save(Yii::app()->params['avatarPath'].$imagename);
                
                $model->avatar->saveAs(Yii::app()->params['avatarPath'].$imagename);
                $model->avatar=$imagename;
            }
            if($model->save())
                $this->redirect(array('show','id'=>$model->id));
        }
        $this->pageTitle=Yii::t('lan','New User');
        $this->render('create',array('model'=>$model));
    }
	
    public function actionRegistration()
    {
        if(isset($_GET['code']))
        {
            $user=User::model()->find('confirmRegistration=:confirmRegistration',
                array(':confirmRegistration'=>$_GET['code']));
            if($user===null)
                throw new CHttpException(404,'The requested page does not exist.');
            $user->confirmRegistration='';
            $user->save();
            Yii::app()->user->setFlash('message',Yii::t('lan','Thank you for confirm your email. You can login with your username and password.'));
            $this->redirect(Yii::app()->homeUrl);
        }
        
        $model=new User;
        if(isset($_POST['User']))
        {
            $model->attributes=$_POST['User'];
            $model->status=User::STATUS_VISITOR;
            $model->banned=User::BANNED_NO;
            
            if($model->validate('registration'))
            {
                if(Yii::app()->params['confirmRegistration'])
                {
                    $model->confirmRegistration=$code=User::getRIN();
                    $email=Yii::app()->email;
                    $email->to=$model->email;
                    $email->from=$email->replyTo='=?koi8-r?B?'.base64_encode(iconv('UTF-8','koi8-r',Yii::app()->params['emailFrom'])).'?= <'.Yii::app()->params['adminEmail'].'>';
                    $email->message=$this->renderPartial('../email/lostpass',
                                        array('model'=>$user,'code'=>$code),true);
                    $email->subject=Yii::t('lan','Confirm registration').' - '.Yii::app()->params['emailFrom'];
                    $email->message=$this->renderPartial('../email/confirmregistration',
                                        array('model'=>$model,'code'=>$code),true);
                    $email->send();
                    
                    Yii::app()->user->setFlash('message',Yii::t('lan','Thank you for registration but you have to confirm your email. You\'ll receive an email with instructions on the next step.'));
                }
                else Yii::app()->user->setFlash('message',Yii::t('lan','Thank you for registration. You can login with your username and password.'));
                $model->password=md5($model->password);
                $model->save();
                $this->redirect(Yii::app()->homeUrl);
            }
        }
        $this->pageTitle=Yii::t('lan','Registration');
        $this->render('registration',array('model'=>$model));
    }

    public function actionLostpass()
    {
        if(isset($_GET['code']))
        {
            $user=User::model()->find('passwordLost=:passwordLost',array(':passwordLost'=>$_GET['code']));
            if($user===null)
                throw new CHttpException(404,'The requested page does not exist.');
            $user->passwordLost='';
            $password=User::getRIN();
            $user->password=md5($password);
            $user->save();
            Yii::app()->user->setFlash('message',Yii::t('lan','Your password is update. Your username: {username} Your new password: {password}',array('{username}'=>$user->username,'{password}'=>$password)));
            $this->redirect(Yii::app()->homeUrl);
        }
        
        $model=new User('lostpass');
        if(isset($_POST['User']))
        {
            $model->attributes=$_POST['User'];
            if($model->validate('lostpass'))
            { 
                $user=User::model()->find('username=:username',array(':username'=>$model->usernameoremail));
                if($user===null)
                    $user=User::model()->find('email=:email',array(':email'=>$model->usernameoremail));
                
                $user->passwordLost=$code=User::getRIN();
               
                $user->save();
              
                $email=Yii::app()->email;
                $email->to=$user->email;
                $email->from=$email->replyTo='=?koi8-r?B?'.base64_encode(iconv('UTF-8','koi8-r',Yii::app()->params['emailFrom'])).'?= <'.Yii::app()->params['adminEmail'].'>';
                $email->subject=Yii::t('lan','Lost password ?').' - '.Yii::app()->params['emailFrom'];
                $email->message=$this->renderPartial('../email/lostpass',
                                        array('model'=>$user,'code'=>$code),true);
                $email->send();

                Yii::app()->user->setFlash('message',Yii::t('lan','You\'ll receive an email with instructions on the next step.'));
                $this->redirect(Yii::app()->homeUrl);
            }
        }
        $this->pageTitle=Yii::t('lan','Lost password ?');
        $this->render('lostpass',array('model'=>$model));
    }
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];

            if($model->password && $model->validate())
                $model->password=md5($model->password);
            elseif(!$model->password)
                unset($model->password);

            if($model->id==1)
                $model->banned=User::BANNED_NO;

            $model->avatar=CUploadedFile::getInstance($model,'avatar');
            if($model->avatar && $model->validate())
            {
                $imagename=User::getRIN().'.'.$model->avatar->getExtensionName();
                //$image=Yii::app()->image->load($model->avatar->getTempName());
                //$image->resize(Yii::app()->params['avatarWidth'],Yii::app()->params['avatarHeight']);
                //$image->save(Yii::app()->params['avatarPath'].$imagename);
                $model->avatar->saveAs(Yii::app()->params['avatarPath'].$imagename);
                
                $model->avatar=$imagename;
                @unlink(Yii::app()->params['avatarPath'].User::model()->findByPk($model->id)->avatar);
            }
            else
                unset($model->avatar);
            
            if(isset($_POST['davatar']))
            {
                @unlink(Yii::app()->params['avatarPath'].User::model()->findByPk($model->id)->avatar);
                $model->avatar="";
            }
			if($model->save())
				$this->redirect(array('show','id'=>$model->id));
		}
        if($model->id==Yii::app()->user->id)
            $this->pageTitle=Yii::t('lan','My profile');
        else
            $this->pageTitle=Yii::t('lan','Update User');
        
		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
            if($_GET['id']!=1)
            {
                @unlink(Yii::app()->params['avatarPath'].User::model()->findByPk($_GET['id'])->avatar);
                $this->loadUser()->delete();
            }
            $this->redirect(array('list'));
			// we only allow deletion via POST request
//			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
//			if(!isset($_GET['ajax']))
//				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('User');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}
    public function actionList()
    {
        $criteria=new CDbCriteria;
        $pages=new CPagination(User::model()->count($criteria));
        $pages->pageSize=self::PAGE_SIZE;
        $pages->applyLimit($criteria);

        $models=User::model()->findAll($criteria);
        $this->pageTitle=Yii::t('lan','Users List');
//         foreach($models as $model)
//         {
//         	echo $model->statusText;
//         }
        $this->render('list',array(
            'models'=>$models,
            'pages'=>$pages,
        ));
    }

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new User('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['User']))
			$model->attributes=$_GET['User'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=User::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
    public function loadUser($id=null)
    {
        if($this->_model===null)
        {
            if($id!==null || isset($_GET['id']))
                $this->_model=User::model()->findByPk($id!=null ? $id :$_GET['id']);
            if($this->_model===null)
                throw new CHttpException(404,'The requested page does no exists.');
        }
        return $this->_model;
    }
    public function actionAjaxBanned()
    {
        $model=$this->loadUser();
        $options=User::getBannedOptions();
        $model->banned=(count($options)==($model->banned+1))?0:($model->banned+1);
       if($model->id==1) 
           $model->banned=User::BANNED_NO;
       $model->save();
        echo $model->bannedText;
    }
}
