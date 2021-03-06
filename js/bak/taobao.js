/*
 * v1.0 2014-01-18 stephen
 */
var itempopsurl = '';

$(function() {
    //上传编辑商品
    $(".upload_btn").click(function()
    {  
        $("#propForm input[name=method]").val("upload");
        $("#propForm").validate();
        $("#propForm").submit();
    });

    //获取标准分类
    $(".itemcats").click(function() {
        $(this).hide();
        $("#ProductProperty_pidPath").attr('value', '');
        $("#ProductProperty_itemCats").attr('value', '');
        getItemCats(0);
    });

    //获取原标准分类
    $(".olditemcats").click(function() {
        if ($(".taobaoSCDiv").html() == "") {
            $("input[name=itemcats]").hide();
        }
        $(".props").find(":input").prop("disabled", true);
        $("#propForm").submit();
    });

    $("select[name*=prop]").change(checkOption);
});

function checkOption() {

    var select = $(this);

    var option = $(this).children("option:selected");
    var child_template = $(select).attr("child_template");

    $(select).nextAll(":input,label").remove();
    $(select).nextAll("input:text").hide().prop("disabled", true);

    var pid = $(select).attr("pid");

    //检查是否有下级子select
    if ($(option).attr("is_parent")) {
        var pid_vids = new Array();
        $(select).prevAll("select").each(function() {
            var pid = $(this).attr("pid");
            var vid = $(this).children("option:selected").val();
            var pid_vid = pid + ":" + vid;

            pid_vids.push(pid_vid);
        });

        var vid = $(option).val();
        var pid_vid = pid + ":" + vid;
        pid_vids.push(pid_vid);

        var pid_vid_joined = pid_vids.join(";");

        var taobaoCatId = $("#ProductProperty_itemCats").val();
        var url = itempopsurl + "?cid=" + taobaoCatId + "&child_path=" + pid_vid_joined;

        $.getJSON(url, function(data) {
            props = data['item_props']['item_prop']
            for (var i in props) {
                var ItemProp = props[i];
                var input = genInput(ItemProp);

                if (child_template) {
                    $(input).attr("parent_pid", pid);
                    $(input).attr("parent_value", $(option).text());
                    $(input).attr("parent_template", child_template);
                }

                $(select).after(input);

                if (ItemProp.name) {
                    var label = $("<label>").text(ItemProp.name + ":");
                    $(select).after(label);
                }
            }
        });

        //检查是否有自定义选项
    } else if ($(option).val() == "0") {

        var parent_pid = $(select).attr("parent_pid");
        var parent_template = $(select).attr("parent_template");
        var parent_value = $(select).attr("parent_value");
        if (parent_pid) {
            var parent_input = $("<input type=hidden name=input[" + parent_pid + "]>").val(parent_value);
            var child_input = $("<input type=text name=childs[" + parent_pid + "]>");
            var child_hidden = $("<input type=hidden name=templates[" + parent_pid + "]>").val(parent_template);
            $(select).parent("td").append(parent_input).append(child_input).append(child_hidden);

        } else {
            $(select).after("<input type=text name=input[" + pid + "]>");
        }


        if (child_template) {
            var child_label = $("<label>").text(child_template + ":");
            var child_input = $("<input type=text name=childs[" + pid + "]>");
            var child_hidden = $("<input type=hidden name=templates[" + pid + "]>").val(child_template);
            $(select).parent("td").append(child_label).append(child_input).append(child_hidden);
        }

        if ($(select).hasClass("required"))
            $(select).nextAll("input").addClass("required");
    }

}

