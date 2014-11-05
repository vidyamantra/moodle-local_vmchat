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

header("content-type: application/x-javascript");
require_once('../../config.php'); // Moodle config.
global $USER, $CFG;

if (!$USER->id) {
    setcookie('auth_user', null, -1, '/');
    setcookie('auth_pass', null, -1, '/');
    setcookie('path', null, -1, '/');
    setcookie('tk', null, -1, '/');
    echo "exit;";
}
function vmchat_curl_request($url, $postdata) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, 'content-type: text/plain;');
    curl_setopt($ch, CURLOPT_TRANSFERTEXT, 0);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXY, false);
    curl_setopt($ch, CURLOPT_SSLVERSION, 1);
    $result = @curl_exec($ch);
    if ($result === false) {
        echo 'Curl error: ' . curl_error($ch);
        exit;
    }
    curl_close($ch);
    return $result;
}

if (!isset($_COOKIE['auth_user']) || !isset($_COOKIE['auth_pass']) || !isset($_COOKIE['path'])) {

    $licen = get_config('local_getkey', 'keyvalue');
    if(!$licen){
        set_config('enablevmchat', 0, 'local_vmchat');
        $additionalhtmlhead = preg_replace("/<!-- fcStart -->.*<!-- fcEnd -->/", "", $CFG->additionalhtmlhead);
        $DB->execute('UPDATE {config} set value = "'.$additionalhtmlhead.'" WHERE name =:hname',
        array('hname' => 'additionalhtmlhead'));
        purge_all_caches();
    }
    // Send auth detail to server.
    $authusername = substr(str_shuffle(md5(microtime())), 0, 12);
    $authpassword = substr(str_shuffle(md5(microtime())), 0, 12);
    $postdata = array('authuser' => $authusername, 'authpass' => $authpassword, 'licensekey' => $licen);
    $postdata = json_encode($postdata);

    $rid = vmchat_curl_request("https://c.vidya.io", $postdata); // REMOVE HTTP.

    if (empty($rid) or strlen($rid) > 32) {
        echo "Chat server is unavailable!";
        echo "alert('Chat server is unavailable!');";
        exit;
    }
    if ($rid == 'Rejected - Key Not Active') {
        echo "alert('VmChat license key is not valid');exit;";
        exit;
    }

    setcookie('auth_user', $authusername, 0, '/');
    setcookie('auth_pass', $authpassword, 0, '/');
    setcookie('path', $rid, 0, '/');

    $result = get_config('local_getkey', 'tokencode');
    setcookie('tk', $result, 0, '/');
}

if ($USER->id) {
    // Moodle user pic url.
    $userpicture = moodle_url::make_pluginfile_url(context_user::instance($USER->id)->id, 'user', 'icon', null, '/', 'f2');
    $src = $userpicture->out(false);
    echo "imageurl ='".$src."';";
    echo "sid ='".$USER->sesskey."';";

    echo "auth_user ='".$_COOKIE['auth_user']."';";
    echo "auth_pass ='".$_COOKIE['auth_pass']."';";
    echo "path ='".$_COOKIE['path']."';";
    echo "tk ='".$_COOKIE['tk']."';";

    echo "id='".$USER->id."';";
    echo "fname ='".$USER->firstname."';";
    echo "lname ='".$USER->lastname."';";
}
