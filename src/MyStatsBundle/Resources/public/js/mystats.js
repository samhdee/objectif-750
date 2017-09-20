( function($) {
  $(document).ready(function() {
    $.each($('#words_calendar .calendar_item.somewords'), function() {
      var progression = $(this).attr('data-progression');
      $(this).find('.progression_status').css('height', progression+'%');
    });
  })
} )( jQuery );