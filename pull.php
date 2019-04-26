<?php
ini_set('display_errors', 'On');
require_once 'utils/Log.php';
$requestBody = file_get_contents("php://input");

Log::DEBUG($_POST);

Log::DEBUG(system("whoami"));

$path = isset($_get['path']) ? $_get['path'] : '../' . $_POST['repository']['name'];

$shell_res = shell_exec("cd {$path} && git pull");

Log::INFO($shell_res);