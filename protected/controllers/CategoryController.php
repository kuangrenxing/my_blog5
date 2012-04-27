<?php

class CategoryController extends Controller
{
    const PAGE_SIZE=10;
    public $pageTitle="";
    private $_model;
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
    public $defaultAction="list";
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','list'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('admin','create','update','show'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
            array('allow',
            'actions'=>array('admin','create','update','delete','show'),
            'expression'=>'Yii::app()->user->status=='.User::STATUS_ADMIN.'
                || Yii::app()->user->status=='.User::STATUS_WRITER),
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
        $criteria=new CDbCriteria;
        $pages=new CPagination(User::model()->count($criteria));
        $pages->pageSize=Yii::app()->params['postsPerPage'];
        $pages->applyLimit($criteria);

        $model=$this->loadCategorySlug();
        $this->pageTitle==Yii::t('lan','Posts in category').' "'.$model->name.'"';

        $this->render('show',array(
            'model'=>$model,
            'models'=>$model->posts,
            'pages'=>$pages
        ));

    }

public function loadCategorySlug($slug=null)
{
    if($this->_model===null)
    {
        if($slug!==null || isset($_GET['slug']))
            $this->_model=Category::model()->find('slug=:slug',array('slug'=>$slug!=null?$slug:$_GET['slug']));
        if($this->_model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $this->_model;
    }
	//echo $_GET['slug'];exit;
}
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Category;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Category']))
		{
			$model->attributes=$_POST['Category'];
			if($model->save())
				//$this->redirect(array('view','id'=>$model->id));
				$this->redirect('admin');
		}

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
		$model=$this->loadCategory();

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Category']))
		{
			$model->attributes=$_POST['Category'];
			if($model->save())
				$this->redirect(array('admin'));
		}
        $this->pageTitle=Yii::t('lan','Update Category');
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
		$dataProvider=new CActiveDataProvider('Category');
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

        $criteria =new CDbCriteria;
        $pages=new CPagination(Category::model()->count($criteria));
        $pages->pageSize=self::PAGE_SIZE;

        $sort=new CSort('Category');
        $sort->applyOrder($criteria);

        $models=Category::model()->findAll($criteria);

        $this->pageTitle=Yii::t('lan','Manage Categories');
		$this->render('admin',array(
			'models'=>$models,
            'pages'=>$pages,
            'sort'=>$sort,
		));
	}
    protected function processAdminCommand()
    {
        if(isset($_POST['command'],$_POST['id']) && $_POST['command']=='delete')
        {
            $this->loadCategory($_POST['id'])->delete();
            $this->refresh();
        }

    }
    public function loadCategory($id=null)
    {
        if($this->_model===null)
        {
            if($id!==null || isset($_GET['id']))
                $this->_model=Category::model()->findByPk($id!==null ? $id: $_GET['id']);
            if($this->_model===null)
                throw new CHttpException(404,'The requested page does no exist.');
        }
        return $this->_model;
    }
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Category::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='category-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
