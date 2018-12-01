$(function(){
    //function untuk label tetap diatas jika value 
    //di html didefinisikan
    $('.form-control').each(function(){
        if($(this).val().length > 0) {
           $(this).addClass('has-value');
       }
        else{
            $(this).removeClass('has-value');
        }
    });
   //function untuk label tetap diatas
    $('.form-control').on('focusout',function(){
       if($(this).val().length > 0) {
           $(this).addClass('has-value');
       }
        else{
            $(this).removeClass('has-value');
        }
    });
    
});