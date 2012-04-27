<?php Yii::app()->clientScript->registerCSSFile(Yii::app()->baseUrl.'/js/highslide/highslide.css');?>
<div class="form">
<?php
$imagetype=array('jpg','JPG','png','PNG','jpeg','JPEG');
$type=explode('/',$model->type);
//if($type[0]=='jpg')
if(in_array($type[0],$imagetype))
{
	$whtext=File::getHOW(Yii::app()->params['filePath'].$file);
	$url=Yii::app()->baseUrl.'/uploads/file/'.$file;
}
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'file-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

<?php echo ($type[0]=='jpg') ? (($whtext)?
    CHtml::link(CHtml::image(
	    $url,
	    $model->alt, 
    	array($whtext=>Yii::app()->params['imageThumbnailBoundingbox'],'class'=>'pic'
    			)),
	    $url,
	   	array('class'=>'highslide','onclick'=>'return hs.expand(this)')
    ):
    CHtml::image($url,$model->alt,array('class'=>'pic'))
	):
    CHtml::image(Yii::app()->theme->baseUrl.'/images/file.png','',array('class'=>'pic'));?>

<div class="highslide-caption">
   <?php echo $model->alt;?>
</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>64)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>


	<div class="row">
		<?php echo $form->labelEx($model,'说明'); ?>
		<?php echo $form->textField($model,'alt',array('size'=>32,'maxlength'=>32)); ?>
		<?php echo $form->error($model,'alt'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<script type="text/javascript">
	hs.graphicsDir = '/js/highslide/graphics/';
	hs.wrapperClassName = 'rounded-white';
</script>
<style>
/* .pic{
	width:300px;
	height:300px;
} */
</style>
