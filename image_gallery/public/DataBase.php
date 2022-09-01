<?php

require_once "DatabaseConnection.php";
$config = require "config.php";
$pdo = DatabaseConnection::establishConnection($config);
return $pdo;
