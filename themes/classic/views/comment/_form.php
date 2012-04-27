<?php if(Yii::app()->user->hasFlash('commentSubmittedMessage')): ?>
    <div class="form">
        <?php echo Yii::app()->user->getFlash('commentSubmittedMessage'); ?>
    </div><br />
<?php endif; ?>

<?php if(isset($_POST['previewComment']) && !$model->hasErrors()):?>
    <h3><?php echo Yii::t('lan','Preview');?></h3>


<div  class="comment">
    <div class="avatar">
    <img src="<?php echo Yii::app()->baseUrl.'/uploads/avatar/'.(isset(User::model()->findByPK($model->authorId)->avatar)?
    		User::model()->findByPK($model->authorId)->avatar:Yii::app()->params['noAvatar'])?>"
    alt="<?php echo (isset(User::model()->findByPK($model->authorId)->username)?User::model()->findByPK($model->authorId)->username:
    	$model->authorName);?>" 
    title="<?php echo (isset(User::model()->findByPK($model->authorId)->username)?
        User::model()->findByPK($model->authorId)->username:$model->authorName);?>" />
    </div>
<div class="info">
    <div class="author">
    	<?php echo (isset(User::model()->findByPK($model->authorId)->username)?
        User::Model()->findByPK($model->authorId)->username:$model->authorName);?>
    </div>
    <div class="time">
        <?php echo Yii::t('lan',date('F',$model->createTime)).date(' j,Y H:i ',$model->createTime);?> |
        <?php echo (isset(User::model()->findByPK($model->authorId)->email)?
                User::model()->findByPK($model->authorId)->email:$model->email);?>
    </div>
        <div class="content"><?php echo $model->contentDisplay;?></div>
</div>
</div>
<?php endif;?>



<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'comment-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

<?php if(Yii::app()->user->isGuest): ?>
    <div class="row">
        <?php echo CHtml::activeLabel($model,'authorName'); ?>
        <?php echo CHtml::activeTextField($model,'authorName',array('size'=>50,'maxlength'=>50)); ?>
    </div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>64)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>
<?php endif; ?>

	<div class="row">
		<?php echo $form->labelEx($model,'content'); ?>
		<?php echo $form->textArea($model,'content',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'content'); ?>
	</div>

<?php 
if(Yii::app()->user->isGuest && extension_loaded('gd')):
?>
<div class="row">
   <?php echo $form->labelEx($model,'verifyCode'); ?>
		<div>
		<?php $this->widget('CCaptcha'); ?>
		<?php echo $form->textField($model,'verifyCode'); ?>
		</div>
</div>
<?php endif;?>

	<div class="row buttons">
        <?php echo CHtml::submitButton($update?Yii::t('lan','Save'):Yii::t('lan','Create'),array('name'=>'submitComment'));?>
        <?php echo CHtml::submitButton(Yii::t('lan','Preview'),array('name'=>'previewComment'));?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<?php 
$cs=Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/ckeditor/sample.js');
$cs->registerScriptFile(Yii::app()->baseUrl.'/js/ckeditor/ckeditor.js');
//$cs->registerScriptFile(Yii::app()->baseUrl.'/js/ckeditor/sample.css');


?>
<script>
CKEDITOR.replace( 'Comment[content]' );
</script>
