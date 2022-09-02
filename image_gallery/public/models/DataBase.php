<?php

require "DatabaseConnection.php";
require "DatabaseQueries.php";
$config = require "config.php";
$pdo = DatabaseConnection::establishConnection($config);
return new DatabaseQueries($pdo);
