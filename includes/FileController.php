<?php
//класс для проверки файлов на уже обработанные

	class FileController
	{
		protected string $fileName=".parserignore";//имя файла
		protected resource $file;
		protected array $filesList;

		public function __construct()
		{
			$file="";
			$this->filesList=array();
			if (!file_exists($this->fileName))
			{
				$file=fopen($this->fileName,"r");
				fclose($file);
			}
			else
			{
				//читаем строки в массив
				$file=fopen($this->fileName,"r");
				if ($file)
				{
					while (($str = fgets($file)) !== false)
					{
						echo "file in list : ".$str."\n";
						$this->filesList[$str]=1;
					}
					if (!feof($file))
					{
						echo "Ошибка: fgets() неожиданно потерпел неудачу\n";
					}
					fclose($file);
				}
				var_dump($this->filesList);
			}//else if file_exists
		}
		
		public function check($filename) : bool
		{
			//файл уже ранее обработан
			if (!empty($this->filesList[$filename]))
				return false;
			echo "checking \"".$filename."\" file in list exists = ".(!empty($this->filesList[$filename]))."\n";
			$file=fopen($this->fileName,"a");
			fwrite($file,$filename.PHP_EOL);
			fclose($file);
			$this->filesList[$filename]=1;
			return true;
		}
	}