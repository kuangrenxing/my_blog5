<?php
/*
 * $this->breadcrumbs=array(
	'Posts'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);
$this->menu=array(
	array('label'=>'List Post', 'url'=>array('index')),
	array('label'=>'Create Post', 'url'=>array('create')),
	array('label'=>'View Post', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Post', 'url'=>array('admin')),
);

 */
?>

<h2 id="preview-post"><?php echo Yii::t('lan','Update Post');?> 
<?php echo CHtml::link('#'.$model->id,array('post/show','slug'=>$model->slug));?>
<?php echo CHtml::link(Yii::t('lan','Manage Posts'),array('admin'));?>
</h2>
<?php echo $this->renderPartial('_form', array('model'=>$model,'update'=>'true')); ?>
