<?php
ini_set('display_errors', 'On');

$log_level = isset($_GET['log_level']) ? $_GET['is_log'] : 0;
$log_level = intval($log_level);
define('LOG_LEVEL', 0);

require_once 'utils/Log.php';

if ($_SERVER['HTTP_CONTENT_TYPE'] === 'application/json') {
    $requestBody = json_decode($requestBody, true);
    $requestBody = file_get_contents("php://input");
} elseif ($_SERVER['HTTP_CONTENT_TYPE'] === 'application/x-www-form-urlencoded') {
    $requestBody = $_POST;
}
if (!isset($requestBody)) {
    exit('没有请求参数');
}


Log::DEBUG($requestBody);
echo 'USER: ';
system('whoami');

$path = isset($_GET['path']) ? urldecode($_GET['path']) : '../' . $requestBody['repository']['name'];

$shell_cmd = "cd {$path} && git pull";
echo $shell_cmd . "\n";

$shell_res = system($shell_cmd);

Log::INFO($shell_cmd);
Log::INFO($shell_res);