( function($) {
  $(document).ready(function() {
    $('nav.navbar ul#main_menu li.first_level').on('mouseover', function() {
      $(this).find('ul.submenu').fadeIn('fast');
      $(this).find('a.first_level_link').trigger('hover');
    });

    $('nav.navbar ul#main_menu li.first_level').on('mouseleave', function() {
      $(this).find('ul.submenu').fadeOut('fast');
    });
  })
} )( jQuery );