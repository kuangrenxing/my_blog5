<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'post-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'titleLink'); ?>
		<?php echo $form->textField($model,'titleLink',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'titleLink'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'slug'); ?>
		<?php echo $form->textField($model,'slug',array('size'=>32,'maxlength'=>32)); ?>
		<?php echo $form->error($model,'slug'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'contentshort'); ?>
		<?php echo $form->textArea($model,'contentshort',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'contentshort'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'contentbig'); ?>
		<?php echo $form->textArea($model,'contentbig',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'contentbig'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tags'); ?>
		<?php echo $form->textArea($model,'tags',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'tags'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->textField($model,'status'); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'createTime'); ?>
		<?php echo $form->textField($model,'createTime'); ?>
		<?php echo $form->error($model,'createTime'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'updateTime'); ?>
		<?php echo $form->textField($model,'updateTime'); ?>
		<?php echo $form->error($model,'updateTime'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'commentCount'); ?>
		<?php echo $form->textField($model,'commentCount'); ?>
		<?php echo $form->error($model,'commentCount'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'categoryId'); ?>
		<?php echo $form->textField($model,'categoryId'); ?>
		<?php echo $form->error($model,'categoryId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'authorId'); ?>
		<?php echo $form->textField($model,'authorId'); ?>
		<?php echo $form->error($model,'authorId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'authorName'); ?>
		<?php echo $form->textField($model,'authorName',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'authorName'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'title2'); ?>
		<?php echo $form->textField($model,'title2',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'title2'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'content2'); ?>
		<?php echo $form->textArea($model,'content2',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'content2'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->