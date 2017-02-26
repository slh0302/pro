/**
 * Created by Su on 2017/2/23.
 */
$(document).ready(
    function () {
        var URL = document.location.toString();
        var name;
        if (URL.lastIndexOf("?") != -1) {
            QueryString = URL.substring(URL.lastIndexOf("?") + 1, URL.length);
            tmpArr = QueryString.split("&");// 分离参数
            for (i = 0; i <= tmpArr.length; i++) {
                try { eval(tmpArr[i]); }
                catch (e) {
                    var re = new RegExp("(.*)=(.*)", "ig");
                    re.exec(tmpArr[i]);
                    try {
                        name=RegExp.$2;
                    }
                    catch (e) { }
                }
            }
        }
        else {
            name = "";
        }
       // alert(name);
        $("#TitleText").text(name);
        str='php/getPictureList.php?name='+name;
        $('#table_toggle').bootstrapTable({
            url: str
        });
    }
);