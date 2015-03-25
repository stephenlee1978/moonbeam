/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
(function($) {
    var supplySettings = [];

    var methods = {
        init: function(options) {
            var settings = $.extend({
                'id': 'wSellercats',
            }, options || {});

            return this.each(function() {
                var index = $(this).attr('id');
                 supplySettings[index] = settings;
                 
                var id = settings.id;
                
                //点击店铺分类
                $(id).find('.open_cats').live('click', function() {
                    
                    $(this).hide();
                    $(".cat_divs").show();

                    return true;
                });

                //保存卖家店铺列表
                $(id).find(".save_cats_btn").live('click', function() {
                    var titles = [];
                    var ids = [];
                    $(".taobaoSCDiv :checkbox:checked").each(function() {
                        titles.push($(this).attr("title"));
                        ids.push($(this).attr("value"));
                    });
                    $("#ProductProperty_sellercats").attr("value", ids.join(","));
                    $("#ProductProperty_sellerPath").attr("value", titles.join(","));

                    $(".open_cats").show();
                    $(".cat_divs").hide();
                });

                //关闭卖家店铺列表
                $(id).find(".close_cats_btn").live('click', function() {
                    $(".open_cats").show();
                    $(".cat_divs").hide();
                });
            });
        },
    };



    $.fn.sellercats = function(method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.yiiGridView');
            return false;
        }
    };

    $.fn.sellercats.settings = supplySettings;

})(jQuery);