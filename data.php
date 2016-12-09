<?php
$host='localhost';
$user='root';
$passwd='111111';
$database='relation';
$con=mysqli_connect($host,$user,$passwd,$database);
if (mysqli_connect_errno($con)){
    echo "连接 MySQL 失败: " . mysqli_connect_error();
}
/*
 * 二维数组去重复*/
function unique($data = array()){
    $tmp = array();
    foreach($data as $key => $value){
        //把一维数组键值与键名组合
        foreach($value as $key1 => $value1){
            $value[$key1] = $key1 . '_|_' . $value1;//_|_分隔符复杂点以免冲突
        }
        $tmp[$key] = implode(',|,', $value);//,|,分隔符复杂点以免冲突
    }

    //对降维后的数组去重复处理
    $tmp = array_unique($tmp);

    //重组二维数组
    $newArr = array();
    foreach($tmp as $k => $tmp_v){
        $tmp_v2 = explode(',|,', $tmp_v);
        foreach($tmp_v2 as $k2 => $v2){
            $v2 = explode('_|_', $v2);
            $tmp_v3[$v2[0]] = $v2[1];
        }
        $newArr[] = $tmp_v3;/*$newArr[$k] = $tmp_v3;  引用原来的键名*/
    }
    return $newArr;
}
$id=$_POST['id'];
$x=$_POST['x'];
$y=$_POST['y'];
$type=$_POST['type'];
if($type=="id"){
    $sql="select * from ulocation where uid='".$id."'";
    $res=mysqli_query($con,$sql);
    $ress=mysqli_fetch_array($res,MYSQLI_ASSOC);
    if(!$ress['uid']){
        $sql="insert into ulocation (uid,x,y) values ('{$id}','{$x}','{$y}')";
        mysqli_query($con,$sql);
    }
    $sql="select l.x,l.y from us u join ulocation l on u.id = l.uid where u.id='".$id."'";
    $res=mysqli_query($con,$sql);
    $slef= mysqli_fetch_array($res,MYSQLI_ASSOC);
    $sql2="select l.fx,l.fy from fr f join flocation l on f.fid=l.fid where f.id='".$id."'";
    $res2=mysqli_query($con,$sql2);
    $da=mysqli_fetch_all($res2,MYSQLI_ASSOC);
    $friend=unique($da);
    $data=array(
        $slef,
        $friend,
    );
}else{
    $sql="select * from flocation where fid='".$id."'";
    $res=mysqli_query($con,$sql);
    $ress=mysqli_fetch_array($res,MYSQLI_ASSOC);
    if(!$ress['fid']){
        $sql="insert into flocation (fid,fx,fy) values ('{$id}','{$x}','{$y}')";
        mysqli_query($con,$sql);
    }
    $sql="select l.x,l.y from us u ,fr f, ulocation l where  f.id = u.id and l.uid = u.id and f.fid ='".$id."'";
    $res=mysqli_query($con,$sql);
    $slef= mysqli_fetch_array($res,MYSQLI_ASSOC);
    $sql2="select l.fx,l.fy from fr f join flocation l on f.fid=l.fid ";
    $res2=mysqli_query($con,$sql2);
    $da=mysqli_fetch_all($res2,MYSQLI_ASSOC);
    $friend=unique($da);
    $data=array(
       $slef,$friend,
    );
}
    echo json_encode($data);
?>