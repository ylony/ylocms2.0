<?php

namespace Ycms;

use Mysqli;

class SQL{

    public $db = false;
    public $i = 0; //SQL COUNT
    public $totaltime = 0;

    public function __construct($host, $usermysql, $passmysql, $database)
    {
        $connect = new mysqli($host, $usermysql, $passmysql, $database);
        if ($connect->connect_error) {
            if (App::$config["debug"] == TRUE){
                die('Erreur : (' . $connect->connect_errno . ')'.$connect->connect_error);
            }
            else {
                echo "Oops, Something goes wrong ! Maybe you should active DEBUG mode ?";
            }
            $db = FALSE;
        }
        else {
            $this->db = $connect;
        }
    }

    public function query($string)
    {
        if($this->db != false)
        {
            $starttime = microtime(true);
            $stmt = $this->db->query($string);
            $endtime = microtime(true);
            $duration = $endtime - $starttime;
            if(!$stmt){
                if(App::$config["debug"] == TRUE){
                    printf("Erreur : %s\n", $this->db->error);
                    return FALSE;
                }
                else{
                    echo "Oops something goes wrong !";
                    return FALSE;
                }
            }
            else {
                if(App::$config["debug"] == TRUE){
                    $this->i++;	//Count all sql query done
                    $this->totaltime = $this->totaltime + $duration;
                }
                return $stmt;
            }
        }
        else{
            echo "no active connection";
        }
    }

    public function getall($string)
    {
        $stmt = $this->query($string);
        if($stmt == FALSE){
            return FALSE;
        }
        while($get = $stmt->fetch_all(MYSQLI_ASSOC)){
            $data = $get;
        }
        if (empty($data)){
            return FALSE;
        }
        else{
            return $data;
        }
    }

    public function get($string)
    {
        $stmt = $this->query($string);
        if($stmt == FALSE){
            return FALSE;
        }
        while($get = $stmt->fetch_assoc()){
            $data = $get;
        }
        if (empty($data)){
            return FALSE;
        }
        else{
            return $data;
        }
    }

    public function count($string)
    {
        $stmt = $this->query($string);
        if($stmt == FALSE){
            return FALSE;
        }
        $count = $stmt->num_rows;
        return $count;
    }

    public function close()
    {
        $this->db->close();
        $this->db = false;
    }
}