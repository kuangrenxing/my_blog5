<?php

/**
 * This is the model class for table "Page".
 *
 * The followings are the available columns in table 'Page':
 * @property integer $id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property integer $status
 * @property integer $createTime
 * @property integer $updateTime
 * @property integer $authorId
 * @property string $authorName
 */
class Page extends CActiveRecord
{
    const STATUS_DRAFT=0;
    const STATUS_PUBLISHED=1;
	/**
	 * Returns the static model of the specified AR class.
	 * @return Page the static model class
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
		return 'Page';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title,  content, status', 'required'),
			array('status, createTime, updateTime, authorId', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>128),
			array('slug', 'length', 'max'=>32),
			array('authorName', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, slug, content, status, createTime, updateTime, authorId, authorName', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
				'author'=>array(self::BELONGS_TO,'User','authorId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Title',
			'slug' => 'Slug',
			'content' => 'Content',
			'status' => 'Status',
			'createTime' => 'Create Time',
			'updateTime' => 'Update Time',
			'authorId' => 'Author',
			'authorName' => 'Author Name',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('slug',$this->slug,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('createTime',$this->createTime);
		$criteria->compare('updateTime',$this->updateTime);
		$criteria->compare('authorId',$this->authorId);
		$criteria->compare('authorName',$this->authorName,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    protected function beforeValidate()
    {
        $this->slug=Post::getSlug('Page',$this->title,($this->isNewRecord)?null:$this->id);
        if($this->isNewRecord)
        {
            $this->createTime=$this->updateTime=time();
            $this->authorId=Yii::app()->user->id;
            $this->authorName=Yii::app()->user->username;
        }
        else
        {
            $this->updateTime=time();
        }
        return true;
    }
    public function getstatusOptions()
    {
        return array(
            self::STATUS_DRAFT=>Yii::t('lan','Draft'),
            self::STATUS_PUBLISHED=>Yii::t('lan','Published'),
        );
    }

    public function getStatusText()
    {
        $options=$this->statusOptions;
        return $options[$this->status];
    }
}
