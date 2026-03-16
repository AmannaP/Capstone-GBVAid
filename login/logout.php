<!-- login/logout.php -->
 
<?php
require_once '../settings/core.php';
session_destroy();
header('Location: https://forms.office.com/r/v29B6Brk85?origin=lprLink');

exit();
?>
 