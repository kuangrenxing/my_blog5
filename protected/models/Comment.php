<?php

/**
 * This is the model class for table "Comment".
 *
 * The followings are the available columns in table 'Comment':
 * @property integer $id
 * @property string $content
 * @property string $contentDisplay
 * @property integer $status
 * @property integer $createTime
 * @property string $authorName
 * @property string $email
 * @property integer $postId
 * @property integer $authorId
 */
class Comment extends CActiveRecord
{
    
    const STATUS_PENDING=0;
    const STATUS_APPROVED=1;

      public $verifyCode;
	/**
	 * Returns the static model of the specified AR class.
	 * @return Comment the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'Comment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('content, status, authorName, email, postId', 'required'),
			array('status, createTime, postId, authorId', 'numerical', 'integerOnly'=>true),
			array('authorName', 'length', 'max'=>50),
			array('email', 'length', 'max'=>64),
			array('contentDisplay', 'safe'),
             array('verifyCode', 'captcha', 'on'=>'insert', 
                'allowEmpty'=>!Yii::app()->user->isGuest || !extension_loaded('gd')),
        
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, content, contentDisplay, status, createTime, authorName, email, postId, authorId', 'safe', 'on'=>'search'),
		);
	}

    public function safeAttributes()
    {
        return array('authorName','email','content','verifyCode','status');
    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'post'=>array(self::BELONGS_TO, 'Post', 'postId', 'joinType'=>'INNER JOIN'),
            'author'=>array(self::BELONGS_TO,'User','authorId', 'joinType'=>'INNER JOIN'),
        );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'content' => Yii::t('lan','Content'),
            'authorName'=>Yii::t('lan','Username'),
			'status' => 'Status',
			'createTime' => 'Create Time',
			'email' => 'Email',
			'postId' => 'Post',
			'authorId' => 'Author',
            'verifyCode'=>Yii::t('lan','Verification Code'), 
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('contentDisplay',$this->contentDisplay,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('createTime',$this->createTime);
		$criteria->compare('authorName',$this->authorName,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('postId',$this->postId);
		$criteria->compare('authorId',$this->authorId);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    public function beforeValidate()
    {
        $parser=new CMarkdownParser;
        $this->contentDisplay=$parser->safeTransform($this->content);
        if($this->isNewRecord)
        {
            $this->createTime=time();
        }
        return true; 
    }

    protected function afterSave()
    {
        if($this->isNewRecord && $this->status==Comment::STATUS_APPROVED)
            Post::model()->updateCounters(array('commentCount'=>1),"id={$this->postId}");
    }

    protected function afterDelete()
    {
        if($this->status==Comment::STATUS_APPROVED)
            Post::model()->updateCounters(array('commentCount'=>-1,"id={$this->postId}"));
    }

    public function approve()
    {
        if($this->status==Comment::STATUS_PENDING)
        {
            $this->status=Comment::STATUS_APPROVED;
            $this->save();
            Post::model()->updateCounters(array('commentCount'=>1,"id={$this->postId}"));
        }
    }

    public function getPendingCommentCount()
    {
        return Comment::model()->count('status='.self::STATUS_PENDING);
    }
    
    
    public function findRecentComments($limit=10)
    {
    	$criteria=array(
    			'condition'=>'t.status='.self::STATUS_APPROVED,
    			'order'=>'t.createTime DESC',
    			'limit'=>$limit,
    	);
    	return $this->with('post')->findAll($criteria);
    }
    public function getAuthorLink()
    {
    	if(!empty($this->author))
    		return CHtml::link($this->author->username,array('user/show','id'=>$this->author->id));
    	else
    		return $this->authorName;
    }

}
