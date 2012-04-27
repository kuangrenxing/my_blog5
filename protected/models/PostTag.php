<?php

/**
 * This is the model class for table "PostTag".
 *
 * The followings are the available columns in table 'PostTag':
 * @property integer $postId
 * @property integer $tagId
 */
class PostTag extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return PostTag the static model class
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
		return 'PostTag';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('postId, tagId', 'required'),
			array('postId, tagId', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('postId, tagId', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'postId' => 'Post',
			'tagId' => 'Tag',
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

		$criteria->compare('postId',$this->postId);
		$criteria->compare('tagId',$this->tagId);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}