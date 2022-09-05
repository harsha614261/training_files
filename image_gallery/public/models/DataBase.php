<?php

require "DatabaseConnection.php";
require "userDatabaseQuery.php";
$config = require "config.php";
$pdo = DatabaseConnection::establishConnection($config);
return new userDatabaseQuery($pdo);
