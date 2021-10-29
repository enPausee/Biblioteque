<?php
require_once 'config.php';
require_once 'db.php';

$db = connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

$id = '';
if(!empty($_GET['id'])){
    $id = $_GET['id'];
}

deleteAuteur($db, $id);
header("Location: /php/auteur.php");
die;