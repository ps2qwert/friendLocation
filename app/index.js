require('../static/css/reset.css'); //加载初始化样式
require('../static/css/style.css'); //加载组件样式
// var $ = require("n-zepto");
// var $ = require("jquery")
var echarts = require("echarts/lib/echarts")
require('echarts/lib/chart/bar');
// 引入提示框和标题组件
require('echarts/lib/component/tooltip');
require('echarts/lib/chart/treemap');
require('echarts/lib/component/title');
require('echarts/lib/chart/scatter');
require('echarts/lib/chart/effectScatter')
require('echarts/lib/chart/map');
require('echarts/lib/component/geo');
require('echarts/lib/component/legend');
require('echarts/lib/component/dataZoom');
require('echarts/lib/component/visualMap');
require('echarts/map/js/china')

require("fullpage.js/dist/jquery.fullpage.css")








// 基于准备好的dom，初始化echarts实例
var myChart = echarts.init(document.getElementById('main'));
// 绘制图表
myChart.showLoading();
wx.ready(function () {
        wx.getLocation({
            type: 'wgs84', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
            success: function (res) {
                var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
                var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
                var speed = res.speed; // 速度，以米/每秒计
                var accuracy = res.accuracy; // 位置精度
                var arr = [];
                var uidName = $("#uid").attr("name")
                var id = $("#uid").val();
                arr[0] = [longitude,latitude]
                $.ajax({
                    url : "data.php",
                    type : "POST",
                    data : {
                        x : longitude,
                        y : latitude,
                        id : id,
                        type : uidName
                    },
                    dataType : "json",
                    success : function(data){

                        getGeo(data);
                    },
                    error:function(x,y,z){
                        console.log(x);
                        console.log(y);
                        console.log(z);
                    }
                })
            }
        });   
})

function getGeo(arrData){
    arrData = arrData.map(function (serieData,idx){
            var px = Number(serieData['x']);
            var py = Number(serieData['y']);
            if(!isNaN(px) && !isNaN(py)){
                    var res = [[px,py]];
            }else{
                    var res = []
                    $.each(serieData,function(idx,obj){
                        var dx = Number(obj.fx);
                        var dy = Number(obj.fy);
                        res.push([dx,dy])
                    })       
            }
            return res;
    })
    $.get('weibo.json', function (weiboData) {
        myChart.hideLoading();
        weiboData = weiboData.map(function (serieData, idx) {
            var px = serieData[0] / 1000;
            var py = serieData[1] / 1000;
            var res = [[px, py]];

            for (var i = 2; i < serieData.length; i += 2) {
                var dx = serieData[i] / 1000;
                var dy = serieData[i + 1] / 1000;
                var x = px + dx;
                var y = py + dy;
                res.push([x.toFixed(2), y.toFixed(2), 1]);
                px = x;
                py = y;
            }
            return res;
        });
        myChart.setOption(option = {
            backgroundColor: '#404a59',
            title : {
                text: '您的好友位置',
                subtext: 'From ThinkGIS',
                sublink: 'http://www.thinkgis.cn/public/sina',
                left: 'center',
                top: '20',
                textStyle: {
                    color: '#fff'
                }
            },
            tooltip: {},
            legend: {
                left: 'center',
                bottom:'20',
                data: ['我', '朋友'],
                textStyle: {
                    color: '#ccc'
                }
            },
            geo: {
                name: '我',
                type: 'scatter',
                map: 'china',
                label: {
                    emphasis: {
                        show: false
                    }
                },
                itemStyle: {
                    normal: {
                        areaColor: '#323c48',
                        borderColor: '#111'
                    },
                    emphasis: {
                        areaColor: '#2a333d'
                    }
                }
            },
            series: [{
                name: '朋友',
                type: 'scatter',
                coordinateSystem: 'geo',
                symbolSize: 5,
                large: true,
                itemStyle: {
                    normal: {
                        shadowBlur: 2,
                        shadowColor: 'rgba(37, 140, 249, 0.8)',
                        color: 'rgba(37, 140, 249, 0.8)'
                    }
                },
                data: arrData[1]
            }, {
                name: '我',
                type: 'effectScatter',
                coordinateSystem: 'geo',
                rippleEffect: {
                    brushType: 'stroke'
                },
                symbolSize: 10,
                large: true,
                itemStyle: {
                    normal: {
                        shadowBlur: 10,
                        shadowColor: 'rgba(255, 255, 255, 0.8)',
                        color: 'rgba(255, 255, 255, 0.8)'
                    }
                },
                zlevel: 1,
                data: arrData[0]
            }]
        });
    });
}