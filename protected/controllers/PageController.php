<?php

class PageController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
    public $defaultAction="show";
    private $_model;
    const PAGE_SIZE=10;
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
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('show'),
				'users'=>array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
            'expression'=>'Yii::app()->user->status=='.User::STATUS_ADMIN.'
            || Yii::app()->user->status=='.User::STATUS_WRITER,
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
        $model=$this->loadPageSlug();
        $this->pageTitle=$model->title;
        $this->render('show',array('model'=>$model));
    }
    public function loadPageSlug($slug=null)
    {
        if($this->_model===null)
        {
            if(isset($id) && $id!==null || isset($_GET['slug']))
                $this->_model=Page::model()->find('slug=:slug',array(':slug'=>$slug!==null ? $slug : $_GET['slug']));
            if($this->_model===null || Yii::app()->user->status!=User::STATUS_ADMIN && Yii::app()->user->status!=User::STATUS_WRITER)
                throw new CHttpException(404,'The requested page does not exist.');
        }
        return $this->_model;
    }
    
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Page;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Page']))
		{
			$model->attributes=$_POST['Page'];
            if($_POST['previewPage'])
                $model->validate();
			elseif(isset($_POST['submitPage']) && $model->save())
				$this->redirect(array('view','id'=>$model->id));
		}
    $this->pageTitle=Yii::t('lan','New Page');
		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate()
	{
		$model=$this->loadPage();

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Page']))
		{
			$model->attributes=$_POST['Page'];
			 if(isset($_POST['previewPage']))
                $model->validate();
			elseif(isset($_POST['submitPage']) && $model->save())
				$this->redirect(array('show','slug'=>$model->slug));
		}
		$this->pageTitle=Yii::t('lan','Update Page');
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
		$dataProvider=new CActiveDataProvider('Page');
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

        $criteria=new CDbCriteria();

        $pages=new CPagination(Page::model()->count($criteria));
        $pages->pageSize=self::PAGE_SIZE;
        $pages->applyLimit($criteria);
        $models=Page::model()->findAll($criteria);

        
        $sort=new CSort('Page');
        $sort->defaultOrder='status ASC, createTime DESC';
        $sort->applyOrder($criteria);
        
       // $sort=new CSort('Page');
        //$sort->applyOrder($criteria);
        
        $this->pageTitle=Yii::t('lan','Mange Pages');
        $this->render('admin',array(
            'models'=>$models,
            'pages'=>$pages,
            'sort'=>$sort,
        ));
	}
	public function loadPage($id=null)
	{
		if($this->_model===null)
		{
			if(isset($id) && $id!==null || isset($_GET['id']))
				$this->_model=Page::model()->findbyPk($id!==null ? $id : $_GET['id']);
			if($this->_model===null || Yii::app()->user->status!=User::STATUS_ADMIN && Yii::app()->user->status!=User::STATUS_WRITER && $this->_model->status!=Post::STATUS_PUBLISHED)
				throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}

    protected function processAdminCommand()
    {
        if(isset($_POST['command'], $_POST['id']) && $_POST['command']==='delete')
        {
            $this->loadPage($_POST['id'])->delete();
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
		$model=Page::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='page-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	public function actionAjaxStatus()
	{
		$model=$this->loadPage();
		$options=Page::getStatusOptions();
		$model->status=(count($options)==($model->status+1))?0:($model->status+1);
		$model->save();
		echo $model->statusText;
	}
}
