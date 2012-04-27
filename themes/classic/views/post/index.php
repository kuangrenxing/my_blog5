<?php
$this->breadcrumbs=array(
	'Posts',
);

$this->menu=array(
	array('label'=>'Create Post', 'url'=>array('create')),
	array('label'=>'Manage Post', 'url'=>array('admin')),
);
?>

<?php if(Yii::app()->user->hasFlash('message')): ?>
<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('message'); ?>
</div>
<?php endif; ?>

<h1>Posts</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
