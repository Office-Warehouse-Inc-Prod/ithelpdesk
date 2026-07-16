<?php
session_start();
session_unset();
session_destroy();
header('Location: price_verifier_login.php');
exit();
