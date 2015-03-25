/* 
 * siderbar control
 * by stephen.leon 2015
 */

jQuery(function() {
        $('#siderctrl').live('click', function() {
            if($('#wrapper').hasClass('toggled')){
                $('#wrapper').removeClass('toggled');
                if($('.subnav-fixed')) $('.subnav-fixed').addClass('untoggled');
            }else{
                $('#wrapper').addClass('toggled');
                if($('.subnav-fixed')) $('.subnav-fixed').removeClass('untoggled');
            }
        });
});
