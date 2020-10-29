<?php
require_once "./includes/logger.php";
require_once "./includes/clients.php";
require_once "./includes/logs.php";
//require_once "./includes/FileController.php";
require_once "./includes/DbController.php";


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

//string $host, string $user, string $password, string $name
//соединение с бд

$host="localhost";
$user="parser_user";
$password="password123";
$name="testparser";

//таблица для записи из csv
$clients=new Clients($host, $user, $password, $name);
//таблица для записи логов
$db_logger=new Logs($host, $user, $password, $name);

//$db_logger->log("parser.php","something happened");

//$file_controller=new FileController();
$db_controller=new DbController($host, $user, $password, $name);

//проверка папок. не рекурсивно, т.к. стандартная структура

$import_path="./import";
$year="";
$month="";
$day="";
$db_logger->log("parser.php","парсер запущен, начата обработка");
$files=scandir($import_path);
if ($files)
{
	foreach ($files as $file_year)
	{
		if ($file_year=="." || $file_year=="..")
			continue;
		if (!is_dir($import_path."/".$file_year))
		{
			echo "\t\"".$import_path."/".$file_year."\" не является папкой\n";
			$db_logger->log("parser.php",$import_path."/".$file_year."\" не является папкой");
			continue;
		}
		
		$year=$file_year;
		//игнорируем неправильный год
		if ((int)$year<1900 || (int)$year>2020)
			continue;
//		echo $file_year."\n";
		$files_year=scandir($import_path."/".$year);
		if ($files_year)
		{
			foreach ($files_year as $file_month)
			{
				if ($file_month=="." || $file_month=="..")
					continue;
				if (!is_dir($import_path."/".$year."/".$file_month))
				{
					echo "\t\t\"".$import_path."/".$year."/".$file_month."\" не является папкой\n";
					$db_logger->log("parser.php",$import_path."/".$year."/".$file_month."\" не является папкой");
					continue;
				}
				$month=$file_month;
				//игнорируем неправильный месяц
				if ((int)$month<1 || (int)$month>12)
					continue;
//				echo "\t".$import_path."/".$year."/".$file_month."\n";
				$files_month=scandir($import_path."/".$year."/".$month);
				if ($files_month)
				{
					//перебор дней
					foreach($files_month as $file_day)
					{
						if ($file_day=="." || $file_day=="..")
							continue;
						if (!is_dir($import_path."/".$year."/".$file_month))
						{
							echo "\t\t\t\"".$import_path."/".$year."/".$file_month."/".$file_day."\" не является папкой\n";
							$db_logger->log("parser.php",$import_path."/".$year."/".$file_month."/".$file_day."\" не является папкой");
							continue;
						}
						$day=$file_day;
						//игнорируем неправильный месяц
						if ((int)$day<1 || (int)$day>31)
							continue;
						echo "\tпроверка пути \"".$import_path."/".$year."/".$file_month."/".$file_day."\"\n";
						$db_logger->log("parser.php","проверка пути \"".$import_path."/".$year."/".$file_month."/".$file_day."\"");
/*						$files_day=scandir($import_path."/".$year."/".$month."/".$day);
						if ($files_day)
						{
							foreach($files_day as $file_one)
							{
								$file_name=$file_one;
								echo "\t\t\t".$file_name."\n";
							}
						}*/
						$files_day=glob($import_path."/".$year."/".$month."/".$file_day."/"."*.csv");
//							var_dump($files_day);

						if ($files_day)
						{
//							var_dump($files_day);
							foreach($files_day as $file_one)
							{
								
								$file_name=$file_one;
//								echo "\t\t\tобрабатывается файл \"".$file_name."\"\n";
//								if ($file_controller->check($file_name))
								if ($db_controller->check($file_name))
								{
									echo "\t\tновый файл \"".$file_name."\"\n";
									$db_logger->log("parser.php","новый файл \"".$file_name."\"");
									//читаем файл и вносим данные в таблицу
									$file=fopen($file_name,"r");
									if ($file)
									{
										echo "\t\tобрабатывается файл \"".$file_name."\"\n";
										$db_logger->log("parser.php","обрабатывается файл \"".$file_name."\"");
										while (($read_row = fgets($file)) !== false)
										{
											//превращаем строку в массив по разделителю
											$params_arr=explode(",",$read_row);
											//var_dump($params_arr);
											if (count($params_arr)!=5)
											{
												echo "неверная строка в файле: ".$read_row."\n";
												$db_logger->log("parser.php","неверная строка в файле: ".$read_row);
												continue;
											}
											//запись в бд
											$reg_number=(string)$params_arr[0];
											$company_name=(string)$params_arr[1];
											$url=(string)$params_arr[2];
											$phone_number=(string)$params_arr[3];
											$email=(string)$params_arr[4];
											$clients->addRow($reg_number,$company_name,$url,$phone_number,$email);
//											echo "успешно добавлена строка ".$read_row."\n";
											$db_logger->log("parser.php","успешно добавлена строка ".$read_row);
										}
										if (!feof($file))
										{
											echo "Ошибка чтения файла\n";
											$db_logger->log("parser.php","Ошибка чтения файла");
										}
										fclose($file);
										//сообщение об обработанном файле
										echo "\t\tфайл \"".$file_name."\" успешно обработан\n";
										$db_logger->log("parser.php","файл \"".$file_name."\" успешно обработан");
									}
								}//if db_controller
								else
								{
									echo "\t\tобнаружен уже обработанный файл \"".$file_name."\"\n";
									$db_logger->log("parser.php","обнаружен уже обработанный файл \"".$file_name."\"");
								}
							}
						}//if files_day
						
						
					}//foreach $files_month
				}
			}//foreach $files_year
		}
	}//foreach
}//if ($files)

$db_logger->log("parser.php","парсер завершил работу");
