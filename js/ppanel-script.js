/* Custom JS for ppanels */
if(jQuery){
    var frameTiles = function(){
        var height = 0;
        $('.ppanel-container .ppanel-tile-image img').each(function(){
            height = $(this).height() + 8 > height ? $(this).height() + 8 : height;
        });
        $('.ppanel-container .ppanel-tile-image').css('height', height);
    };

    $(window).load(frameTiles);

    $(window).on('resize', function(){
        frameTiles();
    });

    $('img.modalable').on('click', function(){
        $('body').addClass('ppanel-modal-open');
        $('.ppanel-modal-backdrop').css('display','block');
        $($(this).data('show')).fadeIn();
    });

    $('span.ppanel-xish').on('click', function(){
        $('body').removeClass('ppanel-modal-open');
        $('.ppanel-modal-backdrop').css('display','none');
        $('.ppanel-modal-wrapper').fadeOut();
    });

}
