<?php
require_once "./includes/logger.php";

if (!file_exists("./logs"))
{
	echo "Не найдена папка логирования\n";
	if (!mkdir("./logs"))
	{
		echo "Не удалось создать директории\n";
		exit;
	}
}
$log=new CLogger("./logs/parser.log");


