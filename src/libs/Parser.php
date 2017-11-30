<?php

namespace Ycms;

class Parser{
	public static function myStrPos($str, $keyword)
	{
		$i = 0;
		$j = 0;
		$x = strlen($keyword);
		while($i < strlen($str))
		{
			if($str[$i] == $keyword[$j])
			{
				$j++;
			}
			else{
				$j = 0;
			}
			$i++;
			if($j == $x){
				return true;
			}
		}
		return false;
	}

	public static function getRequest($line)
	{
		
		$finalRequest = array('name' => '', 'result' => '');
		if(self::myStrPos($line, "{{")){
			if(self::myStrPos($line, "}}")){
				$request = explode('{{', $line);
				$request = explode('}}', $request["1"]);
				$finalRequest['name'] = $request["0"];
				$finalRequest['result'] = App::sendRequest(trim($finalRequest['name']));
				return $finalRequest;
			}
		}
		return false;
	}
}