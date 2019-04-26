<?php
require_once 'utils/Logs.php';
$requestBody = file_get_contents("php://input");

Log::DEBUG($requestBody);



