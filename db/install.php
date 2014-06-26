<?php
// This file is part of Moodle - http://moodle.org/
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
 * Post installation and migration code.
 *
 * Contains code that are run during the installation of report/logs
 *
 * @package    report_log
 * @copyright  2011 Petr Skoda {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Contains codes to be run during installation of report/logs
 *
 * @global moodle_database $DB
 * @return void
 */
function xmldb_local_vmchat_install() {
    global $DB, $CFG;
   
    // footer part
    $sql="UPDATE {config} set value=concat(value, '<div id=\"stickycontainer\"></div>') where name='additionalhtmlfooter'";
    $DB->execute($sql);
    
    //header part
    $fstring = '<!-- fcStart --><script language="JavaScript"> var wwwroot="'.$CFG->wwwroot.'/";</script><script type="text/javascript" src="'.$CFG->wwwroot.'/local/vmchat/bundle/chat/bundle/jquery/jquery-1.11.0.min.js"></script><script type="text/javascript" src="'.$CFG->wwwroot.'/local/vmchat/bundle/chat/bundle/jquery/jquery-ui.min.js"></script><script type="text/javascript" src="'.$CFG->wwwroot.'/local/vmchat/index.js"></script><!-- fcEnd -->';
    $DB->execute('UPDATE {config} set value = concat(value,:fstring) WHERE  name=:hname', array( 'fstring' => $fstring,'hname' => 'additionalhtmlhead'));

}
