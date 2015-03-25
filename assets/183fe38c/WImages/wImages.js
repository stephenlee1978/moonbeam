/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
var actionurl='';

//得到图片路径
function getObjectURL(file) {
    var url = null;
    if (window.createObjectURL != undefined) { // basic
        url = window.createObjectURL(file);
    } else if (window.URL != undefined) { // mozilla(firefox)
        url = window.URL.createObjectURL(file);
    } else if (window.webkitURL != undefined) { // webkit or chrome
        url = window.webkitURL.createObjectURL(file);
    }
    return url;
}

//生成图片
function makefile(btn, obj) {
    var objUrl;
    if (navigator.appName == "Microsoft Internet Explorer") {
        objUrl = btn.val();
    } else {
        objUrl = getObjectURL(obj.files[0]);
    }

    console.log("objUrl = " + objUrl);
    if (objUrl) {
        if (btn.parent().find("img").length === 0)
            btn.before('<img src="' + objUrl + '"/><div class="remove" onClick="delpic(this)"></div>');
        else
            btn.parent().find("img").attr("src", objUrl);
        
        btn.parent().find(".file").attr("style", 'left:9999px !important;');
        //btn.parent().find(".file").attr("disabled", 'disabled');
    }
    //添加file
    //$("#addimgbtn").prepend("<input type='file' id='file" + index + "' style='z-index:" + index + "' class='file' name='temp[fdFile][]' />");
}

//删除图片
function delpic(obj) {

    var id = jQuery(obj).parent().find("img").attr("data");
    
    if (id === undefined)
        remove(jQuery(obj).parent());
    else
        ajaxdelpic(id, jQuery(obj).parent());
}

function remove(pobj) {
    if (pobj !== undefined) {
        pobj.find("img").remove();
        pobj.find(".remove").remove();
        var name = pobj.find('.file').attr('name');
        pobj.find('.file').remove();
       pobj.prepend("<input class='file' type='file' name='"+name+"' ></input>");
    }
}

//删除服务器图片
function ajaxdelpic(id, obj) {
    var ret;
    ret = window.confirm("确定要删除么");
    if (ret == false) {
        return false;
    }

    var data = {'file':id};
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: actionurl,
        async:true,
        data: data,
        success: function(msg) {
            if(msg.sucess == true)
                remove(obj);
        }, //如果调用php成功
        error: function(msg) {
            window.alert(msg.status + '错误');
        },
        timeout: 5000
    });
}

jQuery(function($) {
    //点击添加按钮
    $(".file").live('change', function() {
        var btn = $(this);
        makefile(btn, this);
    });
});



