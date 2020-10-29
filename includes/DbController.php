<?php
require_once "base.php";

	class DbController extends Base
	{
		//для работы с таблицей clients
		protected string $tableName="db_controller";
/*		
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
//				echo "успешно"."\n";
				$this->isConnected=true;
				//записываем данные из таблицы
			}
		}
	*/

		public function check($filename)
		{
			if (!$this->isConnected)
			{
				echo "Нет соединения с БД\n";
				return null;
			}
			try
			{
				$sqlStr="select * from ".$this->tableName."
						where file_name='".$filename."'
				";
				$result=$this->database->query($sqlStr);
				while(($row=$result->fetch_assoc())!==null)
				{
					//есть хотя бы одна строка
					return false;
				}
				$sqlStr="
					insert into ".$this->tableName." (file_name)
					values ('".$filename."')
				";
				$query_res=$this->database->query($sqlStr);
				if ($query_res!==true)
				{
					echo "ошибка в запросе: ".$sqlStr."\n".$this->database->error."/n";
					return null;
				}
				return true;
			}
			catch(Exception $e)
			{
				echo "при проверке файла возникла ошибка:\n";
				echo $e->getMessage();
				return false;
			}
			
		}

		public function truncate()
		{
			//очистка таблицы, так удобнее
			if (!$this->isConnected)
			{
				echo "Нет соединения с БД\n";
				return null;
			}

			try
			{
				$sqlStr="truncate table ".$this->tableName;
				$query_res=$this->database->query($sqlStr);
				if ($query_res===true)
					echo "успешно очищено ".$this->tableName."\n";
				else
				{
					echo "ошибка в запросе: ".$sqlStr."\n".$this->database->error."\n";
					return null;
				}
			}
			catch (Exception $e)
			{
				echo "при очистке таблицы".$this->tableName." возникла ошибка:\n";
				echo $e->getMessage();
				return null;
			}
		}
		

	}