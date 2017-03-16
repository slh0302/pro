/**
 * Created by Su on 2017/2/28.
 */
$(document).ready(function () {
    var $image = $('#image');
    var originalImageURL = $image.attr('src');
    var uploadedImageURL;
  //  var $download = $('#download');
    var options = {
        autoCrop:false,
        aspectRatio: NaN,
        crop: function(e) {
            // Output the result data for cropping image.
        }
    };

    $(Window).resize(function () {
        $image.cropper("reset");
    });

    //image picker choose
    $("#select_pic").imagepicker({
        clicked:function (data) {
            var src_path=data['node'].children().children().attr('src');
            console.log(data['node'].children().children().attr('src'));
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
            $btn_crop.css("display","none");
            $btn_detect.css("display","inline");

            //data-original-title
        }else{
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
                        console.info(result);
                        // Bootstrap's Modal
                        $('#crop-pic').val(result.toDataURL('image/jpeg'));
                        $('#getCroppedCanvasModal').modal().find('.modal-body').html(result);
                        $("#li_origin").append("<img style='max-width: 100%' id='imagei' src="+result.toDataURL('image/jpeg')+">");

                    }
                    break;

                case 'getDetect':
                    if (result) {
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
                            },
                            success:function(data){
                                var last=data['count'];
                                $("#image-detect").attr("src", data['origin_pic']);
                                var $my_select=$('#detect_pic');
                                $.each(data['img'],function(n,value) {
                                    if(n==0){
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
                                $("#detect_pic").imagepicker();
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
    console.info(window.URL);
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

        //$.get('./php/search_upload.php');
        $.ajax({
            url:'./php/search_upload.php',
            type:'POST', //GET
            data:{
                data:da
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
        // $.post("./php/search_upload.php",{data:da},function (data) {
        //     alert(data);
        // });
    });
});
