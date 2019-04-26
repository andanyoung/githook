<?php
ini_set('display_errors', 'On');
require_once 'utils/Log.php';
$requestBody = file_get_contents("php://input");

Log::DEBUG($requestBody);


$requestBody = json_decode($requestBody);


$path = isset($_get['path']) ? urldecode($_get['path']) : '../' . $requestBody['repository']['name'];

$shell_cmd = "cd {$path} && git pull";
$shell_res = system($shell_cmd);

echo $shell_cmd . '<br>';
Log::INFO($shell_cmd);
Log::INFO($shell_res);