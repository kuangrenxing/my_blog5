<?php
/*
$this->breadcrumbs=array(
	'Posts'=>array('index'),
	'Manage',
);
$this->menu=array(
	array('label'=>'List Post', 'url'=>array('index')),
	array('label'=>'Create Post', 'url'=>array('create')),
);

 */
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('post-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
    <h2><?php echo Yii::t('lan','Manage Posts');?><?php echo CHtml::link(Yii::t('lan','New Post'),array('create'));?></h2>
<table class="dataGrid">
    <tr>
    <th><?php echo $sort->link('id');?></th>
    <th><?php echo $sort->link('status');?></th>
    <th><?php echo $sort->link('categoryId');?></th>
    <th><?php echo $sort->link('title');?></th>
    <th><?php echo $sort->link('author');?></th>
    <th><?php echo $sort->link('createTime');?></th>
    <th><?php echo $sort->link('updateTime');?></th>
    <th><?php echo $sort->link('commentCount');?></th>
    <th><?php echo Yii::t('lan','Actions');?></th>
    </tr>
<?php foreach($models as $n=>$model):?>
<tr class=<?php echo $n%2?'even':'odd'?>>
	<td><?php echo $model->id;?></td>
  <td>
                <?php echo CHtml::ajaxLink($model->statusText,
                    $this->createUrl('post/ajaxStatus',array('id'=>$model->id)),
                    array('success'=>'function(msg){ pThis.html(msg); }'),
                    array('onclick'=>'var pThis=$(this);')); ?>
            </td>
    <td>
        <?php if($model->category):?>
            <?php echo CHtml::link(CHtml::encode($model->category->name),array('category/show','slug'=>$model->category->slug));?>
        <?php endif;?>
    </td>
    <td><?php echo CHtml::link(CHtml::encode($model->title),array('show','slug'=>$model->slug));?></td>
    <td><?php echo (($model->author->username)?CHtml::link($model->author->username,array('user/show','id'=>$model->author->id)):$model->authorName); ?></td>
    <td><?php echo Yii::t('lan',date('Y-m-d H:i',$model->createTime));?></td>
    <td><?php echo Yii::t('lan',date('Y-m-d H:i',$model->updateTime));?></td>
    <td><?php echo $model->commentCount;?></td>

    <td>
<?php echo CHtml::link(Yii::t('lan','Update'),array('update','id'=>$model->id));?> 
<?php echo CHtml::linkButton(Yii::t('lan','Delete'),array(
                    'submit'=>'',
		           
                    'params'=>array('command'=>'delete','id'=>$model->id),
                    'confirm'=>Yii::t('lan','Are you sure to delete')." {$model->title} ?")); ?>
    </td>
</td>



</tr>
<?php endforeach;?>
</table>



