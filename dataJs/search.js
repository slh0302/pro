/**
 * Created by Su on 2017/2/28.
 */
var databaseName="";
window.onload(function () {
    $("#checkbox-11-2").attr("checked",'false');//全选

});

$(document).ready(function () {

    var $image = $('#image');
    var originalImageURL = $image.attr('src');
    var uploadedImageURL;
    var detectImageURL;
    var isButtonDown=false;
    var checkStatus=false;
////


    //  var $download = $('#download');
    var options = {
        zoomOnWheel:false,
        autoCrop:false,
        aspectRatio: NaN,
        crop: function(e) {
            // Output the result data for cropping image.
        }
    };

    $(Window).resize(function () {
        $image.cropper("reset");
    });


    //right button
    var toggle = $('#ss_toggle');
    var menu = $('#ss_menu');
    var rot;
    $('#ss_toggle').on('click', function (ev) {
        rot = parseInt($(this).data('rot')) - 180;
        menu.css('transform', 'rotate(' + rot + 'deg)');
        menu.css('webkitTransform', 'rotate(' + rot + 'deg)');
        if (rot / 180 % 2 == 0) {
            toggle.parent().addClass('ss_active');
            toggle.addClass('close');
        } else {
            toggle.parent().removeClass('ss_active');
            toggle.removeClass('close');
        }
        $(this).data('rot', rot);
    });
    menu.on('transitionend webkitTransitionEnd oTransitionEnd', function () {
        if (rot / 180 % 2 == 0) {
            $('#ss_menu div i').addClass('ss_animate');
        } else {
            $('#ss_menu div i').removeClass('ss_animate');
        }
    });
    // find data
    var $setting= $("#setting");
    var temp;
    $.get('./php/getDatabaseInfo.php',function (data) {
        //db_file
        var $show_data=$('#data-show');
        var $desc_data=$('#data-descrip');
        if(data['msg']=="SUCCESS"){
            temp=data['data'];
            $show_data.empty();
            $.each(data['data'],function(n,value) {
                if(n==0 && databaseName=="") {
                    databaseName=value['db_location'];
                    console.info("change");
                    $desc_data.append("<h4>Database Name:  &nbsp;&nbsp;"+value['database']+" </h4>");
                    $desc_data.append("<h4>status:  &nbsp;&nbsp;"+value['status']+" </h4>");
                    $desc_data.append("<h4>Count:  &nbsp;&nbsp;"+value['count']+" </h4>");
                    $desc_data.append("<h4>File Location:  &nbsp;&nbsp;"+value['fileLocation']+" </h4>");
                }
                $show_data.append("<option>"+value['database']+","+value['count']+"</option>");
            });
            $('.selectpicker').selectpicker('destroy').selectpicker({showTick:true});
            $('#content-data a').click(function () {
                datatemp=temp[$(this).parent().attr("data-original-index")]
                $desc_data.empty();
                $desc_data.append("<h4>Database Name:  &nbsp;&nbsp;"+datatemp['database']+" </h4>");
                $desc_data.append("<h4>status:  &nbsp;&nbsp;"+datatemp['status']+" </h4>");
                $desc_data.append("<h4>Count:  &nbsp;&nbsp;"+datatemp['count']+" </h4>");
                $desc_data.append("<h4>File Location:  &nbsp;&nbsp;"+datatemp['fileLocation']+" </h4>");
            });
        }
    });
   // $('.selectpicker').selectpicker({showTick:true});
    $setting.on('click',function () {
        $("#settings").modal();
    });
    //





    $("#refresh").on('click',function () {
        isButtonDown=false;
        location.reload();
    });
    $("#back").on('click',function () {
        if(isButtonDown){
            isButtonDown=false;
            location.reload();
        }
    });

    //move judge
    $(window).scroll(function(e){
        p=$(document).scrollTop();
        half=$(window).height()/2;
        total=$(document).height();
        if(p+half > total/2){
            $("#move").css("display","none");
            $("#move-up").css("display","inline-block");
        }else{
            $("#move").css("display","inline-block");
            $("#move-up").css("display","none");
        }
    });
    $("#move,#move-up").on('click',function () {
        p=$(document).scrollTop();
        half=$(window).height()/2;
        total=$(document).height();
        console.info(p+half);
        if(p+half > total/2){
            //too down
            $(document).scrollTop(0);
        }else{
            //too up
            var h = $(document).height()-$(window).height();
            $(document).scrollTop(h);
        }
    });
    //image picker choose
    //add pic
    var $select_pic=$("#select_pic");
    $select_pic.imagepicker({
        clicked:function (data) {
            var src_path=data['node'].children().children().attr('src');
            console.log(data['node'][0]);
            $image.cropper('destroy').attr('src', src_path).cropper(options);
        }
    });
    //
    //checkbox change
   // alert($('#checkbox-id').is(':checked'));
    var $checkbox= $("#checkbox-11-2");
    var $btn_crop= $("#btn-crop");
    var $btn_detect=$("#btn-detect");
    $checkbox.change(function() {
        if($checkbox.is(':checked')){
            checkStatus=true;
            $btn_crop.css("display","none");
            $btn_detect.css("display","inline");
            //data-original-title
        }else{
            checkStatus=false;
            $btn_crop.css("display","inline");
            $btn_detect.css("display","none");
        }
    });


    $image.cropper(options);
  //  $image.cropper('clear');
    $('[data-toggle="tooltip"]').tooltip();

    if (!$.isFunction(document.createElement('canvas').getContext)) {
        $('button[data-method="getCroppedCanvas"]').prop('disabled', true);
    }
    if (typeof document.createElement('cropper').style.transition === 'undefined') {
        $('button[data-method="rotate"]').prop('disabled', true);
        $('button[data-method="scale"]').prop('disabled', true);
    }
    //download
    // if (typeof $download[0].download === 'undefined') {
    //     $download.addClass('disabled');
    // }
    // Methods
    $('.docs-buttons').on('click', '[data-method]', function () {
        var $this = $(this);
        var data = $this.data();
        var $target;
        var result;

        if ($this.prop('disabled') || $this.hasClass('disabled')) {
            return;
        }

        if ($image.data('cropper') && data.method) {
            data = $.extend({}, data); // Clone a new one

            if (typeof data.target !== 'undefined') {
                $target = $(data.target);

                if (typeof data.option === 'undefined') {
                    try {
                        data.option = JSON.parse($target.val());
                    } catch (e) {
                        console.log(e.message);
                    }
                }
            }

            if (data.method === 'rotate') {
                $image.cropper('clear');
            }
            if(data.method === 'getDetect' ) {
                result = $image.cropper('getCroppedCanvas', data.option, data.secondOption);
            }else{
                result = $image.cropper(data.method , data.option, data.secondOption);
            }

            if (data.method === 'rotate') {
                $image.cropper('crop');
            }
            switch (data.method) {
                case 'scaleX':
                case 'scaleY':
                    $(this).data('option', -data.option);
                    break;

                case 'getCroppedCanvas':
                    if (result) {
                        isButtonDown=true;
                        var $li_origin=$("#li_origin");
                        console.info(result);
                        // Bootstrap's Modal
                        $('#crop-pic').val(result.toDataURL('image/jpeg'));
                        $('#getCroppedCanvasModal').modal().find('.modal-body').html(result);
                        $li_origin.empty();
                        $li_origin.append("<img style='max-width: 100%;height:300px' id='imagei' src="+result.toDataURL('image/jpeg')+">");

                    }
                    break;

                case 'getDetect':
                    if (result) {
                        isButtonDown=true;
                        console.info(result);
                        da=result.toDataURL('image/jpeg').split(';')[1].split(',')[1];
                       // $('#crop-pic').val(result.toDataURL('image/jpeg'));
                        $.ajax({
                            url:'./php/detect_upload.php',
                            type:'POST', //GET
                            data:{
                                data:da
                            },
                            timeout:20000,    //超时时间
                            dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
                            beforeSend:function(xhr){
                                $("#pic-picked").css("display","none");
                                $("#show-main").css("display","none");
                                $("#pic-detect").css("display","block");
                                //loading 特效
                                $('#detect-load').fadeIn();
                                $('#detect-h2').fadeIn();
                                $('#btn-submit').attr("disabled", true);
                            },
                            success:function(data){
                                var last=data['count'];
                                $("#image-detect").attr("src", data['origin_pic']);
                                var $my_select=$('#detect_pic');
                                $my_select.css("display","inline");
                                $.each(data['img'],function(n,value) {
                                    if(n==0){
                                        detectImageURL=value;
                                        $my_select.append("<option data-img-src='"+value+"' data-img-class='first' data-img-alt='Page '"+n+"' value='"+n+"'>  Page "+n+"  </option>");
                                    }else if(n==last-1){
                                        $my_select.append("<option data-img-src='"+value+"' data-img-class='last' data-img-alt='Page '"+n+"' value='"+n+"'>  Page "+n+"  </option>");
                                    }else $my_select.append("<option data-img-src='"+value+"' data-img-alt='Page '"+n+"' value='"+n+"'>  Page "+n+"  </option>");
                                });
                            },
                            error:function(xhr,textStatus){
                            },
                            complete:function(){
                             //   $('.loading').fadeOut();
                                $('#detect-load').fadeOut();
                                $('#detect-h2').fadeOut();
                                $("#detect_pic").imagepicker({
                                    clicked:function (data) {
                                        var src_path=data['node'].children().children().attr('src');
                                        console.log(data['node'][0]);
                                        detectImageURL=src_path;
                                    }
                                });
                                $('#btn-submit').attr("disabled", false);
                                console.log('结束');
                            }
                        });
                    }
                    break;
            }

            if ($.isPlainObject(result) && $target) {
                try {
                    $target.val(JSON.stringify(result));
                } catch (e) {
                    console.log(e.message);
                }
            }

        }
    });


    // Import image
    var $inputImage = $('#inputImage');
    var URL = window.URL || window.webkitURL;

    if (URL) {
        $inputImage.change(function () {
            var files = this.files;
            var file;

            if (!$image.data('cropper')) {
                return;
            }

            if (files && files.length) {
                file = files[0];

                if (/^image\/\w+$/.test(file.type)) {
                    if (uploadedImageURL) {
                        URL.revokeObjectURL(uploadedImageURL);
                    }

                    uploadedImageURL = URL.createObjectURL(file);
                    $image.cropper('destroy').attr('src', uploadedImageURL).cropper(options);
                    $inputImage.val('');
                } else {
                    window.alert('Please choose an image file.');
                }
            }
        });
    } else {
        $inputImage.prop('disabled', true).parent().addClass('disabled');
    }


    $("#search-btn").click(function () {
        var text = $('#crop-pic').val();
        da=text.split(';')[1].split(',')[1];
        $('#getCroppedCanvasModal').modal('hide');
        $("#searche-target").css('display','none');
        $("#searche-result").css('display','block');

        var usage = $("meta[name=usage]").attr("content");

        //$.get('./php/search_upload.php');
        $.ajax({
            url:'./php/new_search.php',
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
            success:function(data,textStatus,jqXHR){
                console.info(data);
                var myViewer=$("#mytest");
                $.each(data['img'],function(n,value) {
                    myViewer.append("<li><img src="+value+" alt='图片1'><span>Rank:&nbsp"+eval(n+1)+"</span></li>");
                });
                $("#search-time").append("<h4>Search&nbspTime:&nbsp "+data['cost time']+"s </h4>");
                myViewer.viewer();
                // $("#li_origin").append("<img style='max-width: 100%' id='imagei' src="+data['origin_img']+">");
                // $("#myorigin").viewer();
                console.info(usage);
                if(usage == 'person'){
                    console.info($("#mytest li img"));
                    $("#mytest li ").css('text-align','center');
                }
            },
            error:function(xhr,textStatus){
                console.log('错误');
            },
            complete:function(){
                console.log('结束');
                $('.loading').fadeOut();

            }
        });
        // $.post("./php/search_upload.php",{data:da},function (data) {
        //     alert(data);
        // });
    });


    $("#btn-submit").click(function () {
    //$.get('./php/search_upload.php');
        var $li_origin=$("#li_origin");
        $li_origin.empty();
        $li_origin.append("<img style='max-width: 100%' id='imagei' src="+detectImageURL+">");
        $("#searche-target").css('display','none');
		$('#pic-detect').css("display","none");
        $("#searche-result").css('display','block');
        var da = detectImageURL;

        var usage = $("meta[name=usage]");

        $.ajax({
            url:'./php/search_upload.php',
            type:'POST', //GET
            data:{
                data:da,
                isDetect:true,
                usage:usage
            },
            timeout:20000,    //超时时间
            dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
            beforeSend:function(xhr){
                $('.loading').fadeIn();
                console.log(xhr);
                console.log('发送前');
            },
            success:function(data,textStatus,jqXHR){
                // alert(data['img'])
                //
                var myViewer=$("#mytest");
                $.each(data['img'],function(n,value) {
                    myViewer.append("<li><img src="+value+" alt='图片1'><span>Rank:&nbsp"+eval(n+1)+"</span></li>");
                });
                $("#search-time").append("<h4>Search&nbspTime:&nbsp "+data['cost time']+"s </h4>");
                myViewer.viewer();
                // $("#li_origin").append("<img style='max-width: 100%' id='imagei' src="+data['origin_img']+">");
                // $("#myorigin").viewer();
            },
            error:function(xhr,textStatus){
                console.log('错误');
                console.log(xhr);
                console.log(textStatus);
            },
            complete:function(){
                $('.loading').fadeOut();
                console.log('结束');
            }
        });
    });
});
