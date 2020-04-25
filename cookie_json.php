<?php
header('Access-Control-Allow-Headers: Content-Type');
$jsonString = file_get_contents('php://input');

file_put_contents("json.json", $jsonString);

exit();