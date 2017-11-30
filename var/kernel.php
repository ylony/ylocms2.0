<?php

namespace Ycms;

class Kernel{

    public static $logsFolder = __DIR__ . "/logs/";
    public static $debug = true;

    public function __construct()
    {
        $this->loader();
    }

	private function loader($dir = __DIR__ . "/../src/libs"){
		//Get all php file from /var/libs on first call		
		if (file_exists($dir)) 
		{
            $cdir = scandir($dir, 0);
            if ($cdir)
            {
                foreach ($cdir as $key => $value)
                {
                    if (!in_array($value, array('.', '..'), true)) 
                    {
                    	$path = $dir . '/' . $value;
                        if(is_dir($path)){
                            $this->loader($path);
                        }
                        else
                        {
                        	$ext = explode('.', $path);
                        	$ext = end($ext);
                            if ($ext == 'php')
                            {
                            	require_once($path);
                            }
                        }
                    }
                }
            }
        } 
        else 
        {
        	echo "Fatal error : Kernel can't load libs";
        	exit;
        }
	}

    public function getConfig()
    {
        require_once(__DIR__ . '/config.php');
        $config['hostname'] = $hostname;
        $config['database'] = $database;
        $config['usermysql'] = $usermysql;
        $config['passmysql'] = $passmysql;
        $config['debug'] = $debug;
        return $config;
    }

    public function getScriptPath(){
        $finalPath = NULL;
        $path = $_SERVER["PHP_SELF"];
        $path = explode('/', $path);
        $i = 0;
        while(isset($path[$i]) && $path[$i] != 'index.php'){
            $finalPath = $finalPath . $path[$i] . '/';
            $i++;
        }
        return $finalPath;
    }

    public function clearTmpFolder($folder = __DIR__ . "/../web/tmp"){
        if(file_exists($folder)){
            $cdir = scandir($folder, 0);
            if ($cdir)
            {
                foreach ($cdir as $key => $value)
                {
                    if (!in_array($value, array('.', '..'), true)) 
                    {
                        if(is_dir($folder . '/' . $value)){
                            $this->clearTmpFolder($folder . '/' . $value);
                            rmdir($folder . '/' . $value);
                        }
                        else{
                            unlink($folder . '/' . $value);
                        }
                    }
                }
            } 
        }
        return false;
    }
}




?>