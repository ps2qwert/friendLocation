<?php
$host='localhost';
$user='root';
$passwd='111111';
$database='relation';
$con=mysqli_connect($host,$user,$passwd,$database);
if (mysqli_connect_errno($con)){
	echo "连接 MySQL 失败: " . mysqli_connect_error();
}
function trakey($keyn,$arr){
	foreach($arr as $key=>$vo){
		$ar[]=array_combine($keyn,$vo);
	}
	return $ar;
}
$id=$_POST['id'];
$type=$_POST['type'];
if($type=="id"){
	$sql="select id,fid from fr where id='".$id."'";
	$aid=mysqli_fetch_all(mysqli_query($con,$sql),MYSQLI_ASSOC);
	for($i=0;$i<count($aid);$i++){
		$da[]=$aid[$i]['fid'];
	}
	$sql2 = "select name,image from us where id in(".implode(',',$da).") limit 10";
	$res=mysqli_query($con,$sql2);
	$edges2=mysqli_fetch_all($res,MYSQLI_ASSOC);
	$sql3="select name,image from us where id= '".$id."'";
	$edge3=mysqli_fetch_all(mysqli_query($con,$sql3),MYSQLI_ASSOC);
	$nodes=array_merge($edge3,$edges2);
	$sql22 = "select id,fid from fr where id in(".implode(',',$da).") limit 10";
	$res22=mysqli_query($con,$sql22);
	$aid2= mysqli_fetch_all($res22,MYSQLI_ASSOC);
	$edges=array_merge($aid,$aid2);
	$ken=array('source','target');
	$as=trakey($ken,$edges);
	if(count($edges)>=count($nodes)){
		$num=count($edges);
	}else{
		$num=count($nodes);
	}
	for($i=0;$i<$num;$i++){
		$sql="select name from us where id='".$as[$i]['source']."'";
		$res=mysqli_fetch_array(mysqli_query($con,$sql),MYSQLI_ASSOC);
		for($j=0;$j<count($nodes);$j++){
			$array[]=$nodes[$j]['name'];
		}
		$ass=array_unique($array);
		$ed[$i]['source']=array_search($res['name'],$ass);
		$sql2="select name from us where id='".$as[$i]['target']."'";
		$res2=mysqli_fetch_array(mysqli_query($con,$sql2),MYSQLI_ASSOC);
		//print_r($res2);
		$ed[$i]['target']= array_search($res2['name'],$ass);
	}
	$data=array(
		"edges"=>$ed,
		"nodes"=>$nodes,
	);
}else{
	$sql="select id from fr  where fid ='".$id."'";
	$res=mysqli_query($con,$sql);
	$res= mysqli_fetch_assoc($res);
	$id=$res['id'];
	$sql5="select id,fid from fr where id='".$id."'";
	$res5=mysqli_query($con,$sql5);
	$edges1= mysqli_fetch_all($res5,MYSQLI_ASSOC);
	for($i=0;$i<count($edges1);$i++){
		$da[]=$edges1[$i]['fid'];
	}
	$sql2 = "select id,fid from fr where id in(".implode(',',$da).") limit 10";
	$res2=mysqli_query($con,$sql2);
	$edges2= mysqli_fetch_all($res2,MYSQLI_ASSOC);
	$edges=array_merge($edges1,$edges2);
	$sql3="select name,image from us where id='".$id."'";
	$res3=mysqli_query($con,$sql3);
	$sql4 = "select name,image from us where id in(".implode(',',$da).") limit 10";
	$res4=mysqli_query($con,$sql4);
	$da=mysqli_fetch_all($res3,MYSQLI_ASSOC);
	$da2=mysqli_fetch_all($res4,MYSQLI_ASSOC);
	$nodes= array_merge($da,$da2);
	$ken=array('source','target');
	$as=trakey($ken,$edges);
	if(count($edges)>=count($nodes)){
		$num=count($edges);
	}else{
		$num=count($nodes);
	}

	for($i=0;$i<$num;$i++){
		$sql="select name from us where id='".$as[$i]['source']."'";
		$res=mysqli_fetch_array(mysqli_query($con,$sql),MYSQLI_ASSOC);
		for($j=0;$j<count($nodes);$j++){
			$array[]=$nodes[$j]['name'];
		}
		$ass=array_unique($array);
		$ed[$i]['source']=array_search($res['name'],$ass);
		$sql2="select name from us where id='".$as[$i]['target']."'";
		$res2=mysqli_fetch_array(mysqli_query($con,$sql2),MYSQLI_ASSOC);
		//print_r($res2);
		$ed[$i]['target']= array_search($res2['name'],$ass);
	}
	$data=array(
		"edges"=>$ed,
		"nodes"=>$nodes,
	);
}



echo json_encode((object)$data);


?>