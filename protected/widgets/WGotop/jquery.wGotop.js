/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
(function($) {
    var methods = {
        init: function(options) {
            var settings = $.extend({
                'id': 'gotop',
            }, options || {});

            return this.each(function() {
                var $supply = $(this);
                var id = $supply.attr('id');

                $(window).scroll(function() {
                    if ($(window).scrollTop() > 100) {
                        $('#' + id).fadeIn(1500);
                    } else {
                        $('#' + id).fadeOut(1500);
                    }
                    
                })

                $('#' + id).on('click', function() {
                    $('body,html').animate({scrollTop: 0}, 500);
                    return false;
                });

            });
        },
    };

    $.fn.gotop = function(method) {
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