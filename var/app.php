<?php 


namespace Ycms;

class App{

	public static $config;
	public static $sql;
	private static $kernel;

	public function start()
	{
		self::$kernel = new Kernel();
		self::$config = self::$kernel->getConfig();
		self::sqlConnect();
		$style = Style::getCurrentStyle();
		if(!$style->loadStyle()){
			exit("Can't load the default style");
		}
	}

	public static function sqlConnect()
	{
		self::$sql = new SQL(self::$config['hostname'], self::$config['usermysql'], self::$config['passmysql'], self::$config['database']);
	}

	public static function sendRequest($string)
	{
		switch ($string) {
			case 'getCurrentStylePath':
				$style = Style::getCurrentStyle();
				return self::$kernel->getScriptPath() . "./styles/" . $style->name . "/";
				break;
			
			default:
				# code...
				break;
		}
	}
}