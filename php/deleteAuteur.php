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

deleteAuteur($db, $id);
header("Location: /php/auteur.php");
die;