function genInput(ItemProp) {
    if (ItemProp.is_enum_prop) {
        if (ItemProp.multi) {	//多选 
            var input = $("<span>");
            var props = ItemProp.prop_values.prop_value;
            for (var j in props) {
                var chk = $("<input type=checkbox>").attr("name", "prop[]").val(ItemProp.pid + ":" + props[j].vid);
                var lab = $("<span>").text(props[j].name);
                $(input).append(chk).append(lab);
            }
        } else {	//单选
            var input = $("<select>").attr("name", "prop[" + ItemProp.pid + "]").attr("pid", ItemProp.pid).change(checkOption);
            var opt = $("<option>").val("").text("--请选择--");
            $(input).append(opt);

            if (ItemProp.prop_values) {
                var props = ItemProp.prop_values.prop_value;
                for (var j in props) {
                    var opt = $("<option>").val(props[j].vid).text(props[j].name);
                    if (props[j].is_parent) {
                        $(opt).attr("is_parent", 1);
                    }
                    $(input).append(opt);
                }
            }

            //可自定义属性
            if (ItemProp.is_input_prop) {
                var opt = $("<option>").val("0").text("自定义");
                $(input).append(opt);
            }

            //自定义属性的模板
            if (ItemProp.child_template) {
                $(input).attr("child_template", ItemProp.child_template);
            }
        }
    } else {
        var input = $("<input type=text>").attr("name", "input[" + ItemProp.pid + "]");
    }

    if (ItemProp.is_sale_prop)
        $(input).addClass("sale");
    if (ItemProp.is_color_prop)
        $(input).addClass("color");
    if (ItemProp.is_allow_alias)
        $(input).addClass("alias");
    if (ItemProp.must)
        $(input).addClass("required");

    return input;
}

//获取淘宝后台标准类目
function getItemCats(pid, sid) {
    var ajaxurl = $(".itemcats").attr('url');
    var productID = $("#Product_id").val();

    var sortCid = [50022517, 50005700, 50011397, 50010788, 1801, 50008165, 50006842, 50010404, 50013864, 1625, 50011699, 50010728, 50010388, 50011740, 50006843, 30, 16];
    var data = {'pid': pid, 'product': productID};
    $.ajax({
        url: ajaxurl,
        dataType: 'json',
        data: data,
        async: false,
        success: function(data) {
            if (data) {
                data.sort(function(a, b) {
                    return $.inArray(b.cid, sortCid) - $.inArray(a.cid, sortCid);
                });

                var select = $("<select name=catTaobao[]>");
                $(select).append("<option value=0>-请选择-</option>");
                for (i in data) {
                    var option = $("<option>").val(data[i]['cid']).text(data[i]['name']);
                    if (data[i].is_parent)
                        $(option).attr("is_parent", 1);
                    if (sid == data[i]['cid'])
                        $(option).attr("selected", true);
                    $(select).append(option);
                }

                $(select).change(function() {
                    getTaobaoChildrenCat();
                });

                $("input:hidden[name='catTaobao[]']").remove();
                $(".taobaoCatDiv").append(select);
            }
        }
    });
}

//获取淘宝标准分类（子分类）
function getTaobaoChildrenCat() {
    var select = $("select[name='catTaobao[]']:focus");
    var option = $(select).children("option:selected");
    $(select).nextAll("select[name='catTaobao[]']").remove();
    var pid = $(select).val();
    if ($(option).attr("is_parent") && pid != 0) {
        $("#ProductProperty_pidPath").attr('value', $(option).text());
        $("#ProductProperty_itemCats").attr('value', '');
        getItemCats(pid);
    } else {
        $("#ProductProperty_pidPath").attr('value', $("#ProductProperty_pidPath").val() + '-' + $(option).text());
        $("#ProductProperty_itemCats").attr('value', pid);
        returnTaobaoCat(pid);
    }
}

function returnTaobaoCat(pid) {
    $(".props").find(":input").prop("disabled", true);
    $("#propForm").submit();
}

function getChildPropValue(child_path, title) {

    var cid = $("#this_pid").attr("value");

    var data = {'cid': cid, 'child_path': child_path};

    $.ajax({
        url: itempopsurl,
        dataType: 'json',
        data: data,
        async: false,
        success: function(data) {
            createChildPropsForm(data, title);
        }
    });
}