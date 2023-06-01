<?php
// Sessiooni algatamine
session_start();
$_SESSION = [];
// Sessiooni lõpetamine
session_destroy();

// Suunamine avalehele
header("Location: ../");
exit();
?>