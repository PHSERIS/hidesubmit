<?php
	
/*
 * © 2016 Partners HealthCare System, Inc. All Rights Reserved.
 * @author Bob Gorczyca
 * 
 * This is a hook utility function that hides the submit button on surveys
 * 
 * The trigger is similar to REDCap "action tags".
 * @HIDESUBMITBUTTON would hide the submit button for an instrument in which this 
 * tag exists
 *
**/

// "Action tag" for this hook
$tag = '@HIDESUBMITBUTTON';

// init_tags() returns an array of all project instrument fields in which some tag 
// appears. Each array contains the variable Name of the field, and an array of
// elements_index and params
$tag_functions = init_tags(__FILE__);
if (empty($tag_functions)) return;

// If this particular tag is not used on this survey/form, no need to load
if (!isset($tag_functions[$tag])) return;

// Ok, tag is used in this project - process it
hook_log ("Running $tag for PID: $project_id, INSTR: $instrument", "DEBUG");

// Step 1 - Create array of fields containing the tag, by variable name
$startup_vars = $tag_functions[$tag];

// Include processing file
include_once 'hidesubmit_js.php';




