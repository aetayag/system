<?php
session_start();
session_unset();
session_destroy();

// redirect to HR login
header("Location: ../hr/index.php");
exit;
