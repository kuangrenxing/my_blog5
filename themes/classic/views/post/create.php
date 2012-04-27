<?php
/* $this->breadcrumbs=array(
	'Posts'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Post', 'url'=>array('index')),
	array('label'=>'Manage Post', 'url'=>array('admin')),
); */
?>
<h2 id="preview-post"><?php echo Yii::t('lan','New Post');?></h2>
<?php 
if(Yii::app()->user->status==User::STATUS_ADMIN || Yii::app()->user->status==User::STATUS_WRITER)
    echo CHtml::link(Yii::t('lan','Manage Posts'),array('admin'));
?>
<?php echo $this->renderPartial('_form', array('model'=>$model,'update'=>false)); ?>
