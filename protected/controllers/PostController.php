<?php

class PostController extends BaseController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

    public $defaultAction="list";

    const PAGE_SIZE=10;

    private $_model;
    public function actions()
    {
        return array(
            'captcha'=>array(            	
                'class'=>'CCaptchaAction',
            	'minLength'=>4,
            	'maxLength'=>4,
            	'backColor'=>0xffffff,
            ),
        );
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','show','list','captcha','test','test1'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','ajaxbookmark'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
            array('allow',
            'expression'=>'Yii::app()->user->status=='.User::STATUS_ADMIN.'
            ||Yii::app()->user->status=='.User::STATUS_WRITER),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
    public function actionTest()
    {
        // echo "cc";echo "<br>";
//         $sql="select tagId from PostTag where postId=33";
//         $a=Yii::app()->db->createCommand($sql)->queryAll();
       // while($row=mysql_fetch_array($a))
        //{
         //   echo $row['tagId']."<br />";
        //}
//         foreach($a as $b)
//         {
//             echo $b['tagId'];
//             print_r($b);
//         }
        //echo Yii::app()->defaultController;
       // print_r(Yii::app()->urlManager);
      // print_r(Yii::app()->createController(Yii::app()->getUrlManager()->parseUrl(Yii::app()->getRequest())));
      //print_r(Yii::app()->Controller);
     // print_r(Yii::app()->controllerMap);
       // $sql="SELECT username,email FROM User";
        //$connection=Yii::app()->db;
        //$dataReader=$connection->createCommand($sql)->query();
        // 使用 $username 变量绑定第一列 (username)
        // $dataReader->bindColumn(1,$username);
        // // 使用 $email 变量绑定第二列 (email)
         //$dataReader->bindColumn(2,$email);
        // while($dataReader->read()!==false)
         //{
        // $username 和 $email 含有当前行中的 username 和 email
         //    print_r($dataReader->read());
         //}
        //print_r(Yii::app()->cache);
//         echo  $route=Yii::app()->getUrlManager()->parseUrl(Yii::app()->getRequest());
//         echo "<br>";
//         while(($pos=strpos($route,'/'))!==false)
//         {
//         	echo $id=substr($route,0,$pos);echo "<br>";
//         	if(!preg_match('/^\w+$/',$id))
//         		return null;
//         	if(!$caseSensitive)
//         		$id=strtolower($id);
//         	$route=(string)substr($route,$pos+1);
//         	if(!isset($basePath))  // first segment
//         	{
//         	if(isset($owner->controllerMap[$id]))
// 				{
// 					return array(
// 						Yii::createComponent($owner->controllerMap[$id],$id,$owner===$this?null:$owner),
// 						Yii::app()->parseActionParams($route),
// 					);
// 				}
//         	}
//         	print_r($pos);echo "<br>";
//         	echo $route;echo "<br>";
        	//print_r(Yii::app()->controllerMap[$id]);
        	//print_r($this->ActionParams);
//          $model=new Post;
//     	$row=$model->find('id=:id',array(':id'=>12));
//     	print_r($row->title);
    	//print_r($model);
        //$this->render('test');
//         }
//     	echo Yii::app()->getBasePath();
//     echo "<br>";
//     echo dirname(__FILE__).DIRECTORY_SEPARATOR.'..';
        // echo $this->Test2;
    	echo $this->setTest2('dd');
    
   

    	
    
    	
        
    } 
    public $Test2=45;  
    
    public function actionTest1()
    {
    	$data=$_POST['data'];
    	
    	print_r(json_encode($data));
    	//exit;
    } 
    public function getTest2()
    {
    	return "test2OK";
    }
    public function setTest2($f)
    {
    	return $f;
    }
    
    
    
    
    
    
	
	public function actionShow()
	{	
		$readFlag=0;
		
		if(isset($_GET['readFlag']))
		{
			$readFlag=$_GET['readFlag'];
		}
		//echo $readFlag;
		$model=$this->loadPostSlug();
		$newComment=$this->newComment($model);
	
		$this->pageTitle=$model->title.(($model->category)?' /'.$model->category->name:'');
		$this->render('show',array(
				'model'=>$model,
				'comments'=>$model->comments,
				'newComment'=>$newComment,
				'readFlag'=>$readFlag,
		));
	
	}
	
	
	public function loadPostSlug($slug=null)
	{
		if($this->_model===null)
		{
			if(isset($id) && $id!==null || isset($_GET['slug']))
				$this->_model=Post::model()->find('slug=:slug',array('slug'=>$slug!==null ?$slug:$_GET['slug']));
			if($this->_model===null)
				throw new CHttpException(404,'The requested page does no exists.');
			//echo $this->_model->getStatusText();
			return $this->_model;
		}
	}
    
    protected function newComment($model)
    {
        $comment=new Comment;
        if(isset($_POST['Comment']))
        {
        	$comment->attributes=$_POST['Comment'];
            if(!Yii::app()->user->isGuest)
            {
                $comment->authorName=Yii::app()->user->username;
                $comment->email=Yii::app()->user->email;
                $comment->authorId=Yii::app()->user->id;
            }
            if(Yii::app()->user->isGuest && Yii::app()->params['commentNeedApproval'])              
                $comment->status=Comment::STATUS_PENDING;                                          
            else
                $comment->status=Comment::STATUS_APPROVED;

            $comment->postId=$model->id;

            if(isset($_POST['previewComment']))
            {
                $comment->validate();
                //print_r($comment);exit;
            }
            else
                if(isset($_POST['submitComment']) && $comment->save())
                {
                    if($comment->status==Comment::STATUS_PENDING)
                    {
                        Yii::app()->user->setFlash('commentSubmittedMessage',Yii::t('lan','Think you for your comment.Your comment will be posted once it is approved.'));
                        $this->refresh();
                    }
                    else
                        $this->redirect(array('show','slug'=>$model->slug,'#'=>'c'.$comment->id));
                }
        }
        return $comment;
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
		echo Yii::app()->user->status;
		echo Yii::app()->user->id;
		$model=new Post;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Post']))
		{
			$model->attributes=$_POST['Post'];
            if(isset($_POST['previewPost']))
                $model->validate();
            elseif(isset($_POST['submitPost']) && $model->save())
            {
                if(Yii::app()->user->status==User::STATUS_VISITOR)
                {
                    Yii::app()->user->setflash('message','Think you for your post. Your post will be posted noce it is approved.');
                    $this->redirect(Yii::app()->homeUrl);
                }
                $this->redirect(array('show','slug'=>$model->slug));
            }
				
		}
        $this->pageTitle=Yii::t('lan','New Post');

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
		$model=$this->loadPost();

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
        $model->content=($model->contentbig)?$model->contentbig:$model->contentshort;
		if(isset($_POST['Post']))
		{
			$model->attributes=$_POST['Post'];
            if(isset($_POST['previewPost']))
                $model->validate();
            else if(isset($_POST['submitPost']) && $model->save())
                $this->redirect(array('show','slug'=>$model->slug));

		}
        $this->pageTitle=Yii::t('lan','Update Post'); 

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
		$dataProvider=new CActiveDataProvider('Post');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}
    public function actionList($readFlag=0)
    {
    	    	
        $criteria=new CDbCriteria;
        $criteria->condition='status='.Post::STATUS_PUBLISHED;
        $criteria->order='createTime DESC';
        if(!empty($_GET['tag']))
        {
            $this->pageTitle=Yii::t('lan','Posts Tagged width').' "'.CHtml::encode($_GET['tag']).'"';
            $widthOption['tagFilter']['params'][':tag']=$_GET['tag'];
//             $modelsCount=Post::model()->with($widthOption)->count($criteria);
            $criteria->condition='tags=:tages';
            $criteria->params=array(':tages'=>$_GET['tag']);
            $modelsCount=Post::model()->count($criteria);
        }
        else
        {
            $this->pageTitle="";
            $modelsCount=Post::model()->count($criteria);
        }
        $pages=new CPagination($modelsCount);
        $pages->pageSize=Yii::app()->params['postsPerPage'];
        $pages->applyLimit($criteria);

//         if(isset($widthOption))
//         {
//             $models=Post::model()->width($widthOption)->findAll($criteria);
//         }
//         else
        {
            $models=Post::model()->findAll($criteria);
        }
        $this->render('list',array(
            'models'=>$models,
            'pages'=>$pages,
            'readFlag'=>$readFlag,
        ));

    }

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{

        $this->processAdminCommand();
        
        $criteria=new CDbcriteria;

        $pages=new CPagination(Post::model()->count($criteria));
        $pages->applyLimit($criteria);

        $sort=new CSort('post');
        $sort->defaultOrder='status ASC,createTime DESC';
        $sort->applyOrder($criteria);
        

        $models=Post::model()->findAll($criteria);
        
        
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        //if(!isset($_GET['ajax']))
        	//$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        $this->pageTitle=Yii::t('lan','Manage Posts');
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
    		$this->loadPost($_POST['id'])->delete();
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
		$model=Post::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='post-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
    	public function loadPost($id=null)
	{
		if($this->_model===null)
		{
			if(isset($id) && $id!==null || isset($_GET['id']))
			$this->_model=Post::model()->findbyPk($id!==null ? $id : $_GET['id']);
			if($this->_model===null || Yii::app()->user->status!=User::STATUS_ADMIN && Yii::app()->user->status!=User::STATUS_WRITER && $this->_model->status!=Post::STATUS_PUBLISHED)
			throw new CHttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}
	
	public function actionAjaxStatus()
	{
 		$model=$this->loadPost();
 		$options=Post::getStatusOptions();
 		$model->status=(count($options)==($model->status+1))?0:($model->status+1);
 		$model->save(false);
 		echo $model->statusText;
		//echo "cccccccccc";
	}
    
    public function actionAjaxBookmark()
    {
        $model=$this->loadPost();
        echo (Bookmark::addDel($model->id))?Yii::t('lan','Delete'):Yii::t('lan','Add');
    }
    
    public function actionSearch()
    {
    	$search=new SiteSearchForm;
    
    	if(isset($_POST['SiteSearchForm']))
    	{
    		$search->attributes=$_POST['SiteSearchForm'];
    		$_GET['searchString']=$search->keyword;
    	}
    	else
    		$search->keyword=$_GET['searchString'];
    
    	/* if($search->validate())
    	 { */
    
    	$criteria=new CDbCriteria;
    	$criteria->condition='status='.Post::STATUS_PUBLISHED;
    	$criteria->order='createTime DESC';
    
    	$criteria->condition.=' AND contentshort LIKE :keyword';
    	$criteria->params=array(':keyword'=>'%'.CHtml::encode($search->keyword).'%');
    
    	$postCount=Post::model()->count($criteria);
    	$pages=new CPagination($postCount);
    	$pages->pageSize=Yii::app()->params['postsPerPage'];
    	$pages->applyLimit($criteria);
    
    	$models=Post::model()->findAll($criteria);
    	//}
    
    	$this->pageTitle=Yii::t('lan','Search Results').' "'.CHtml::encode($_GET['searchString']).'"';
    	$this->render('search',array(
    			'models'=>($models)?$models:array(),
    			'pages'=>$pages,
    			'search'=>$search,
    	));
    }
}
