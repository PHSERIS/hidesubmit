<?php

/* 
 * © 2016 Partners HealthCare System, Inc. All Rights Reserved.
 * @author Bob Gorczyca
 * 
 * This is a hook utility function processor that will hide the button with a name 
 * of 'submit-btn-saverecord' on a survey page where at least one field has an Annotation 
 * Field containing the custom action tag @HIDESUBMITBUTTON
 * 
 * Both the "Submit" and "Next page" buttons have this name.
 * 
 * If the submit button was the only button in the row, then the row itself will 
 * be hidden; otherwise only the button is hidden.
 * 
 * Requires a list of REDCap Project instrument fields for which the 
 * @HIDESUBMITBUTTON tag is present.
 * 
 */

?>
<script type='text/javascript'>
  
/**
 * Determine if a field, with the field_name passed in via $startup_vars, is
 * visible.  If so, hide the submit button; otherwise show it.
 * 
 * This visibiity check is to support branching; that is, if the branch that 
 * contains the tag is visbile, hide the submit button
 * 
 * @returns {undefined}
 */
var processtags = function(on_load){
  
  var hide = false;
  
  // Get the fields with a @HIDESUBMITBUTTON tag
	var hide_submit_fields = <?php print json_encode($startup_vars) ?>;
	
  // The @HIDESUBMITBUTTON tag can be present in any field.
  // field_name is the REDCap field variable name
  // params.params is the parameter associated to the action tag
	$.each(hide_submit_fields, function(field_name, params) {
        
    // Set hide if ANY field containing the tag is being displayed, or if this
    // is a page load and 'default' is the tag parameter passed in
    if ( ( $('tr[sq_id="' + field_name + '"]').css('display') !== 'none' ) ||
         ( on_load && (params.params == 'default') )) {
      hide = true;
    }
  });
    
  // Find submit button's parent row with class surveysubmit
  var $survey_submit_button = $( "button[name*=\'submit-btn-saverecord\']" );
  var $survey_submit_row = $survey_submit_button.closest('tr.surveysubmit');
  
  // If the submit button is the only item of interest in the row, hide the row
  if ($survey_submit_row.find('button').length === 1){
    (hide) ? $survey_submit_row.hide() : $survey_submit_row.show();  
  } else {  // Otherwise, hide only the button itself
    (hide) ? $survey_submit_button.hide() : $survey_submit_button.show();
  }
  return;
};
    
    
// Process tags on document.ready and on any change event
$(document).ready(function() {
  
  // Whenever a change occurs, a field may have changed visibility,
  // so process any tagged fields
  $('body').on('change', function(e){
    
    // Process tags after the default processing for the change event has occurred,
    // which could, for example change the field visibility we want to test.
    // Setting timeout too low may result in default click processing not having enough time;
    // too high and the submit button will be visible to the user too long.
    setTimeout(function(){ processtags(); }, 100);
  });
  
  // Process any tagged fields
  processtags(true);

});

</script>