<?php
require_once "base.php";

	class Clients extends Base
	{
		//для работы с таблицей clients
		protected string $tableName="clients";
		
		public function addRow(string $reg_number, string $company_name, string $url, string $phone_number, string $email):bool
		{
			if (!$this->isConnected)
			{
				echo "Нет соединения с БД\n";
				return false;
			}
			
			//делаем новую запись с переданными параметрами
			try
			{
				$sqlStr="
					insert into ".$this->tableName." (reg_number, company_name, url, phone_number, email)
					values (".$reg_number.",'".$company_name."','".$url."','".$phone_number."','".$email."')
				";
				$query_res=$this->database->query($sqlStr);
				if ($query_res!==true)
				{
					echo "ошибка в запросе: ".$sqlStr."\n".$this->database->error."/n";
					return false;
				}
				return true;
			}
			catch (Exception $e)
			{
				echo "при добавлении строки в таблицу ".$this->tableName.", возникла ошибка:\n";
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