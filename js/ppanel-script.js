/* Custom JS for ppanels */

if(jQuery){
    var frameTiles = function(){
        var height = 0;
        $('.ppanel-container li').each(function(){
            height = $(this).height() > height ? $(this).height() : height;
        });
        $('.ppanel-container li').css('height', height);
    };

    $(window).load(frameTiles);
}
