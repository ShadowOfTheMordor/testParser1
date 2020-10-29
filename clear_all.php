<?php

require_once "./includes/logger.php";
require_once "./includes/clients.php";
require_once "./includes/logs.php";
require_once "./includes/DbController.php";

function rrmdir($dir) {
   if (is_dir($dir)) {
     $objects = scandir($dir);
     foreach ($objects as $object) {
       if ($object != "." && $object != "..") {
         if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
       }
     }
     reset($objects);
     rmdir($dir);
   }
} 
$host="localhost";
$user="parser_user";
$password="password123";
$name="testparser";

$import_path="./import";

//таблица для записи из csv
$clients=new Clients($host, $user, $password, $name);
//таблица для записи логов
$db_logger=new Logs($host, $user, $password, $name);

$db_controller=new DbController($host, $user, $password, $name);

echo "очищаем все таблицы\n";
$clients->truncate();
$db_logger->truncate();
$db_controller->truncate();
echo "успешно очищены таблицы\n";

//удаляет папку import
//rrmdir($import_path);

