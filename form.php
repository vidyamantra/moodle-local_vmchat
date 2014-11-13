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
 * vmchat module
 *
 * @package    local
 * @subpackage vmchat
 * @copyright  2014 Pinky Sharma
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once($CFG->dirroot.'/lib/formslib.php');

class local_vmchat_form extends moodleform {
    function definition() {
        $mform =& $this->_form;
        $mform->addElement('checkbox', 'enablevmchat', get_string('enablevmchat', 'local_vmchat'));
        $mform->setDefault('enablevmchat', $this->_customdata['enablevmchat']);

        $mform->addElement('select', 'jqhandle', get_string('jqueryinclude', 'local_vmchat'),array(0 => get_string('autoresolve', 'local_vmchat'), 1 => get_string('dontinclude', 'local_vmchat')));
        $mform->addHelpButton('jqhandle', 'jqueryinclude', 'local_vmchat');
        $mform->setDefault('jqhandle', $this->_customdata['jqhandle']);

        $this->add_action_buttons($cancel = false);
    }
}