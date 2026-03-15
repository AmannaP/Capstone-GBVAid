<!-- login/logout.php -->
 
<?php
require_once '../settings/core.php';
session_destroy();
header('Location: ../');

exit();
?>
