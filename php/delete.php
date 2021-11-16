<?php
require_once 'config.php';
require_once 'db.php';

session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$db = connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

$id = '';
if(!empty($_GET['id'])){
    $id = $_GET['id'];
}

if(empty($id))
    throw new Exception('ID is blank');

deleteLivre($db, $id);
header("Location: /php/print.php");
die;