<?php
// This file is part of Moodle - http://vidyamantra.com/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * vmchat footer chat module
 *
 * @package    local
 * @subpackage vmchat
 * @copyright  2014 Pinky Sharma
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once('form.php');

require_login();
require_capability('moodle/site:config', context_system::instance());
admin_externalpage_setup('vmchat');

$PAGE->set_url(new moodle_url('/local/vmchat/index.php'));

$value =0;
$value = get_config('local_vmchat','enablevmchat');
$mform = new local_vmchat_form(null, array('enablevmchat'=>$value));

if ($mform->is_cancelled()) {
	//nothing
} else if ($fromform = $mform->get_data()) {
	
	if(!empty($fromform->enablevmchat)) 
		set_config('enablevmchat', $fromform->enablevmchat, 'local_vmchat');
	
	preg_match("/<!-- fcStart -->.*<!-- fcEnd -->/",$CFG->additionalhtmlhead,$m);// check header already exist
	//print_r($m);
	if(!empty($fromform->enablevmchat) && empty($m)){
		
	// footer part
    $sql="UPDATE {config} set value=concat(value, '<div id=\"stickycontainer\"></div>') where name='additionalhtmlfooter'";
    $DB->execute($sql);
    
    //header part
    $fstring = '<!-- fcStart --><script language="JavaScript"> var wwwroot="'.$CFG->wwwroot.'/";</script><script type="text/javascript" src="'.$CFG->wwwroot.'/local/vmchat/bundle/chat/bundle/jquery/jquery-1.11.0.min.js"></script><script type="text/javascript" src="'.$CFG->wwwroot.'/local/vmchat/bundle/chat/bundle/jquery/jquery-ui.min.js"></script><script type="text/javascript" src="'.$CFG->wwwroot.'/local/vmchat/index.js"></script><!-- fcEnd -->';
    $DB->execute('UPDATE {config} set value = concat(value,:fstring) WHERE  name=:hname', array( 'fstring' => $fstring,'hname' => 'additionalhtmlhead'));

    unset($fromform->enablevmchat);
	}
	
	
	if(empty($fromform->enablevmchat)){	
		//remove footer div
    $sql="UPDATE {config} set value = replace(value, '<div id=\"stickycontainer\"></div>','') where value LIKE '%<div id=\"stickycontainer\"></div>%' and name='additionalhtmlfooter'";    
    $DB->execute($sql);
    
    
    // remove header html  
    $additionalhtmlhead = preg_replace("/<!-- fcStart -->.*<!-- fcEnd -->/", "", $CFG->additionalhtmlhead);
    $DB->execute('UPDATE {config} set value = "'.$additionalhtmlhead.'" WHERE name=:hname', array('hname' => 'additionalhtmlhead'));
    

	}
}

echo $OUTPUT->header();

// api key exist in db
if(!get_config('local_getkey','keyvalue')){
	echo $OUTPUT->error_text("Visit Administration Block > <a href='".$CFG->wwwroot."/local/getkey/index.php'>Get key </a> and register for API key.");	
	//echo $OUTPUT->container('A message of some kind', 'important', 'notice');
}

$mform->display();
echo $OUTPUT->footer();


