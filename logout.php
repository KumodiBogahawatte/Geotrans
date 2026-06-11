<?php
require_once 'includes/helpers.php';

// Destroy session and redirect
destroyUserSession();
redirect('index.php');
?>
