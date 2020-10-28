<?php
//logger
declare(strict_types=1);

class CLogger
{
//	private resource $FileHandle;
//	private string $FileName;
//	private boolean $Opened;
//	private boolean $LoggingAllowed;
	private $FileHandle;
	private $FileName;
	private $Opened;
	private $LoggingAllowed;

	
	function __construct (string $filename)
	{
		$this->LoggingAllowed=true;
		$this->Opened=false;
		$this->FileHandle=0;
		$this->FileName=$filename;
		$this->FileHandle=fopen($filename,"a");
		if ($this->FileHandle)
		{
			$this->Opened=true;
		}
	}
	
	function __destruct()
	{
		if (!$this->LoggingAllowed)
			return;
		if (!$this->Opened)
			return;
		fclose($this->FileHandle);
	}
	
	function write(string $text)
	{
		if (!$this->LoggingAllowed)
			return;
		if (!$this->Opened)
			return;
		fwrite($this->FileHandle,$text.PHP_EOL);
	}
	
	function allow()
	{
		if ($this->LoggingAllowed)
			return;
		$this->LoggingAllowed=true;
		$this->FileHandle=0;
		$this->FileHandle=fopen($this->FileName,"a");
		if ($this->FileHandle)
		{
			$this->Opened=true;
		}
	}
	
	function disallow()
	{
		$this->LoggingAllowed=false;
		if ($this->Opened)
		{
			$this->Opened=false;
			fclose($this->FileHandle);
		}
	}
}
?>