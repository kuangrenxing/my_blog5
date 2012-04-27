

<?php if(Yii::app()->user->hasflash('message')):?>
<?php echo Yii::app()->user->getflash('message'); ?>
<?php endif;?>

<?php if(!empty($_GET['tag'])):?>
<h3>
    <?php echo Yii::t('lan','Posts Tagged with');?>
"<?php echo CHtml::encode($_GET['tag']);?>"
</h3>
<?php endif;?>
<?php foreach($models as $model):?>
<?php $this->renderPartial('_post',array('model'=>$model)); ?>
<?php endforeach;?>
<br/>
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>
