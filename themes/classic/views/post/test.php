<div class="test">
test
</div>
<?php
//echo "Hello";
// Yii::app()->clientScript->registerCoreScript('jquery');
// //print_r(Yii::app()->clientScript);

// 	$arr=array('color'=>'red','width','b'=>2,);
// 	$arr['color']=array('red1','blue','block');
// 	$arr['color']['red']=array('750','950');
// 	print_r($arr);
	
class A { }
class B { }

$thing = new A;

if ($thing instanceof A) {
	echo 'A';
}
if ($thing instanceof B) {
	echo 'B';
}
?>

<script>
$(function(){
	$(".test").click(function(){
		var hey2=new Object();
		hey2['color']=['red','blue','block'];
		hey2['b']='2';
		
		hey2['color']['red']=[750,190];
		
		hey2['color']['red']['750']=[];
		
		$.post(
				"/post/test1",
				{data:hey2},
				function(data){
					console.log('data:',data);
					}
					);
		});

	var hey2=new Object();
	hey2['color']=['red','blue','block'];
	hey2['b']='2';
	
	hey2['color']['red']=[750,190];
	
	hey2['color']['red']['750']=[];

	//hey2['a'][0]['6']='6';
	//hey2['a'][0]['7']='7';

	//hey2['a'][0]['6']=['f','e'];
	
	
	console.log('hey2',hey2);

	var hey3=new Object();
	hey3['color']=['89'];
	hey3['color']['red']=['red'];
	hey3['color']['blue']=['blue'];
	hey3['color']['block']=['block'];
	
	hey3['b']='2';
	
	hey3['color']['red']=[750,190];
	
	console.log('hey3',hey3);
	
})
</script>