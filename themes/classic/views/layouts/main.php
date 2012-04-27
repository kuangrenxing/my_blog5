<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
    <meta name="description" content="<?php  echo CHtml::encode(Yii::app()->params['description']);?>"/>
    <meta name="keywords" content="<?php  echo CHtml::encode(Yii::app()->params['keywords']);?>"/>
	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php //echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

    <title>
<?php echo (($this->pageTitle)? $this->pageTitle.'_' : ''); ?>
<?php echo Yii::app()->params['title']; ?></title>
<?php //echo CHtml::cssFile(Yii::app()->theme->baseUrl.'/css/style.css'); ?>
<?php //echo CHtml::cssFile(Yii::app()->baseUrl.'/js/highslide/highslide.css'); ?>
<?php Yii::app()->clientScript->registerLinkTag('alternate','application/rss+xml',$this->createUrl('site/postFeed')); ?>
<?php Yii::app()->clientScript->registerLinkTag('alternate','application/rss+xml',$this->createUrl('site/commentFeed')); ?>


<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/highslide/highslide.js', CClientScript::POS_HEAD); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/highslide/highslide_eh.js', CClientScript::POS_HEAD); ?>

<?php
/*
$cs=Yii::app()->clientScript;
$baseUrl=Yii::app()->request->baseUrl;

$cs->registerCoreScript('jquery');
$cs->registerScriptFile($baseUrl.'/js/hightslide/hightslide.js');
$cs->registerScriptFile($baseUrl.'/js/hightslide/hightslide_eh.js');
 */
?>
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="logo"><?php echo CHtml::link(CHtml::encode(Yii::app()->name),Yii::app()->homeUrl); ?></div>
	</div><!-- header -->

	<div id="mainmenu">
		<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array('label'=>'Home', 'url'=>array('/site/index')),
				array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
				array('label'=>'Contact', 'url'=>array('/site/contact')),
				array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
			),
		)); ?>
	</div><!-- mainmenu -->
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>

	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> by My Company.<br/>
		All Rights Reserved.<br/>
		<?php echo Yii::powered(); ?>
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>
