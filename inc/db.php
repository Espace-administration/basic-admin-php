<?php

$pdo = new PDO('mysql:dbname=admin_basic;host=localhost', 'root', 'admin');

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);