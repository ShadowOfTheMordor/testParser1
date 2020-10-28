<?php

require_once "vendor/autoload.php";
require_once "./includes/logger.php";

//var_dump($argv);

echo "количество элементов массива = ".count($argv)."\n";
//параметры командной строки — год месяц день минимум максимум «кол-во файлов»
if (count($argv)!=7)
	exit;

if (!file_exists("./logs"))
{
	echo "Не найдена папка логирования\n";
	if (!mkdir("./logs"))
		echo "Не удалось создать директории\n";
}
$log=new CLogger("./logs/generator.log");

//теперь фейкер
$year=(int)$argv[1];
$month=(string)$argv[2];
$day=(string)$argv[3];
$min_rows=(int)$argv[4];
$max_rows=(int)$argv[5];
$files_count=(int)$argv[6];
//проверка
if ($year<1900 || $year>2020)
{
	echo "неправильный год";
	exit;
}
if ((int)$month<1 || (int)$month>12)
{
	echo "неправильный месяц";
	exit;
}
if ((int)$day<1 || (int)$day>30)
{
	echo "неправильный день";
	exit;
}
if (($min_rows>$max_rows)||$min_rows<0||$max_rows<0)
{
	echo "неправильное максимальное или минимально количество строк";
	exit;
}
if ($files_count<1)
{
	echo "неправильное количество файлов";
	exit;
}

$log->write("переданы параметры год = ".$year." месяц = ".$month." день = ".$day." минимум строк = ".$min_rows." максимум строк = ".$max_rows." количество файлов = ".$files_count."\n");
$full_path="./import/".$year."/".$month."/".$day;
//сначала директорию
if (!file_exists($full_path))
{
	$log->write("directory \"".$full_path."\" does not exists");
	//создаем директории
	if (!mkdir($full_path,0777,true))
		echo "Не удалось создать директории\n";
}


$faker = Faker\Factory::create('ru_RU'); 

//цикл по количеству файлов(задано из командной строки)
for ($i=0;$i<$files_count;$i++)
{
	//создаем файлы
	$file_name=$faker->unique()->word().".csv";
	if(file_exists($full_path."/".$file_name))
	{
		$log->write("файл \"".$full_path."/".$file_name."\" уже существует");
		continue;
	}
	
	$fp=fopen($full_path."/".$file_name, "a");
	//количество строк в файле из параметров командной строки
	$rows_count=rand($min_rows,$max_rows);
	for ($j=0;$j<$rows_count;$j++)
	{
		//поля в файлах такие "рег. номер, наименование, url, телефон, email"
		$full_string=$faker->inn().",".$faker->company().",".$faker->url().",".$faker->phoneNumber().",".$faker->companyEmail().PHP_EOL;
		fwrite($fp,$full_string);
	}
	fclose($fp);
}

/*
$faker = Faker\Factory::create('ru_RU'); 
for ($i = 0; $i < 10; $i++) {
	echo $faker->name."\n";
	echo $faker->domainName()."\n";
	echo $faker->url()."\n";
	echo $faker->companyEmail()."\n";
	echo $faker->phoneNumber()."\n";
	echo $faker->company()."\n";
//	echo $faker->companyIdNumber."\n";
	echo $faker->inn()."\n";
//	echo $faker->word->unique().".csv\n";
	echo $faker->unique()->word().".csv\n";
}
*/
