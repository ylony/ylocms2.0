<?php

namespace Ycms;

class Style{

	public $name;
	public function __construct($name)
	{
		$this->name = $name;
	}

	public static function getCurrentStyle(){
		$result = App::$sql->get("SELECT * FROM yfc_styles WHERE actif = 1");
		return new Style($result["name"]);
	}

	public function loadStyle()
	{
		$path = __DIR__. "/../../web/styles/" . $this->name . "/main.php";
		if(file_exists($path)){
			$render = new Render($path);
			$render->generateRender();
			return true;
		}
		return false;
	}
	
}