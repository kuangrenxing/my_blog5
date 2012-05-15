<?php

/**
 * This is the model class for table "Post".
 *
 * The followings are the available columns in table 'Post':
 * @property integer $id
 * @property string $title
 * @property string $titleLink
 * @property string $slug
 * @property string $contentshort
 * @property string $contentbig
 * @property string $tags
 * @property integer $status
 * @property integer $createTime
 * @property integer $updateTime
 * @property integer $commentCount
 * @property integer $categoryId
 * @property integer $authorId
 * @property string $authorName
 * @property string $title2
 * @property string $content2
 */
class Post extends CActiveRecord
{
    const STATUS_DRAFT=0;
    const STATUS_PUBLISHED=1;
    const STATUS_ARCHIVED=2;
    const STATUS_PENDING=3;

    public $content;
    public $text;
    //public $title2;
    //public $content2;

	/**
	 * Returns the static model of the specified AR class.
	 * @return Post the static model class
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
		return 'Post';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, content,tags, title2, content2', 'required'),
            array('titleLink','url'),
			array('status, createTime, updateTime, commentCount, categoryId, authorId', 'numerical', 'integerOnly'=>true),
			array('title, titleLink, title2', 'length', 'max'=>128),
			array('slug', 'length', 'max'=>32),
			array('authorName', 'length', 'max'=>50),
			array('contentbig,titleLink, tags', 'safe'),
            array('status','in','range'=>array(0,1,2,3)),
            array('tags','match','pattern'=>
                   '/^[А-Яа-я\s\w,-]+$/u','message'=>Yii::t('lan','Tags can only contain word characters.')), 
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, titleLink, slug, contentshort, contentbig, tags, status, createTime, updateTime, commentCount, categoryId, authorId, authorName, title2, content2', 'safe', 'on'=>'search'),
		);
	}
    public function safeAttributes()
    {
        return array('title','titleLink','content','status','tags','categoryId','title2','content2');
    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'category'=>array(self::BELONGS_TO,'Category','categoryId'),
            'author'=>array(self::BELONGS_TO,'User','authorId'),
            'comments'=>array(self::HAS_MANY,'Comment','postId','order'=>'comments.createTime'),
            'bookmarks'=>array(self::HAS_MANY,'Bookmark','postId',
                    'condition'=>'bookmarks.userId='.Yii::app()->user->id),
            'tagFilter'=>array(self::MANY_MANY,'Tag','PostTag(postId,tagId)',
                    'together'=>true,
                    'joinType'=>'INNER JOIN',
                    'condition'=>'Tag.name=:tag'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' =>Yii::t('lan','Title'), 
            'title2'=>Yii::t('lan','Title2'),
			'titleLink' => Yii::t('lan','Title Link'), 
			'slug' => 'Slug',
			'contentshort' => 'Contentshort',
            'content'=>Yii::t('lan','Content'),
            'content2'=>Yii::t('lan','Content2'),
			'contentbig' => 'Contentbig',
			'tags' =>Yii::t('lan','Tags'), 
			'status' =>Yii::t('lan','Status'), 
			'createTime' => Yii::t('lan','Create Time'),
			'updateTime' =>Yii::t('lan','Update Time'),
			'commentCount' =>Yii::t('lan','Comment Count'),
			'authorId' => 'Author',
			'authorName' => Yii::t('lan','Author Name'),
            'categoryId'=>Yii::t('lan','Category'),
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
		$criteria->compare('titleLink',$this->titleLink,true);
		$criteria->compare('slug',$this->slug,true);
		$criteria->compare('contentshort',$this->contentshort,true);
		$criteria->compare('contentbig',$this->contentbig,true);
		$criteria->compare('tags',$this->tags,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('createTime',$this->createTime);
		$criteria->compare('updateTime',$this->updateTime);
		$criteria->compare('commentCount',$this->commentCount);
		$criteria->compare('categoryId',$this->categoryId);
		$criteria->compare('authorId',$this->authorId);
		$criteria->compare('authorName',$this->authorName,true);
		$criteria->compare('title2',$this->title2,true);
		$criteria->compare('content2',$this->content2,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function getStatusOptions()
    {
        return array(
            self::STATUS_DRAFT=>Yii::t('lan','Draft'),
            self::STATUS_PUBLISHED=>Yii::t('lan','Published'),
            self::STATUS_ARCHIVED=>Yii::t('lan','Archived'),
            self::STATUS_PENDING=>Yii::t('lan','Pending'),
        		
     
        );
    }
    public function getStatusText()
    {
        //$option=$this->statusOptions;
        $option=$this->statusOptions;
        return $option[$this->status];               
    }


    protected function beforeValidate()
    {
        $this->slug=$this->getSlug('Post',$this->title,($this->isNewRecord)?null:$this->id);
        $content=$this->contentShortBig($this->content);
        $this->contentshort=$content[0];
        if(isset($content[1]))
        {
            $this->contentbig=$content[1];
        }
        if($this->isNewRecord)
        {
            if(Yii::app()->user->status==User::STATUS_VISITOR)
                $this->status=Post::STATUS_PENDING;
                $this->createTime=$this->updateTime=time();
                $this->authorId=Yii::app()->user->id;
                $this->authorName=Yii::app()->user->username;
        }
        else
            $this->updateTime=time();
        return true;
    }

    public function getSlug($class,$slug,$id)
    {
        $slug=trim(preg_replace('/[^a-z0-9a-я-]/','-',mb_strtolower($slug,'UTF-8')));
        if(preg_match('/[а-я]/',$slug))
        {
            $trans=array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'yo','ж'=>'zh',
                'з'=>'z','и'=>'i','й'=>'j','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p',
                'р'=>'r','с'=>'s','т'=>'t','у'=>'t','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh',
                'щ'=>'w','ъ'=>'','ы'=>'i','ь'=>'','э'=>'je','ю'=>'yu','я'=>'ya');
            $slug=strtr($slug,$trans);
        }

        if(strlen($slug)>31)
        $slug=substr($slug,0,30);
        $slug=preg_replace('/-{2,}/','-',$slug);
        if($slug[strlen($slug)-1]=='-')
        $slug=substr($slug,0,strlen($slug)-1);
        if($slug[0]=='-')
        $slug=substr($slug,1,strlen($slug));
        if(!$slug)
        $slug='1';

        $k=1;
        $h=$slug;
        while(count(eval('return '.$class.'::model()->findAll(array(\'condition\'=>\'slug="'.$h.'"'.(($id)?' AND id<>'.$id:'').'\'));')))
        {
            $h=$slug.$k;
            $k++;
        }
        $slug=$h;

        return $slug;
    }
	public function contentShortBig($content)
	{
		$seperator="/<div(.*) id=\"post-more\"(.*)>\r\n\t<span style=\"display: none;\">(.*)<\/span><\/div>/";

		$sbcontent=preg_split($seperator,$content,2,PREG_SPLIT_NO_EMPTY);
		if(isset($sbcontent[1])){
			$sbcontent[1]=preg_replace("/\r|\n|\t|\s/",'',$sbcontent[1]);

			if($sbcontent[1]!='')
			$sbcontent[1]=$content;
		}

		return $sbcontent;
	}

     public function afterSave()
    {
        if(!$this->isNewRecord)
            $this->dbConnection->createCommand('delete from PostTag where postId='.$this->id)->execute();

        if($this->status==self::STATUS_PUBLISHED)
        {
            foreach($this->getTagArray() as $name)
            {
                if(($tag=Tag::model()->findByAttributes(array('name'=>$name)))===null)
                {
                   $tag=new Tag(); 
                   $tag->name=$name;
                   $tag->save();
                }
                //$this->dbConnection->createCommand("INSERT INTO PostTag (postId, tagId) VALUES ({$this->id},{$tag->id})")->execute();
                $this->dbConnection->createCommand("insert into PostTag (postId, tagId) values ({$this->id},{$tag->id})")->execute();
            }
        }
    } 

    protected function afterDelete()
    {
        Comment::model()->deleteAll('postId='.$this->id);
        Bookmark::model()->deleteAll('postId='.$this->id);
        $PostTag=$this->dbConnection->createCommand('select tagId from PostTag where postId ='.$this->id)->queryAll();
        if($PostTag)
           foreach($PostTag as $p)
              {
                 Tag::model()->deleteAll('id='.$p['tagId']); 
             }
        $this->dbConnection->createcommand('Delete from PostTag where postId='.$this->id)->execute();

    }

    public function getTagArray()
    {
        return array_unique(preg_split('/\s*,\s*/',trim($this->tags),-1,PREG_SPLIT_NO_EMPTY));
    }
	
	

    
    public function getTagLinks($model)
    {
        $links=array();
        foreach($model->getTagArray() as $tag)    
            $links[]=CHtml::link($tag,array('list','tag'=>$tag));
        return implode(', ',$links);
    }
    public function findPopularPosts($limit=10)
    {
    	$criteria=array(
    			'condition'=>'t.commentCount<>0 AND t.status='.self::STATUS_PUBLISHED,
    			'order'=>'t.createTime DESC',
    			'order'=>'t.commentCount DESC',
    			'limit'=>$limit,
    	);
    	return $this->findAll($criteria);
    }
}
