<?php

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=simpleauthpdo','root', '');
} catch(PDOException $e) {
    die($e->getMessage());
}