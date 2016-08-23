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
 * @copyright  2016 Pinky Sharma
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

function local_vmchat_curl_request($url, $postdata) {
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

/**
 * Update value in db for enabling/disabling vmchat
 * Delete div from additionalhtmlfooter
 * Unset all relevent cookie 
 *
 * @return void
 * @since  Moodle 3.0
 */
function local_vmchat_disable() {
    set_config('enablevmchat', 0, 'local_vmchat');
    $sql = "UPDATE {config} set value = replace(value, '<div id=\"stickycontainer\"></div>','') "
                . "where value LIKE '%<div id=\"stickycontainer\"></div>%' and name='additionalhtmlfooter'";
    $DB->execute($sql);
    setcookie('auth_user', null, -1, '/');
    setcookie('auth_pass', null, -1, '/');
    setcookie('path', null, -1, '/');
    setcookie('tk', null, -1, '/');
    purge_all_caches();
}

/**
 * Display a error messages during connection.
 *
 * @return void
 * @since  Moodle 3.0
 */
function local_vmchat_js_messages($message) {
    echo '<script type="text/javascript">'."\n//<![CDATA[\n alert('".$message."');\n//]]>\n</script>";
}

function local_vmchat_extend_navigation(global_navigation $nav) {
    global $USER, $CFG, $PAGE;
    if (!$USER->id) {
        setcookie('auth_user', null, -1, '/');
        setcookie('auth_pass', null, -1, '/');
        setcookie('path', null, -1, '/');
        setcookie('tk', null, -1, '/');
    }
    if (get_config('local_vmchat', 'enablevmchat')) {
        if (!isset($_COOKIE['auth_user']) || !isset($_COOKIE['auth_pass']) || !isset($_COOKIE['path'])) {
            if (true) { // False for local server deployment
                $licen = get_config('local_getkey', 'keyvalue');
                if(!$licen) {
                    local_vmchat_js_messages("API key is missing");
                    local_vmchat_disable();
                    return;
                } else {
                    // Send auth detail to server.
                    $authusername = substr(str_shuffle(md5(microtime())), 0, 12);
                    $authpassword = substr(str_shuffle(md5(microtime())), 0, 12);
                    $postdata = array('authuser' => $authusername, 'authpass' => $authpassword, 'licensekey' => $licen);
                    $postdata = json_encode($postdata);

                    $rid = local_vmchat_curl_request("https://c.vidya.io", $postdata); // REMOVE HTTP.
                }

                if (empty($rid) or strlen($rid) > 32) {
                    local_vmchat_js_messages("Chat server is unavailable!");
                    return;
                }
                if ($rid == 'Rejected - Key Not Active') {
                    local_vmchat_js_messages("VmChat license key is not valid");
                    local_vmchat_disable();
                    return;
                }
                $rid = "wss://$rid";
            } else {
                $rid = "ws://127.0.0.1:8080";
            }
            setcookie('auth_user', $authusername, 0, '/');
            setcookie('auth_pass', $authpassword, 0, '/');
            setcookie('path', $rid, 0, '/');

            $result = get_config('local_getkey', 'tokencode');
            setcookie('tk', $result, 0, '/');

        } else {

            if (!empty($USER) && $USER->id) {
                // Moodle user pic url.
                $userpicture = moodle_url::make_pluginfile_url(context_user::instance($USER->id)->id, 'user', 'icon', null, '/', 'f2');
                $src = $userpicture->out(false);
                //print_r($_COOKIE);exit;                
                $authu = (isset($_COOKIE['auth_user'])) ? clean_param($_COOKIE['auth_user'], PARAM_ALPHANUMEXT) : '';
                $authp = $_COOKIE['auth_pass'];
                $authpt = $_COOKIE['path'];
                $autht = $_COOKIE['tk'];
                echo <<<EOD
                <script type="text/javascript">
                //<![CDATA[
                var wwwroot="$CFG->wwwroot/";
                var imageurl = "$src";
                var sid = "$USER->sesskey";
                var auth_user = "$authu";
                var auth_pass = "$authp";
                var path = "$authpt";
                var tk = "$autht";
                var id = "$USER->id";
                var fname = "$USER->firstname";
                var lname = "$USER->lastname";
                //]]>
                </script>
EOD;
            }
        $PAGE->requires->js_call_amd('local_vmchat/vmchat', 'init',array("$CFG->wwwroot/"));
        }
    }
}

/**
 * user logout event handler
 *
 * @param \core\event\user_loggedout $event The event.
 * @return void
 */
function local_vmchat_user_loggedout($event) {
    setcookie('auth_user', null, -1, '/');
    setcookie('auth_pass', null, -1, '/');
    setcookie('path', null, -1, '/');
    setcookie('tk', null, -1, '/');
}
