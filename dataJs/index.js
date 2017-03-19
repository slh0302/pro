/**
 * Created by Su on 2017/3/19.
 */
$(document).ready(function () {
    $.get('./php/getDatabaseInfo.php',function (data) {
        //db_file
        var $add_data=$('#add-data');
        if(data['msg']=="SUCCESS"){
            $("#data").attr("data-to",data['data'].length);
            var count=0;
            $.each(data['data'],function(n,value) {
                count+=parseInt(value['count']);
            });
            $("#count").attr("data-to",count);
        }
    });
});