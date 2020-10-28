<?php
namespace App;

	class Base
	{
		//управляющий класс
		//соединяет с бд
		
		protected string $dbName="";
		protected string $dbUser="";
		protected string $dbPassword="";
		protected string $dbHost="";
		protected bool $isConnected=false;
		protected mysqli $database;
		
		public function __construct(string $host, string $user, string $password, string $name)
		{
			//конструктор. создает внутри соединение с бд
			$this->invoiceList=array();
			$this->dbName=$name;
			$this->dbHost=$host;
			$this->dbUser=$user;
			$this->dbPassword=$password;
			$this->database=new mysqli($this->dbHost, $this->dbUser, $this->dbPassword, $this->dbName);
			if ($this->database->connect_errno)
			{
				echo "Ошибка: Не получилось создать соединение с базой MySQL и вот почему: "."\n";
				echo "Номер: ".$this->database->connect_errno."\n";
				echo "Описание ошибки: ".$this->database->connect_error."\n";
			}
			else
			{
				echo "успешно"."\n";
				$this->isConnected=true;
				
			}
		}

		public function __destruct()
		{
			unset($this->database);
		}
		
		public function isConnected() : bool
		{
			return $this->isConnected;
		}
	}