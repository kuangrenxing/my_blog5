 <?php if(!$search->hasErrors()): ?>
    <h3><?php echo Yii::t('lan','Search Results'); ?> "<?php echo CHtml::encode($search->keyword); ?>"</h3>
<?php else: ?>
    <h3><?php echo CHtml::error($search,'keyword'); ?></h3>
<?php endif; ?>

<?php foreach($models as $model): ?>
<?php
    $pizza=explode('>',$model->contentshort);
    print_r($pizza);
    echo count($pizza);echo "<br />";
    $s='';
    for($i=0;$i<count($pizza); $i++)
    {
        $piece=explode('<',$pizza[$i]);
        echo count($piece);print_r($piece);echo "<br />";
        //echo $piece[1];
        if(count($piece)==2)
        {
            $replace=preg_replace('/('.CHtml::encode($search->keyword).')/i',
            		'<b><span style="background:yellow;">${1}</span></b>',$piece[0]);
            $s.=$replace.'<'.$piece[1].'>';
        }
    }
    
    $model->contentshort=$s;
    $this->renderPartial('_post',array('model'=>$model));
?>
<?php endforeach; ?>

<br/>
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>
