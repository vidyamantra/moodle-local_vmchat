<?php
// This file is part of vidyamantra - http://vidyamantra.com/.
/**
 * API key authentication
 *
 * @package    chat
 * @copyright  2014 Pinky Sharma  {@link http://vidyamantra.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
function my_curl_request($url, $postdata){
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
    // Send auth detail to server.
    $authusername = substr(str_shuffle(md5(microtime())), 0, 12);
    $authpassword = substr(str_shuffle(md5(microtime())), 0, 12);
    $licen = '';
    $postdata = array('authuser' => $authusername, 'authpass' => $authpassword, 'licensekey' => '10-30-9964360d908a76873489a1');
    $postdata = json_encode($postdata);
    $rid = my_curl_request("https://c.vidya.io", $postdata); // REMOVE HTTP.

    if (empty($rid) or strlen($rid) > 32) {
        echo "Chat server is unavailable!";
        exit;
    }

    setcookie('auth_user', $authusername, 0, '/');
    setcookie('auth_pass', $authpassword, 0, '/');
    setcookie('path', $rid, 0, '/');

}
ob_end_flush();
?>
<script type="text/javascript">
    <?php echo "auth_user='".$_COOKIE['auth_user']."';"; ?>
    <?php echo "auth_pass='".$_COOKIE['auth_pass']."';"; ?>
    <?php echo "path='".$_COOKIE['path']."';";?>
    <?php echo "imageurl='./images/quality-support.png';";?>
</script>