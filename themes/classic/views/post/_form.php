<?php
$cs=Yii::app()->clientScript;
$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/thickbox/thickbox.js');
$cs->registerCssFile(Yii::app()->request->baseUrl.'/js/thickbox/thickbox.css');
$cs->registerScriptFile(Yii::app()->request->baseUrl.'/js/ckeditor/ckeditor.js');
?>
<?php if(isset($_POST['previewPost']) && !$model->hasErrors()):?>
<h3><?php echo Yii::t('lan','Preview');?></h3>
<div class="post">
    <div class="title"><?php echo CHtml::encode($model->title); ?></div>    
    <div class="author"><?php echo Yii::t('lan','posted by');?><?php echo Yii::app()->user->username.' '.Yii::t('lan','on').' '.Yii::t('lan',date('F',$model->createTime)).date(' j, Y',$model->createTime);?></div>
    <div class="content" id="previshort"><?php echo $model->contentshort;?></div>
    <div class="content" id="prevbig" style="display:none"><?php echo $model->contentbig;?></div>
    <div class="nav">
    <?php if($model->categoryId):?>
        <b><?php echo Yii::t('lan','Category');?></b>
        <?php echo CHtml::encode(Category::model()->findByPK($model->categoryId)->name);?>
    <?php endif;?>
<b><?php echo Yii::t('lan','Tags');?>:</b>
<?php echo $model->tags;?>
<br/>
<?php if($model->contentbig):?>
    <?php echo CHtml::link(Yii::t('lan','Read more'),'#preview-post',array('id'=>'prevread'));?>
<?php endif;?>
<?php echo Yii::t('lan','Last updated on');?><?php echo Yii::t('lan',date('F',$model->updateTime)).date(' j, Y',$model->updateTime);?>

</div>
</div>
<?php endif;?>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'post-form',
	'enableAjaxValidation'=>true,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'title_en'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>128,'id'=>'title')); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'title_cn'); ?>
		<?php echo $form->textField($model,'title2',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'title2'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'titleLink'); ?>
		<?php echo $form->textField($model,'titleLink',array('size'=>60,'maxlength'=>128)); ?>
		<?php echo $form->error($model,'titleLink'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'categoryId'); ?>
        <?php echo $form->dropDownList($model,'categoryId',
        CHtml::listData(Category::model()->findAll(array('select'=>'id,name')),'id','name'),array('prompt'=>'请选择')); ?>
		<?php echo $form->error($model,'categoryId'); ?>
	</div>


	<div class="row">
		<?php echo $form->labelEx($model,'content_en'); ?>
		<?php echo $form->textArea($model,'content',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'content'); ?>
	</div>


	<div class="row">
		<?php echo $form->labelEx($model,'content_cn'); ?>
		<?php echo $form->textArea($model,'content2',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'content_2'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tags'); ?>
		<?php echo $form->textField($model,'tags',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'tags'); ?>
	</div>

<?php if(Yii::app()->user->status==User::STATUS_ADMIN || Yii::app()->user->status==User::STATUS_WRITER):?>
	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->DropDownList($model,'status',Post::model()->statusOptions); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>
<?php endif;?>

	<div class="row buttons">
		<?php echo CHtml::submitButton($update?Yii::t('lan','Save'):Yii::t('lan','Create'),array('name'=>'submitPost')); ?>
        <?php echo CHtml::submitButton(Yii::t('lan','Preview'),array('name'=>'previewPost')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<script type="text/javascript">
    var bs=false;
$(document).ready(function(){
    $("preview").click(function(){
        if(bs==true)
        {
            $(this).html("<?php echo Yii::t('lan','Read more');?>");
            $("#prevbig").attr('style','display:none');
            $("#prevshort").attr('style','display:');
        }
        else
        {
            $(this).html("<?php echo Yii::t('lan','Read less');?>");
            $("#prevshort").attr('style','display:none');
            $("#prevbig").attr('style','display:');
        }
        bs=!bs;
    });
});
</script>
<script type="text/javascript">
CKEDITOR.config.resize_minWidth=570;
CKEDITOR.config.language='<?php Yii::t('lan','en');?>';
var insertimageorfile="<?php echo $this->createUrl('filem/admin',array('TB_iframe'=>true,'height'=>350));?>";
<?php if(Yii::app()->user->status==User::STATUS_ADMIN || Yii::app()->user->status==User::STATUS_WRITER):?>
CKEDITOR.config.toolbar=
    [
        ['TagMore'],['Maximize'],['Source'],['Bold','Italic','Underline','Strike'],
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
<?php else: ?>
CKEDITOR.config.toolbar=
    [
        ['TagMore'],['Maximize'],['Source'],['Bold','Italic','Underline','Strike'],
        ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
        ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
        ['Link','Unlink'],
        '/',
        ['PasteText','PasteFromWord'],['Undo','Redo'],
        ['Format'],
        ['TextColor','BGColor'],
        ['Image','Flash','Table','SpecialChar']
    ];
<?php endif; ?>
CKEDITOR.replace('Post[content]',{skin:'kama'});
CKEDITOR.replace('Post[content2]');
</script>
