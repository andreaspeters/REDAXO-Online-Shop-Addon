$(document).ready(function() {		
  $("#table").tablesorter({widthFixed: true, widgets: ['zebra']});
  $('#date').datepicker({ dateFormat: 'yy-mm-dd' });
  $('#to').timepicker();
  $('#from').timepicker();
  $('#scheduledend').datepicker({ dateFormat: 'yy-mm-dd' });
  $('#scheduledbegin').datepicker({ dateFormat: 'yy-mm-dd' });

  $('#viewhistory').click(function() {
    $('#viewhistory').fadeOut();	  	 
    $('#hidehistory').fadeIn();	   	 
    $('#historybox').fadeIn('slow', function() {});
  });
  $('#hidehistory').click(function() {
    $('#hidehistory').fadeOut();	   	 
    $('#viewhistory').fadeIn();
    $('#historybox').fadeOut('slow', function() {});	   
  });
});	

