
/**
 * Created by Su on 2017/3/5.
 */
window.onload(function () {
});
$(document).ready(function () {
    $.get('./php/getDatabaseInfo.php',function (data) {
        //db_file
        var $add_data=$('#add-data');
        if(data['msg']=="SUCCESS"){
            $add_data.empty();
            $.each(data['data'],function(n,value) {
                $add_data.append(
                    "<a class='gallery-item'  href='single.html?name="+value['database']+"'>" +
                    "<img src='images/work_"+n+".jpg' alt='IDM SYSTEM DATABASE'>" +
                    "<span class='overlay'>" +
                    "<h2>"+value['database']+"</h2>" +
                    "<span>"+value['count']+" images</span>" +
                    "</a>"
                );
            });
        }
    });
});