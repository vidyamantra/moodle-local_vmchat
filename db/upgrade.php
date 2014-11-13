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
 * vmchat module upgrade code
 *
 * @package    local
 * @subpackage vmchat
 * @copyright  2014 Pinky Sharma
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * vmchat module upgrade task
 *
 * @param int $oldversion the version we are upgrading from
 * @return bool always true
 */
function xmldb_local_vmchat_upgrade($oldversion) {
    global $CFG, $DB;

    $dbman = $DB->get_manager();

    // Moodle v1.0.1 release upgrade line
    // Put any upgrade step following this.
    if ($oldversion < 2014063000) {
        set_config('additionalhtmlhead', '');
        set_config('additionalhtmlfooter', '');
        unset_config('enablevmchat', 'local_vmchat');
        // Vmchat savepoint reached.
        upgrade_plugin_savepoint(true, 2014063000, 'local', 'vmchat');
    }

    // Vmchat v1.1.2 release upgrade line
    if ($oldversion < 2014092300) {
        // Remove header part.
        $additionalhtmlhead = preg_replace("/<!-- fcStart -->.*<!-- fcEnd -->/", "", $CFG->additionalhtmlhead);
        set_config('additionalhtmlhead', $additionalhtmlhead);

        // Update header part.
        $fstring = '<!-- fcStart --><script language = "JavaScript"> var wwwroot="'.
        $CFG->wwwroot.'/";</script><script type="text/javascript" src = "'.
        $CFG->wwwroot.'/local/vmchat/include.js"></script><!-- fcEnd -->';
        $DB->execute('UPDATE {config} set value = concat(value, :fstring) WHERE  name = :hname',
                array( 'fstring' => $fstring, 'hname' => 'additionalhtmlhead'));

        // Vmchat savepoint reached.
        upgrade_plugin_savepoint(true, 2014092300, 'local', 'vmchat');
    }

    // Vmchat v1.2.1 release upgrade line
    if ($oldversion < 2014111300) {
    	if(get_config('local_vmchat', 'enablevmchat')){
        	// Remove header part.
        	$additionalhtmlhead = preg_replace("/<!-- fcStart -->.*<!-- fcEnd -->/", "", $CFG->additionalhtmlhead);
        	set_config('additionalhtmlhead', $additionalhtmlhead);

        	// Update header part.
        	$fstring = '<!-- fcStart --><script language = "JavaScript"> var wwwroot="'.$CFG->wwwroot.'/";</script><script type="text/javascript" src = "'.$CFG->wwwroot.'/local/vmchat/bundle/chat/bundle/jquery/jquery-1.11.0.min.js"></script><script type="text/javascript">$=jQuery.noConflict( );</script><script type="text/javascript" src = "'.$CFG->wwwroot.'/local/vmchat/bundle/chat/bundle/jquery/jquery-ui.min.js"></script><script type="text/javascript" src = "'.$CFG->wwwroot.'/local/vmchat/auth.php"></script><script type="text/javascript" src = "'.$CFG->wwwroot.'/local/vmchat/bundle/chat/bundle/io/build/iolib.min.js"></script><script type="text/javascript" src = "'.$CFG->wwwroot.'/local/vmchat/bundle/chat/build/chat.min.js"></script><script type="text/javascript" src = "'.$CFG->wwwroot.'/local/vmchat/index.js"></script><!-- fcEnd -->';

        	$DB->execute('UPDATE {config} set value = concat(value, :fstring) WHERE  name = :hname',
                array( 'fstring' => $fstring, 'hname' => 'additionalhtmlhead'));
		}
        // Vmchat savepoint reached.
        upgrade_plugin_savepoint(true, 2014111300, 'local', 'vmchat');
    }
    return true;

}
