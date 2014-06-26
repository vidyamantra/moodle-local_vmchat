<?php
ob_start();
session_start();
$sid = session_id();
?>
<!-- The following line is essential for the "position: fixed" property to work correctly in IE -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
  <meta http-equiv="Content-type" content="text/html;charset=UTF-8">
    <title>VidyaMantra Chat</title>
    
	
  <?php include 'auth.php';?> 
   <script type="text/javascript">
	<?php echo "name='".$_GET['name']."';"; ?>
	<?php echo "id='".$_GET['id']."';"; ?>
	<?php echo "sid='".$sid."';";?>
	</script>


 <script type="text/javascript" src="./bundle/jquery/jquery-1.11.0.min.js"></script> 
 <script type="text/javascript" src="./bundle/jquery/jquery-ui.min.js"></script>
 <script type="text/javascript" src="index.js"></script> 
 
  
  </head>
  <body>
    <p>It will not work until necessary scripts and stylesheets are
    properly loaded, check out the code.</p> 

    <div id="stickycontainer"> </div>   
  </body>
</html>

