/* tangbin - http://www.planeArt.cn - MIT Licensed */
(function($) {
    // tipWrap: 	提示消息的容器
    // maxNumber: 	最大输入字符
    $.fn.artTxtCount = function(tipWrap, maxNumber) {
        var countClass = 'js_txtCount', // 定义内部容器的CSS类名
                fullClass = 'js_txtFull', // 定义超出字符的CSS类名
                disabledClass = 'disabled';		// 定义不可用提交按钮CSS类名

        // 统计字数
        var count = function() {
            var btn = $(this).closest('form').find(':submit'),
                    val = dataLength($(this).val()),
                    // 是否禁用提交按钮
                    disabled = {
                on: function() {
                    btn.removeAttr('disabled').removeClass(disabledClass);
                },
                off: function() {
                    btn.attr('disabled', 'disabled').addClass(disabledClass);
                }
            };

            if (val == 0)
                disabled.off();
            if (val <= maxNumber) {
                if (val > 0)
                    disabled.on();
                tipWrap.html('<span class="' + countClass + '">\u8FD8\u80FD\u8F93\u5165 <strong>' + (maxNumber - val) + '</strong> \u4e2a\u5b57\u7b26</span>');
            } else {
                disabled.off();
                tipWrap.html('<span class="' + countClass + ' ' + fullClass + '">\u5DF2\u7ECF\u8D85\u51FA <strong>' + (val - maxNumber) + '</strong> \u4e2a\u5b57\u7b26</span>');
            }
            ;
        };
        $(this).bind('keyup change', count);

        return this;
    };

    function dataLength(fData)
    {
        var intLength = 0
        for (var i = 0; i < fData.length; i++)
        {
            if ((fData.charCodeAt(i) < 0) || (fData.charCodeAt(i) > 255))
                intLength = intLength + 2
            else
                intLength = intLength + 1
        }
        return intLength;
    }
})(jQuery);