<?php
$host='localhost';
$user='root';
$passwd='111111';
$database='relation';
$con=mysqli_connect($host,$user,$passwd,$database);
if (mysqli_connect_errno($con)){
    echo "连接 MySQL 失败: " . mysqli_connect_error();
}

    $sql="select * from us where id='".$_GET['id']."'";
    $res=mysqli_query($con,$sql);
    $info=mysqli_fetch_assoc($res);
    $img=$info['image'];
    if($info['name']==$_GET['name']){
        $title='我的社交圈';
        $ff='id';
        $id=$info['id'];
    }else{
        $title=$info['name']."的社交圈";
        $sql="select id from us where name='".$_GET['name']."'";
        $res=mysqli_fetch_array(mysqli_query($con,$sql),MYSQLI_ASSOC);
        $ff='fid';
        $id=$res['id'];
    }
    $news = array(
    "Title" =>$info['name']."的朋友圈",
    'Description'=>$info['name']."是你的朋友吗？",
    "PicUrl" =>$info['image'],
    "Url" =>"http://www.minizhen.com/pk/friendLocation/ready.php?uid=".$info['id']."&name=".$info['name']."",
      );
require_once "jssdk.php";
$jssdk = new JSSDK("wxf90ee8b34845fa70", "d84fc57802da6bc3f7bc3362670b6543");
$signPackage = $jssdk->GetSignPackage();
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<meta charset="UTF-8">
	<meta name="format-detection" content="telephone=no"/>
	<title><?php echo $title;?></title>
	 <link rel="stylesheet" type="text/css" href="dist/style.css">
</head>
<body>

<div id="dowebok">
    <div class="section" >
        <h1><?php echo $title;?></h1>
        <div class="head_img">
            <img src="<?php echo $img;?>">
        </div>
    </div>
    <div class="section" id ="section1">
    <input type="hidden" id="uid" name="<?php echo $ff;?>" value="<?php echo $id;?>" >
    </div>
    <div class="section">
        <div id="main" style="width:120%; margin-left:-60%; left:50%">
        </div>        
    </div>
    <div class="section">
        <div class="btn">
                分享给好友，寻找他们的位置
        </div>
        <div class="btn">
                生成我的社交圈
        </div>
    </div>
</div>


<script src="static/js/jquery-3.1.0.min.js"></script>
<script src="static/js/jquery.fullPage.js"></script>
<script src="static/js/d3.v3.min.js" charset="utf-8"></script>  
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script src="dist/common.js"></script>
<script src="dist/build.js"></script>
<script>
    /*
     * 注意：
     * 1. 所有的JS接口只能在公众号绑定的域名下调用，公众号开发者需要先登录微信公众平台进入“公众号设置”的“功能设置”里填写“JS接口安全域名”。
     * 2. 如果发现在 Android 不能分享自定义内容，请到官网下载最新的包覆盖安装，Android 自定义分享接口需升级至 6.0.2.58 版本及以上。
     * 3. 常见问题及完整 JS-SDK 文档地址：http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html
     *
     * 开发中遇到问题详见文档“附录5-常见错误及解决办法”解决，如仍未能解决可通过以下渠道反馈：
     * 邮箱地址：weixin-open@qq.com
     * 邮件主题：【微信JS-SDK反馈】具体问题
     * 邮件内容说明：用简明的语言描述问题所在，并交代清楚遇到该问题的场景，可附上截屏图片，微信团队会尽快处理你的反馈。
     */
$(function(){
        wx.config({
                  debug: false,
                  appId: '<?php echo $signPackage["appId"];?>',
                  timestamp: <?php echo $signPackage["timestamp"];?>,
                  nonceStr: '<?php echo $signPackage["nonceStr"];?>',
                  signature: '<?php echo $signPackage["signature"];?>',
                  jsApiList: [
        	                      // 所有要调用的 API 都要加到这个列表中
                                  'checkJsApi',
                                  'onMenuShareTimeline',
                                  'onMenuShareAppMessage',
                                  'openLocation',
                                  'getLocation',
                              ]
        });
        wx.ready(function() {
            // 在这里调用 API
            //检查是否支持
            //发送给朋友
            wx.onMenuShareAppMessage({
                title: '<?php echo $news['Title'];?>',
                desc: '<?php echo $news['Description'];?>',
                link: '<?php echo $news['Url'];?>',
                imgUrl: '<?php echo $news['PicUrl'];?>',
                trigger: function (res) {
                    // 不要尝试在trigger中使用ajax异步请求修改本次分享的内容，因为客户端分享操作是一个同步操作，这时候使用ajax的回包会还没有返回
                    // alert('用户点击发送给朋友');
                },
                success: function (res) {
                    // alert('已分享');
                },
                cancel: function (res) {
                    // alert('已取消');
                },
                fail: function (res) {
                    // alert(JSON.stringify(res));
                }
            });
            wx.onMenuShareTimeline({
                title: '<?php echo $news['Title'];?>',
                link: '<?php echo $news['Url'];?>',
                imgUrl: '<?php echo $news['PicUrl'];?>',
                trigger: function (res) {
                    // 不要尝试在trigger中使用ajax异步请求修改本次分享的内容，因为客户端分享操作是一个同步操作，这时候使用ajax的回包会还没有返回
                    // alert('用户点击分享到朋友圈');
                },
                success: function (res) {
                    // alert('已分享');
                },
                cancel: function (res) {
                    // alert('已取消');
                },
                fail: function (res) {
                    // alert(JSON.stringify(res));
                }
            });
        })
        $('#dowebok').fullpage({

        });
})
</script>  

