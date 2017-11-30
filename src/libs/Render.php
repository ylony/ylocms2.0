<?php

namespace Ycms;


class Render{

	public $tmpFile;
	private $finalFile;

	public function __construct($file)
	{
		$md5 = md5($file);
		$this->tmpFile = $file;
		$this->finalFile = __DIR__."/../../web/tmp/" . $md5 . ".html";
	}

	public function replace($line, $request)
	{
		$line = str_replace("{{".$request["name"]."}}", $request["result"], $line);
		return $line;
	}

	public function write($line)
	{
		$file = fopen($this->finalFile, "a+");
		if($file)
		{
			fwrite($file, $line);
		}
	}

	public function generateRender()
	{
		if($this->checkLastRender()){
			if(file_exists($this->finalFile)){ 
				unlink($this->finalFile);
			}
			$tmpFile = $this->tmpFile;
			$tmpFile = fopen($tmpFile, "r");
			if($tmpFile){
				while($line = fgets($tmpFile)){
					$request = Parser::getRequest($line);
					if($request)
					{
						$line = $this->replace($line, $request);
					}
					$this->write($line);
				}
			}
			else{
				exit("Problem while rendering");
			}
		}
		require_once($this->finalFile);
	}

	private function checkLastRender() /* Return true si besoin de render false sinon */
	{
		if(file_exists($this->finalFile))
		{
			if(App::$config["debug"]){
				return true;
			}
			else{
				$mtime = filemtime($this->finalFile);
				if($mtime > time() - 3600){
					// No need to render
					return false;
				}
				// Need to update 
				return true;
			}
		}
		return true;
	}
}