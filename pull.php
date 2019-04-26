<?php
ini_set('display_errors', 'On');
require_once 'utils/Log.php';
$requestBody = file_get_contents("php://input");

$is_log = isset($_GET['is_log']) ? $_GET['is_log'] : false;
$is_log && Log::DEBUG($requestBody);
system('whoami');

$requestBody = json_decode($requestBody, true);


$path = isset($_GET['path']) ? urldecode($_GET['path']) : '../' . $requestBody['repository']['name'];

$shell_cmd = "cd {$path} && git pull";
$shell_res = system($shell_cmd);

echo $shell_cmd . '<br>';
$is_log && Log::INFO($shell_cmd);
$is_log && Log::INFO($shell_res);