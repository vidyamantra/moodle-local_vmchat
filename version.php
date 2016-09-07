<?php
// This file is part of VidyaMantra - http://vidyamantra.com/
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

defined('MOODLE_INTERNAL') || die();

$plugin->component = 'local_vmchat';
$plugin->version = 2016090700;
$plugin->release = '2.1 (Build: 2016090700)';
$plugin->requires = 2015111600; // Moodle 3.0 or later.
$plugin->maturity = MATURITY_STABLE;
$plugin->dependencies = array('local_getkey' => 2014081300);
