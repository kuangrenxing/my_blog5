<?php

/**
 * This is the model class for table "User".
 *
 * The followings are the available columns in table 'User':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $url
 * @property integer $status
 * @property integer $banned
 * @property string $avatar
 * @property string $passwordLost
 * @property string $confirmRegistration
 * @property string $about
 */
class User extends CActiveRecord
{
    const STATUS_ADMIN=0;
    const STATUS_WRITER=1;
    const STATUS_VISITOR=2;
    const STATUS_GUEST=3;

    const BANNED_NO=0;
    const BANNED_YES=1;
    
    //public $verifyCode;
    public $verifyCode;
    
    public $password_repeat;
    public $usernameoremail;
	/**
	  Returns the static model of the specified AR class.
	 * @return User the static model class
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
		return 'User';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('username','length','max'=>50),
            array('password','length','max'=>32),
            array('email','length','max'=>64),
            array('url','length','max'=>64),
            array('avatar','file','types'=>'gif, png, jpg, jpeg','maxSize'=>5242880, 'allowEmpty'=>true),
            array('passwordLost','length','max'=>32),
            array('username, email, status, banned','required','on'=>'insert, update, registration'),
            array('password','required','on'=>'insert, registration'),
            array('password','compare','on'=>'registration'),
            array('username','unique','on'=>'insert, registration, update'),
            array('email','unique','on'=>'insert, registration, update'),
            array('email','email'),
            array('url','url'),
            array('status','in','range'=>array(0,1,2)),
            array('banned','in','range'=>array(0,1)),
            array('username','match','pattern'=>'/^[\w\s._-]{3,50}$/','message'=>Yii::t('lan','Wrong or small username.')),
            array('password','match','pattern'=>'/^[\w\s]{3,32}$/','message'=>Yii::t('lan','Wrong or small password.')),
            array('verifyCode','captcha','on'=>'registration, lostpass','allowEmpty'=>!extension_loaded('gd')),
            array('usernameoremail','required','on'=>'lostpass'),
			array('password_repeat','safe'),
        );
	}
	public function safeAttributes()
	{
		return array('username','password','status', 'banned','about','email',
				'url','password_repeat','verifyCode','usernameoremail');
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'posts'=>array(self::HAS_MANY,'Post','authorId','order'=>'posts.createTime'),
            'bookmarks'=>array(self::HAS_MANY,'Bookmark','userId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		/* return array(
			'id' => 'ID',
			'username' => 'Username',
			'password' => 'Password',
			'email' => 'Email',
			'url' => 'Url',
			'status' => 'Status',
			'banned' => 'Banned',
			'avatar' => 'Avatar',
			'passwordLost' => 'Password Lost',
			'confirmRegistration' => 'Confirm Registration',
			'about' => 'About',
		); */
		return array(
				'username'=>Yii::t('lan','Username'),
				'password'=>Yii::t('lan','Password'),
				'email'=>'Email',
				'url'=>'Url',
				'status'=>Yii::t('lan','Status'),
				'banned'=>Yii::t('lan','Banned'),
				'avatar'=>Yii::t('lan','Avatar'),
				'about'=>Yii::t('lan','About'),
				'password_repeat'=>Yii::t('lan','Password again'),
				'usernameoremail'=>Yii::t('lan','Username or Email'),
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
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('banned',$this->banned);
		$criteria->compare('avatar',$this->avatar,true);
		$criteria->compare('passwordLost',$this->passwordLost,true);
		$criteria->compare('confirmRegistration',$this->confirmRegistration,true);
		$criteria->compare('about',$this->about,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	protected function afterSave()
	{
		if(!$this->isNewRecord && $this->id==Yii::app()->user->id)
		{
			Yii::app()->user->username=$this->username;
			if($this->password)
				Yii::app()->user->password=$this->password;
		}
	}
	
	public function afterValidate()
	{
		if($this->usernameoremail)
		{
			$conditions='username=:usernameoremail OR email=:usernameoremail';
			$params=array('usernameoremail'=>$this->usernameoremail);
			$user=$this->find($conditions,$params);
			if($user===null)
			{
				$this->addError('usernameoremail',Yii::t('lan','Username or Email is incorrect.'));
				//echo "1111111111";
				return false;
			}
			else if($user->banned==User::BANNED_YES)
			{
				$this->addError('usernameoremail',Yii::t('lan','User is banned.'));
				//echo "22222222222";
				return false;
			}
			else if($user->confirmRegistration)
			{
				$this->addError('usernameoremail',Yii::t('lan','Confirm user email.'));
				//echo "3333333333333";
				return false;
			}
		}
		return true;
	}
	
    public function getRIN()
    {
    	$rand = null;
        $chars='aeuybdghjlmnpqrstvwxz123456789';
        for($i=0,$pass='';$i<10;$i++)
            $rand.=$chars{mt_rand(0,29)};
        return $rand;
    }
    public function getStatusText()
    {
        $options=$this->StatusOptions;
        return $options[$this->status];
    }
    public function getStatusOptions()
    {
        return array(
            self::STATUS_ADMIN=>Yii::t('lan','Admin'),
            self::STATUS_WRITER=>Yii::t('lan','Writer'),
            self::STATUS_VISITOR=>Yii::t('lan','Visitor'),
        );
    }
    public function getBannedOptions()
    {
        return array(
            self::BANNED_NO=>Yii::t('lan','No'),
            self::BANNED_YES=>Yii::t('lan','Yes'),
        );
    }

    public function getBannedText()
    {
        $options=$this->bannedOptions;
        return $options[$this->banned];
    }
}
