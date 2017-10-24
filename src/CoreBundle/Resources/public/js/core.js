( function($) {
  $(document).ready(function() {
    $('nav.navbar ul#main_menu li.first_level').on('mouseover', function() {
      $(this).find('ul.submenu').fadeIn('fast');
      $(this).find('a.first_level')
    });

    $('nav.navbar ul#main_menu li.first_level').on('mouseleave', function() {
      $(this).find('ul.submenu').fadeOut('fast');
    });

    // $('.tool_tip').on('click', function() {
    //   $('#dialog').html($(this).attr('title'));

    //   $('#dialog').dialog({
    //     title: "Informations",
    //     modal: true,
    //     resizable: false,
    //     draggable: false,
    //     autoOpen: false,
    //     show: 'fade',
    //     hide: 'fade',
    //     width: 300,
    //     height: 150,
    //     open: function() {
    //       $('#dialog').parent().addClass('custom-dialog');
    //       $('.ui-widget-overlay', this).hide().fadeIn();
    //     },
    //     close: function() {
    //       $('.ui-widget-overlay').fadeOut();
    //       $('#dialog').parent().removeClass('custom-dialog');
    //     }
    //   });

    // $("#dialog").parent().css({position: "fixed"}).end().dialog('open');
    // });
  })
} )( jQuery );