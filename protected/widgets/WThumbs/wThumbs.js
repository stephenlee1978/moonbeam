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
                'id': 'wThumbs',
            }, options || {});

            return this.each(function() {
                var $supply = $(this);
                var id = $supply.attr('id');
                supplySettings[id] = settings;

                $('#'+id).find('.thumbs img').on('click', function(event) {
                    $(this).parents('li').addClass("tb-selected").siblings().removeClass("tb-selected");
                    var img = $(this).attr("src");
                    event.preventDefault();
                    var decImg = $("<img>");
                    decImg.attr("src", img);
                    decImg.attr("style", "width:336px;height:450px");
                    $('#'+id).find('.product_pic').html("").append(decImg);
                    return false;
                });

            });
        },
        
    };

    $.fn.thumbs = function(method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.yiiGridView');
            return false;
        }
    };
})(jQuery);