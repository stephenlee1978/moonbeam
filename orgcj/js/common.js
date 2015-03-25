/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(function() {
    $('.checkAll').live('click', function() {
        
            if($(this).attr('checked')){
                $('.checkItem').attr('checked', true);
            }else{
                $('.checkItem').attr('checked', false);
            }
      });
      
      $('.checkItem').live('click', function() {
            if(getCheckItemCount() == $(".checkItem").length){
                $('.checkAll').attr('checked', true);
            }else{
                $('.checkAll').attr('checked', false);
            }
      });
      
});

//得到所选择的行
function getCheckbox() {
    var data = new Array();
    $("input:checkbox[name='selid[]']").each(function() {
        if ($(this).attr("checked")) {
            data.push($(this).val());
        }
    });
    
    return data;
}

function getCheckItemCount(){
    var count = 0;
    $(".checkItem").each(function(i){

        if($(this).attr("checked")) {
          count++;
        }

    });
    return count;
}


