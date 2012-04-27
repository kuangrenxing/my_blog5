<?php 
$cs=Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/thickbox/thickbox.js');
$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/ckeditor/ckeditor.js');
$cs->registerCSSFile(Yii::app()->request->baseUrl.'/js/thickbox/thickbox.css');
?>
<?php if(isset($_POST['previewPage']) && !$model->hasErrors()):?>
<h3><?php echo Yii::t('lan','Preview');?></h3> 
<div class="post">
<div class="title"><?php echo CHtml::encode($model->title);?></div>
<div class="author"><?php echo Yii::t('lan','posted by');?>
<?php
echo Yii::app()->user->username.' '.Yii::t('lan','on').' '.
    Yii::t('lan',date('F',$model->createTime)).date(' j, Y',$model->createTime);
?>
</div>
<div class="content"><?php echo $model->content;?></div>
<div class="nav">
    <?php echo Yii::t('lan','Last updated on');?>
    <?php echo Yii::t('lan',date('F',$model->updateTime)).date(' j,Y',$model->updateTime);?>
</div>
</div>
<?php endif;?>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'page-form',
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
		<?php echo $form->labelEx($model,'content'); ?>
		<?php echo $form->textArea($model,'content',array('rows'=>6, 'cols'=>50,'class'=>'')); ?>
		<?php echo $form->error($model,'content'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->dropDownList($model,'status',array('0'=>'Draft','1'=>'Published')); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row buttons">
        <?php echo CHtml::submitButton($update? Yii::t('lan','Save'):Yii::t('lan','Create'),array('name'=>'submitPage'));?>
		<?php echo CHtml::submitButton(Yii::t('lan','Preview'),array('name'=>'previewPage')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<script type="text/javascript">
/*<![CDATA[*/

CKEDITOR.config.resize_minWidth = 570;
CKEDITOR.config.language = '<?php echo Yii::t('lan','en'); ?>';
var insertimageorfile="<?php echo $this->createUrl('filem/admin', array('TB_iframe'=>true,'height'=>350)); ?>"

CKEDITOR.config.toolbar=
    [
        ['Maximize'],['Source'],['Bold','Italic','Underline','Strike'],
        ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
        ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
        ['Link','Unlink'],
        '/',
        ['PasteText','PasteFromWord'],['Undo','Redo'],
        ['Format'],
        ['TextColor','BGColor'],
        ['Image','Flash','Table','SpecialChar'],
        ['InsertImageOrFile']
    ];

editor = CKEDITOR.replace('Page[content]');
/*]]>*/
</script>
