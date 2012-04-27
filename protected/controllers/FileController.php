<?php

class FileController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
    public $defaultAction='admin';
    const PAGE_SIZE=50;
    private $_model;

    public function init()
    {
        if(isset($_POST['PHPSESSID']))
        {
            session_id($_POST['PHPSESSID']);
            session_start();
        }
        parent::init();
    }
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
    public function accessRules()
    {
        return array(
            array('allow',
                'expression'=>'Yii::app()->user->status=='.User::STATUS_ADMIN.
                    '||Yii::app()->user->status=='.User::STATUS_WRITER,
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

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new File;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
/*
		if(isset($_POST['File']))
		{
			$model->attributes=$_POST['File'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}
 */
        $this->pageTitle=Yii::t('lan','New File');
		$this->render('create',array(
			'model'=>$model,
		));
	}
	
	public function actionUpload()
	{
		// Get the session Id passed from SWFUpload. We have to do this to work-around the Flash Player Cookie Bug
		if(isset($_POST['PHPSESSID']))
			session_id($_POST['PHPSESSID']);
		// Check the upload
		if(!isset($_FILES['Filedata']) || !is_uploaded_file($_FILES['Filedata']['tmp_name']) || $_FILES['Filedata']['error']!=0) exit(0);
	
		if(file_exists(Yii::app()->params['filePath'].$_FILES['Filedata']['name']))
			$_FILES['Filedata']['name']=File::getNonExistName(Yii::app()->params['filePath'].$_FILES['Filedata']['name']);
	
		if(@!move_uploaded_file($_FILES['Filedata']['tmp_name'],Yii::app()->params['filePath'].$_FILES['Filedata']['name']))
			exit(0);
	
		$model=new File;
		$model->name=$_FILES['Filedata']['name'];
		$f=escapeshellarg(Yii::app()->params['filePath'].$_FILES['Filedata']['name']);
		$model->type= strtolower(pathinfo($model->name,PATHINFO_EXTENSION));
		$model->createTime=time();
	
		$model->save();
	
	
		echo $this->renderPartial('_create',array('model'=>$model));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
        $file=$model->name;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['File']))
		{
			$model->attributes=$_POST['File'];
			if($model->save())
            {
                if($file!=$model->name)
                    rename(Yii::app()->params['filePath'].$file,Yii::app()->params['filePath'].$model->name);
				$this->redirect(array('admin'));
            }
		}

		$this->render('update',array(
			'model'=>$model,
            'file'=>$file,
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
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('File');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$this->processAdminCommand();

        $criteria=new CDbCriteria;
        
        $pages=new CPagination(File::model()->count($criteria));
        $pages->pageSize=self::PAGE_SIZE;
        $pages->applyLimit($criteria);

        $sort=new CSort('File');
        $sort->applyOrder($criteria);

        $models=File::model()->findAll($criteria);
        
        $this->pageTitle=Yii::t('lan','Manage Files');
        $this->render('admin',array(
            'models'=>$models,
            'pages'=>$pages,
            'sort'=>$sort,
        ));
	}
	protected function processAdminCommand()
	{
		if(isset($_POST['command'], $_POST['id']) && $_POST['command']==='delete')
		{
			@unlink(Yii::app()->params['filePath'].File::model()->findbyPk($_POST['id'])->name);
			$this->loadFile($_POST['id'])->delete();
			// reload the current page to avoid duplicated delete actions
			$this->refresh();
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=File::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='file-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
