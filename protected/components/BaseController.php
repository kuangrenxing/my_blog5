<?php 
class BaseController extends CController
{
    function init()
    {
    	
        if(!Yii::app()->user->isGuest)
        {
        	
        	//echo Yii::app()->user->username.'||'.Yii::app()->user->password;
        	//echo Yii::app()->user->status;
        	$identity=new UserIdentity(Yii::app()->user->username,Yii::app()->user->password);
            $identity->authenticate(false);
            if($identity->errorCode!=0)
            {
                Yii::app()->user->logout();
                Yii::app()->user->setState('status',User::STATUS_GUEST);
                $this->redirect(Yii::app()->homeUrl);
            }
        }
        else
            Yii::app()->user->setState('status',User::STATUS_GUEST);
    }
}
?>
