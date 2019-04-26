<?php
ini_set('display_errors', 'On');
require_once 'utils/Log.php';
$requestBody = file_get_contents("php://input");

Log::DEBUG($requestBody);



