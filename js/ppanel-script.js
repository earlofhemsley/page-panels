/* Custom JS for ppanels */

if(jQuery){
    var frameTiles = function(){
        var height = 100;
        $('.ppanel-container li').delay(1000).each(function(){
            height = $(this).height() > height ? $(this).height() : height;
        });
        $('.ppanel-container li').css('height', height);
    };

    $(window).load(frameTiles);
}
