<?php
require_once '../functions/helpers.php';
require_once '../functions/pdo_connection.php';
session_start();
unset($_SESSION['user']);
redirect('auth/login.php');