<script>       
    
var width = window.screen.width ;
var height = window.screen.height - 60;
var img_w = 77;
var img_h = 77;

var svg = d3.select("#section1").append("svg")
.attr("width",width)
.attr("height",height);

var id = $("#uid").val();
var uidName = $("#uid").attr("name")
var SocialData = {
    id : id,
    type : uidName
}

$.ajax({
    url : "relation.php",
    type : "POST",
    data : SocialData,
    dataType : "json",
    success : function(root){
          var force = d3.layout.force()
                  .nodes(root.nodes)
                  .links(root.edges)
                  .size([width,height])
                  .linkDistance(200)
                  .charge(-1500)
                  .start();
                  
          var edges_line = svg.selectAll("line")
              .data(root.edges)
              .enter()
              .append("line")
              .style("stroke","#ccc")
              .style("stroke-width",1);
              
          var edges_text = svg.selectAll(".linetext")
              .data(root.edges)
              .enter()
              .append("text")
              .attr("class","linetext")
              .style("fill-opacity",0)
              .text(function(d){
                return d.relation;
              });

                    
          var nodes_img = svg.selectAll("image")
              .data(root.nodes)
              .enter()
              .append("image")
              .attr("width",img_w)
              .attr("height",img_h)
              .attr("xlink:href",function(d){
                return d.image;
              })
              // .on("mouseover",function(d,i){
              //   //显示连接线上的文字
              //   edges_text.style("fill-opacity",function(edge){
              //     if( edge.source === d || edge.target === d ){
              //       return 1.0;
              //     }
              //   });
              // })
              // .on("mouseout",function(d,i){
              //   //隐去连接线上的文字
              //   edges_text.style("fill-opacity",function(edge){
              //     if( edge.source === d || edge.target === d ){
              //       return 0.0;
              //     }
              //   });
              // })
              .call(force.drag);

          var text_dx = -30;
          var text_dy = 20;

          var nodes_text = svg.selectAll(".nodetext")
                  .data(root.nodes)
                  .enter()
                  .append("text")
                  .attr("class","nodetext")
                  .attr("dx",text_dx)
                  .attr("dy",text_dy)
                  .text(function(d){
                    return d.name;
                  });

                    
          force.on("tick", function(e){
            //限制结点的边界
            root.nodes.forEach(function(d,i){
              d.x = d.x - img_w/2 < 0     ? img_w/2 : d.x ;
              d.x = d.x + img_w/2 > width ? width - img_w/2 : d.x ;
              d.y = d.y - img_h/2 < 0      ? img_h/2 : d.y ;
              d.y = d.y + img_h/2 + text_dy > height ? height - img_h/2 - text_dy : d.y ;
            });

            //更新连接线的位置
             edges_line.attr("x1",function(d){ return d.source.x; });
             edges_line.attr("y1",function(d){ return d.source.y; });
             edges_line.attr("x2",function(d){ return d.target.x; });
             edges_line.attr("y2",function(d){ return d.target.y; });
             
             //更新连接线上文字的位置
             edges_text.attr("x",function(d){ return (d.source.x + d.target.x) / 2 ; });
             edges_text.attr("y",function(d){ return (d.source.y + d.target.y) / 2 ; });
             
             
             //更新结点图片和文字
             nodes_img.attr("x",function(d){ return d.x - img_w/2; });
             nodes_img.attr("y",function(d){ return d.y - img_h/2; });
             
             nodes_text.attr("x",function(d){ return d.x });
             nodes_text.attr("y",function(d){ return d.y + img_w/2; });
          });
    },
    error : function() {
      
    }
})



// d3.xhr.post("relation.php",SocialData,function(error,root){
//     if( error ){
//       return console.log(error);
//     }
//     console.log(root);
// });

</script>

</body>

</html>