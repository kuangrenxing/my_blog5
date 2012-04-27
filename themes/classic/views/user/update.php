<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List User', 'url'=>array('index')),
	array('label'=>'Create User', 'url'=>array('create')),
	array('label'=>'View User', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage User', 'url'=>array('admin')),
);
?>

<h2><?php 
if($model->id==Yii::app()->user->id)
    echo Yii::t('lan','My profile');
else 
    echo Yii::t('lan','Update User');
?>
<?php echo CHtml::link('#'.$model->id,array('user/show','id'=>$model->id)); ?>
<?php echo CHtml::link(Yii::t('lan','Users List'),array('list'));?>
</h2>
<?php echo $this->renderPartial('_form', array('model'=>$model,'update'=>true)); ?>
