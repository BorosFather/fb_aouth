<?php
session_start();
session_destroy(); //felhasználoi adatokat

//atiranyitas a login oldalra
header("Location: login.html");
exit();
?>

