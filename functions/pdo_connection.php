<?php
$dbHost = '127.0.0.1';
$dbName = 'php_project_1';
$dbUsername = 'root';
$dbPassword = '';
global $pdo;
try {
    $option = [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_OBJ];
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName",$dbUsername,$dbPassword,$option);
    return $pdo;
}
catch (PDOException $e){
    echo "error => ".$e->getMessage();
}
