<h2><?php echo Yii::t('lan','Users List');?>
<?php
if(Yii::app()->user->status==User::STATUS_ADMIN) 
    echo CHtml::link(Yii::t('lan','New User'),array('user/create'));
 ?></h2>
<?php $this->renderPartial('_list',array(
    'models'=>$models,
));
?>
<br>
<?php $this->widget('CLinkPager',array(
    'pages'=>$pages,
));?>
