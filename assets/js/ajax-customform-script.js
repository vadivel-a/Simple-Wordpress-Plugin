jQuery(document).ready(function($) {
  // Perform AJAX login on form submit
  $('form#mw_customform').on('submit', function(e) {
    $('form#mw_customform p.status').show().text(ajax_customform_object.loadingmessage);
    $.ajax({
      type: 'POST',
      dataType: 'json',
      url: ajax_customform_object.ajaxurl,
      data: {
        'action': 'ajaxmwcustom_form',
        'name': $('form#mw_customform #name').val(),
        'email': $('form#mw_customform #email').val(),
        'phone': $('form#mw_customform #phone').val(),
        'security': $('form#mw_customform #security').val()
      },
      success: function(data) {
        $('form#mw_customform p.status').text(data.message);
        if (data.loggedin == true) {
          document.location.href = ajax_customform_object.redirecturl;
        }
      }
    });
    e.preventDefault();
  });
});