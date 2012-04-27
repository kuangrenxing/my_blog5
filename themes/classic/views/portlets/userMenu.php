<ul>
<?php if(Yii::app()->user->status==User::STATUS_ADMIN || Yii::app()->user->status==User::STATUS_WRITER):?>

<li><?php echo CHtml::link(Yii::t('lan','Approve Comments'),array('comment/list')).' ('.Comment::model()->pendingCommentCount.')';?></li>
<li><?php echo CHtml::link(Yii::t('lan','User List'),array('user/list')); ?></li>
<li><?php echo CHtml::link(Yii::t('lan','Manage Posts'),array('post/admin')); ?></li>
<li><?php echo CHtml::link(Yii::t('lan','Manage Categories'),array('category/admin'));?></li>
<li><?php echo CHtml::link(Yii::t('lan','Manage Files'),array('file/admin'));?></li>
<li><?php echo CHtml::link(Yii::t('lan','Manage Pages'),array('page/admin'));?></li>
<?php endif;?>
<li><?php echo CHtml::link(Yii::t('lan','Create New Post'),array('post/create'));?></li>
<li><?php echo CHtml::link(Yii::t('lan','My bookmarks'),array('user/bookmarks')); ?></li>
<li><?php echo CHtml::link(Yii::t('lan','My profile'),array('user/update','id'=>Yii::app()->user->id)); ?></li>
<li><?php echo CHtml::linkButton(Yii::t('lan','logout'),array( 
        'submit'=>'',
        'params'=>array('command'=>'logout'),
)); ?></li>
</ul>
