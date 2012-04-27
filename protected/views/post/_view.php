<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title')); ?>:</b>
	<?php echo CHtml::encode($data->title); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('titleLink')); ?>:</b>
	<?php echo CHtml::encode($data->titleLink); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('slug')); ?>:</b>
	<?php echo CHtml::encode($data->slug); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('contentshort')); ?>:</b>
	<?php echo CHtml::encode($data->contentshort); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('contentbig')); ?>:</b>
	<?php echo CHtml::encode($data->contentbig); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tags')); ?>:</b>
	<?php echo CHtml::encode($data->tags); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('createTime')); ?>:</b>
	<?php echo CHtml::encode($data->createTime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('updateTime')); ?>:</b>
	<?php echo CHtml::encode($data->updateTime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('commentCount')); ?>:</b>
	<?php echo CHtml::encode($data->commentCount); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('categoryId')); ?>:</b>
	<?php echo CHtml::encode($data->categoryId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('authorId')); ?>:</b>
	<?php echo CHtml::encode($data->authorId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('authorName')); ?>:</b>
	<?php echo CHtml::encode($data->authorName); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('title2')); ?>:</b>
	<?php echo CHtml::encode($data->title2); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('content2')); ?>:</b>
	<?php echo CHtml::encode($data->content2); ?>
	<br />

	*/ ?>

</div>