<?php
require "imageDatabaseQuery.php";
$config = require "config.php";
$pdo = DatabaseConnection::establishConnection($config);
return new imageDatabaseQuery($pdo);