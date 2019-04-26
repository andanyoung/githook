<?php
ini_set('display_errors', 'On');
require_once 'utils/Log.php';
$requestBody = file_get_contents("php://input");

$log_level = isset($_GET['log_level']) ? $_GET['is_log'] : 0;
$log_level = intval($log_level);
define('LOG_LEVEL', 0);

Log::DEBUG($requestBody);
system('whoami');

$requestBody = json_decode($requestBody, true);


$path = isset($_GET['path']) ? urldecode($_GET['path']) : '../' . $requestBody['repository']['name'];

$shell_cmd = "cd {$path} && git pull";
echo $shell_cmd . '<br>';

$shell_res = system($shell_cmd);

Log::INFO($shell_cmd);
Log::INFO($shell_res);