/* Custom JS for ppanels */
if(jQuery){
    jQuery(document).ready(function($){
        var frameTiles = function(){
            var height = 0;
            $('.ppanel-container .ppanel-tile-image img').each(function(){
                height = $(this).height() + 6 > height ? $(this).height() + 6 : height;
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
            var modal = $($(this).data('show'));

            modal.fadeIn(400, function(){
                if(($(window).height()-50) < modal.outerHeight()){
                    modal.css('height', $(window).height() - 100);
                    modal.css('overflow', 'scroll');
                }
            });
        });

        $('span.ppanel-xish').on('click', function(){
            $('body').removeClass('ppanel-modal-open');
            $('.ppanel-modal-backdrop').css('display','none');
            $('.ppanel-modal-wrapper').fadeOut(400, function(){
                $(this).css('height','');
                $(this).css('overflow','');
            });
        });
    });
}
