<?php
// This file is part of vidyamantra - http://vidyamantra.com/
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
 * @package    chat
 * @copyright  2014 Pinky Sharma
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
ob_start();
session_start();
$sid = session_id();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-type" content="text/html;charset=UTF-8">
        <title>VidyaMantra Chat</title>
        <?php require('auth.php');?>
        <script type="text/javascript">
            <?php echo "name='".$_GET['name']."';"; ?>
            <?php echo "id='".$_GET['id']."';"; ?>
            <?php echo "sid='".$sid."';";?>
        </script>
<!--
        <script type="text/javascript" src="./bundle/jquery/jquery-1.11.0.min.js"></script>
        <script type="text/javascript" src="./bundle/jquery/jquery-ui.min.js"></script>
-->
        <link rel="stylesheet" type="text/css" href="./bundle/jquery/css/base/jquery-ui.css">
        <link rel="stylesheet" type="text/css" href="./css/jquery.ui.chatbox.css">
<!--         <link rel="stylesheet" type="text/css" href="http://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.3/css/bootstrap.min.css"> -->
        <script data-main="index.js" src="bundle/requirejs/require.js"></script>
        
        
    </head>
    <body>
        <p>It will not work until necessary scripts and stylesheets are properly loaded, check out the code.</p> 
        <div id = "stickycontainer"> </div>
    </body>
</html>