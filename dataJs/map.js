//标注点数组
// {
//     title: "路口1",
//     content: "图片1",
//     point: "122.062244|37.194687",
//     isOpen: 0,
//     icon: {
//         w: 23,
//         h: 25,
//         l: 46,
//         t: 21,
//         x: 9,
//         lb: 12
//  }
// },
// {
//     title: "文登南路",
//     content: "图片2",
//     point: "122.079205|37.202448",
//     isOpen: 0,
//     icon: {
//         w: 23,
//         h: 25,
//         l: 46,
//         t: 21,
//         x: 9,
//         lb: 12
//     }
// }
var markerArr = [];
// icon
var icon = [{icon:{
        w: 23,
        h: 25,
        l: 46,
        t: 21,
        x: 9,
        lb: 12
}}];

$("#map-search-btn").click(function () {
    var text = $('#crop-pic').val();
    da = text.split(';')[1].split(',')[1];
    $('#getCroppedCanvasModal').modal('hide');
    $("#searche-target").css('display','none');
    $("#searche-result").css('display','block');

    var usage = $("meta[name=usage]").attr("content");
    ///console.info(da);
    $.ajax({
        url:'./php/search_upload.php',
        type:'POST', //GET
        data:{
            data:da,
            isDetect:false,
            usage:usage
        },
        timeout:50000,    //超时时间
        dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
        beforeSend:function(xhr){
            $('.loading').fadeIn();
        },
        success:function(data){
            //console.info(data);
            $("#map-re").css('display','block');
            $("#viewer-re").css('display','none');
            // map-re
            // data =[
            //     {
            //         title: "Rank",
            //         content: "联通岗",
            //         point: "37.200251|122.061231",
            //         isOpen: 0
            //          url:
            //     },
            //     {
            //         title: "文登南路",
            //         content: "图片2",
            //         point: "37.200427|122.069339",
            //         isOpen: 0
            //
            //     }];
            $("#search-time").append("<h4>Search&nbspTime:&nbsp "+data['cost time']+"s </h4>");
            $.each(data['map'],function(n,value) {
                if(value['title']!=""){
                    value['icon'] = icon[0]['icon'];
                    value['isOpen'] = 0;
                    value['cid'] = "MapResult" + n;
                    value['id'] = n;
                    markerArr.push(value);
                }
            });
        },
        error:function(xhr,textStatus){
            console.log('错误');
        },
        complete:function(){
            $('.loading').fadeOut();
            initMap();
        }
    });

});

$('.map-btn').click(function () {
    alert("more");
    // destory
    $("#map-re").css('display','none');
    $("#viewer-re").css('display','block');
    var myViewer=$("#mytest");
    $.each(data['img'],function(n,value) {
        myViewer.append("<li><img src="+value+" alt='图片1'><span>Rank:&nbsp"+eval(n+1)+"</span></li>");
    });

    myViewer.viewer();
    // $("#li_origin").append("<img style='max-width: 100%' id='imagei' src="+data['origin_img']+">");
    // $("#myorigin").viewer();
    if(isPerson){
        console.info($("#mytest li img"));
        $("#mytest li ").css('text-align','center');
        $("#mytest li img").css('margin-left','31%');
        $("#mytest li img").css('width','100px');
        $("#mytest li img").css('height','300px');
    }
});
// 构造makerArry
function makeMakerArry(data) {
    //     data 格式：
    //     title: "路口1",
    //     content: "图片1",
    //     point: "122.062244|37.194687",
    console.info(data);
    for(var da in data){
        var temp = {};
        temp['id'] = da;
        temp['title'] = data[da]['title'];
        temp['content'] = data[da]['content'];
        temp['point'] = data[da]['point'];
        temp['isOpen'] = data[da]['isOpen'];
        temp['icon'] = icon[0]['icon'];
        temp['url'] = data[da]['url'];
        markerArr.push(temp);
    }
}

function createViewer(id) {
    $("#map-re").css('display','none');
    $("#viewer-re").css('display','block');
    var myViewer=$("#mytest");
    myViewer.empty();
    $.each(markerArr[parseInt(id)]['url'],function(n,value) {
        myViewer.append("<li><img src="+value+" alt='图片1'><span>Rank:&nbsp"+eval(n+1)+"</span></li>");
    });
    myViewer.viewer();
}

$("#map-btn-back").click(function () {
    var myViewer=$("#mytest");
    myViewer.viewer('destroy');
    myViewer.empty();
    $("#map-re").css('display','block');
    $("#viewer-re").css('display','none');
});

//创建和初始化地图函数：
function initMap() {
    createMap(); //创建地图
    setMapEvent(); //设置地图事件
    addMapControl(); //向地图添加控件
    addMarker(); //向地图中添加marker
}

