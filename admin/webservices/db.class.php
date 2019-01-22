<?php

class DB {
    
    private $pdo = NULL;
    
    // For Android Notification
    private $android_GCM = '';
    
    // For Iphone Notification
    private $sandBox = 0; // 0-Sandbox / 1-Live
    private $pem_Dev = '';
    private $pem_Pro = '';
    private $passPhrase = '12345';
    
    // For BASE URL
    public $baseurl = NULL;

    // Creation of Database Connection
    function __construct() {
        $host = 'localhost';
        $db = '';
        $user = '';
        $pass = '';
        $charset = 'utf8';
        
        try {
            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
            $this->pdo = new PDO($dsn, $user, $pass);
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        
        $this->baseurl = $this->curPageURL();
        if (!defined('WEBSITE'))
      	//define('WEBSITE', "http://" . $_SERVER['SERVER_NAME'] . "/"); 
        define('WEBSITE', "http://10k.tempurl.co.il/");
        define("UPLOADS", WEBSITE."uploads/");
        define("STORY_IMAGES", WEBSITE."uploads/story_images/");
        date_default_timezone_set('Asia/Kolkata');
//        mysql_set_charset('utf8');
    }
    
    function curPageURL() {
        $pageURL = 'http';
//        if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
        $pageURL .= "://";
        if(!empty($_SERVER["SERVER_PORT"])){
        if ($_SERVER["SERVER_PORT"] != "80") {
         $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
        } else {
         $pageURL .= $_SERVER["SERVER_NAME"];
        }
        }
        return $pageURL;
    }
    
    // For Security Purpose
    function security($device_id, $source, $timestamp) {
        $secret_key = '998877';
        $secure_pin = md5($secret_key . $device_id . $source . $timestamp);
        return $secure_pin;
    }
    function clean($string) {
    $string = str_replace(' ', '_', $string); // Replaces all spaces with hyphens.

    return preg_replace('/[^A-Za-z0-9\_-]/', '', $string); // Removes special chars.
    }
    // Get the records of any query
    function query_result($query) {
        $result = array();
        $run = $this->pdo->query($query);
        while ($row = $run->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $row;
        }
        return $result;
    }

    // Get Data from a particular table
    function get_all_records($table) {
        $result = array();
        $query = "SELECT * FROM `$table`";
        $run = $this->pdo->query($query);
        while ($row = $run->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $row;
        }
        return $result;
    }

    // Get Data from a table can also apply different conditions
    function get_record_where($table, $where, $column = '', $group_by = '', $order_by = '', $order_by_type = '', $limit = '') {
        $result = array();
        $query = "SELECT ";
        if ($column != '')
            $query .= "$column ";
        else
            $query .= "* ";
        $query .= "FROM $table WHERE ";

        if (is_array($where)) {
            $i = 0;
            foreach ($where as $key => $value) {
                $key_ar = explode(' ', $key);
                $key = trim($key_ar[0]);
                
                $condition = '=';
                if(!empty($key_ar[1]))
                    $condition = trim($key_ar[1]);
                
                if ($i == 0)
                    $query .= "$key $condition :$key ";
                else
                    $query .= "AND $key $condition :$key ";

                $where_array[":$key"] = $value;

                $i++;
            }

            if ($group_by != '')
                $query .= "GROUP BY $group_by ";

            if ($order_by != '') {
                $query .= "ORDER BY $order_by ";
                if ($order_by_type != '')
                    $query .= "$order_by_type ";
            }

            if ($limit != '')
                $query .= "LIMIT $limit";
            //echo $query;
            $run = $this->pdo->prepare($query);
            $run->execute($where_array);
            while ($row = $run->fetch(PDO::FETCH_ASSOC)) {
                $result[] = $row;
            }
        }
        return $result;
    }
    
    // For Inserting records in database
    function insert_records($table, $data) {
        //print_r($data);die();
        $insert_id = 0;
        $query = "INSERT INTO $table";
        if (is_array($data)) {
            $statement_1 = "(";
            $statement_2 = "VALUES(";
            foreach ($data as $key => $value) {
                $statement_1 .= "$key,";
                $statement_2 .= ":$key,";

                $insert_data[":$key"] = $value;
            }
            $statement_1 = rtrim($statement_1, ",");
            $statement_2 = rtrim($statement_2, ",");

            $statement_1 .= ") ";
            $statement_2 .= ")";

            $query .= $statement_1 . $statement_2;
           // echo ''.$query;die();
            $insert = $this->pdo->prepare($query);
            //echo 'hello';die();
            $insert->execute($insert_data);

            $insert_id = $this->pdo->lastInsertId();
        }
        return $insert_id;
    }
    
    // For updation of database records
    function update_records($table, $data, $where) {
        $affected_rows = 0;
        $query = "UPDATE $table SET ";
        if (is_array($data) && is_array($where)) {
            foreach ($data as $key => $value) {
                $query .= "$key = :$key, ";
                $exe_array[":$key"] = $value;
            }

            $query = rtrim($query, ", ");

            $query .= " WHERE ";

            $i = 0;
            foreach ($where as $key => $value) {
                $key_ar = explode(' ', $key);
                $key = trim($key_ar[0]);
                
                $condition = '=';
                if(!empty($key_ar[1]))
                    $condition = trim($key_ar[1]);
                
                if ($i == 0)
                    $query .= "$key $condition :$key ";
                else
                    $query .= "AND $key $condition :$key ";

                $exe_array[":$key"] = $value;

                $i++;
            }

            $run = $this->pdo->prepare($query);
            $run->execute($exe_array);
            $affected_rows = $run->rowCount();
        }
        return $affected_rows;
    }
    
    // For deleting any records from database
    function delete_record($table, $where) {
        $affected_rows = 0;
        $query = "DELETE FROM $table WHERE ";
        if (is_array($where)) {
            $i = 0;
            foreach ($where as $key => $value) {
                $key_ar = explode(' ', $key);
                $key = trim($key_ar[0]);
                
                $condition = '=';
                if(!empty($key_ar[1]))
                    $condition = trim($key_ar[1]);
                
                if ($i == 0)
                    $query .= "$key $condition :$key ";
                else
                    $query .= "AND $key $condition :$key ";

                $exe_array[":$key"] = $value;

                $i++;
            }

            $run = $this->pdo->prepare($query);
            $run->execute($exe_array);
            $affected_rows = $run->rowCount();
        }
        return $affected_rows;
    }
    
    // For get records using JOIN query
    function get_record_join($tables, $keys_1, $keys_2, $join_type, $where = '', $column = '', $group_by = '', $order_by = '', $order_by_type = '', $limit = '') {
        $records = array();
        if (is_array($tables) && is_array($keys_1) && is_array($keys_2) && is_array($join_type)) {
            $query = "SELECT ";
            if ($column != '')
                $query .= "$column ";
            else
                $query .= "* ";

            $query .= "FROM " . $tables[0] . " ";

            foreach ($tables as $key => $value) {
                if ($key != 0)
                    $query .= strtoupper($join_type[$key]) . " JOIN $value ON " . $keys_1[$key - 1] . " = " . $keys_2[$key - 1] . " ";
            }
        }

        $where_array = array();

        if ($where != '' && is_array($where)) {
            $query .= "WHERE ";
            $i = 0;
            foreach ($where as $key => $value) {
                $key_ar = explode(' ', $key);
                $key = trim($key_ar[0]);
                
                $condition = '=';
                if(!empty($key_ar[1]))
                    $condition = trim($key_ar[1]);
                
                if ($i == 0)
                    $query .= "$key $condition :$key ";
                else
                    $query .= "AND $key $condition :$key ";

                $where_array[":$key"] = $value;

                $i++;
            }
        }

        if ($group_by != '')
            $query .= "GROUP BY $group_by ";

        if ($order_by != '') {
            $query .= "ORDER BY $order_by ";
            if ($order_by_type != '')
                $query .= "$order_by_type ";
        }

        if ($limit != '')
            $query .= "LIMIT $limit";
    //echo $query;die();
        $run = $this->pdo->prepare($query);
        $run->execute($where_array);

        while ($row = $run->fetch(PDO::FETCH_ASSOC)) {
            $records[] = $row;
        }

        return $records;
    }    
    
    function json_output($array){
        if(is_array($array)){
            header('Content-Type: application/json');
            echo json_encode($array);
        }
    }
    


	public function IndianFormat($date)
	{
		$indian_date = date('d-m-Y', strtotime($date));
		
		return $indian_date;
	}
        

}
?>
