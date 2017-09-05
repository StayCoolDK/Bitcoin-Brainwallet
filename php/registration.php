<?php


$sUsername = $_POST['username'];
$sPassword = $_POST['password'];
$sHashedPw = crypt($sPassword);
echo 'Registration successful';


?>