//创建地图函数：
function createMap() {
    var map = new BMap.Map("dituContent",{enableMapClick:false}); //在百度地图容器中创建一个地图
    var point = new BMap.Point(122.067095, 37.193089); //定义一个中心点坐标
    map.centerAndZoom(point, 13); //设定地图的中心点和坐标并将地图显示在地图容器中
    window.map = map; //将map变量存储在全局
}

//地图事件设置函数：
function setMapEvent() {
    map.enableDragging(); //启用地图拖拽事件，默认启用(可不写)
    map.enableScrollWheelZoom(); //启用地图滚轮放大缩小
    map.enableDoubleClickZoom(); //启用鼠标双击放大，默认启用(可不写)
    map.enableKeyboard(); //启用键盘上下左右键移动地图
}

//地图控件添加函数：
function addMapControl() {
    //向地图中添加缩放控件
    var ctrl_nav = new BMap.NavigationControl({
        anchor: BMAP_ANCHOR_TOP_LEFT,
        type: BMAP_NAVIGATION_CONTROL_SMALL
    });
    map.addControl(ctrl_nav);
    //向地图中添加缩略图控件
    var ctrl_ove = new BMap.OverviewMapControl({
        anchor: BMAP_ANCHOR_BOTTOM_RIGHT,
        isOpen: 0
    });
    map.addControl(ctrl_ove);
    //向地图中添加比例尺控件
    var ctrl_sca = new BMap.ScaleControl({
        anchor: BMAP_ANCHOR_BOTTOM_LEFT
    });
    map.addControl(ctrl_sca);
}

//创建marker
function addMarker() {
    for (var i = 0; i < markerArr.length; i++) {
        var json = markerArr[i];
        var p0 = json.point.split("|")[1];
        var p1 = json.point.split("|")[0];
        console.info(p0);
        var point = new BMap.Point(p0, p1);
        var iconImg = createIcon(json.icon);
        var marker = new BMap.Marker(point, {
            icon: iconImg
        });
        var iw = createInfoWindow(i);
        var label = new BMap.Label(json.title, {
            "offset": new BMap.Size(json.icon.lb - json.icon.x + 10, -20)
        });
        marker.setLabel(label);
        map.addOverlay(marker);
        label.setStyle({
            borderColor: "#808080",
            color: "#333",
            cursor: "pointer"
        });

        (function() {
            var index = i;
            var _iw = createInfoWindow(i);
            var _marker = marker;
            _marker.addEventListener("click",
                function() {
                    this.openInfoWindow(_iw);
                });
            _iw.addEventListener("open",
                function() {
                    _marker.getLabel().hide();
                });
            _iw.addEventListener("close",
                function() {
                    _marker.getLabel().show();
                });
            label.addEventListener("click",
                function() {
                    _marker.openInfoWindow(_iw);
                });
            if ( !! json.isOpen) {
                label.hide();
                _marker.openInfoWindow(_iw);
            }
        })()
    }
}
//创建InfoWindow
function createInfoWindow(i) {
    var opts = {
        height:360,
        width:460
    };
    var json = markerArr[i];
    var iw = new BMap.InfoWindow("<b class='iw_poi_title' title='" + json.title + "'>" + json.title + "</b>" +
        "<div class='row' style='text-align: center;height: auto;margin-top: 10px'> " +
            "<div>" +
            "<img src='" + json.content + "' style='width: 360px;height: 250px'>" +
            "</div>"+
        "</div>" +
        "</div>" +
        "<div class='row' style='text-align: center;margin-top: 10px'>" +
        "<div class='col-md-12'>" +
            "<a class='map-btn-result map-btn-result-action map-btn-result-pill' onclick='createViewer("+json.id+")' myid='"+ json.id +"' id='" + json.cid + "'>查看所有结果</a>" +
        "</div>" +
        "</div>"
        ,opts);
    return iw;
}
//创建一个Icon
function createIcon(json) {
    var icon = new BMap.Icon("http://api.map.baidu.com/lbsapi/creatmap/images/us_mk_icon.png", new BMap.Size(json.w, json.h), {
        imageOffset: new BMap.Size( - json.l, -json.t),
        infoWindowAnchor: new BMap.Size(json.lb + 5, 1),
        offset: new BMap.Size(json.x, json.h)
    });
    return icon;
}

function createRedIcon(json){
    var icon = new BMap.Icon("http://api.map.baidu.com/lbsapi/creatmap/images/us_mk_icon.png", new BMap.Size(json.w,json.h),{
        imageOffset: new BMap.Size(-json.l,-json.t),
        infoWindowOffset:new BMap.Size(json.lb+5,1),
        offset:new BMap.Size(json.x,json.h)});
    return icon;
}

