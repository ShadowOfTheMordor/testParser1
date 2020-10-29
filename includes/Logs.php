<?php
require_once "base.php";

	class Logs extends Base
	{
		//для ведения логов в бд
		protected string $tableName="log_table";
		
		public function log(string $source, string $event)
		{
			if (!$this->isConnected)
			{
				echo "Нет соединения с БД\n";
				return null;
			}
			//делаем новую запись с переданными параметрами
			try
			{
				$sqlStr="
					insert into ".$this->tableName." (source, log_event)
					values ('".$source."','".$event."')
				";
				$query_res=$this->database->query($sqlStr);
				if ($query_res!==true)
				{
					echo "ошибка в запросе: ".$sqlStr."\n".$this->database->error."/n";
					return null;
				}
			}
			catch (Exception $e)
			{
				echo "при добавлении строки в таблицу ".$this->tableName.", возникла ошибка:\n";
				echo $e->getMessage();
				return null;
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