/* Custom JS for ppanels */
if(jQuery){
    var frameTiles = function(){
        var height = 0;
        $('.ppanel-container .ppanel-tile-image').each(function(){
            height = $(this).height() + 8 > height ? $(this).height() + 8 : height;
        });
        $('.ppanel-container .ppanel-tile-image').css('height', height);
    };

    $(window).load(frameTiles);
}
