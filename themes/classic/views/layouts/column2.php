<?php $this->beginContent('//layouts/main'); ?>
<div class="container">
	<div class="span-19">
		<div id="content">
			<?php echo $content; ?>
		</div><!-- content -->
	</div>
	<div class="span-5 last">
		<div id="sidebar">

<?php $this->widget('UserLogin',array('visible'=>Yii::app()->user->isGuest)); ?>
<?php $this->widget('UserMenu',array('visible'=>!Yii::app()->user->isGuest)); ?>
<?php $this->widget('SiteSearch');?>
<?php $this->widget('TagCloud');?>
<?php $this->widget('Links');?>
<?php $this->widget('RecentComments');?>
<?Php $this->widget('PopularPosts');?>
<?php $this->widget('Categories');?>

		<?php
			/* $this->beginWidget('zii.widgets.CPortlet', array(
				'title'=>'Operations',
			));
			$this->widget('zii.widgets.CMenu', array(
				'items'=>$this->menu,
				'htmlOptions'=>array('class'=>'operations'),
			));
			$this->endWidget(); */
		?>
		</div><!-- sidebar -->
	</div>
</div>
<?php $this->endContent(); ?>
