<?php

class DB {
    
    private $pdo = NULL;
    
    // For Android Notification
    private $android_GCM = 'AAAAq2scoI8:APA91bE_F5GPlZWkvCH4kQyC8oT6xwrzXdX4TcaHYOusK2ZM_p8h6tdQCDabSHtVyn-Z4GGIA_KW6nLBtfFzGSIiqLrbTw7l0UywczUfmhSzaeZz8PgKp18IrLjZFWwyWZ_AigUe2GFo4gzR1n37QHUef6EyeacIbg';
    
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
        define("FAVOURITE_IMAGES", WEBSITE."uploads/favourite_images/");
        //date_default_timezone_set('Asia/Kolkata');
        date_default_timezone_set('Asia/Jerusalem');
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
    function shorter($text, $chars_limit)
    {
        // Check if length is larger than the character limit
        if (strlen($text) > $chars_limit)
        {
            // If so, cut the string at the character limit
//            $new_text = substr($text, 0, $chars_limit);
            $new_text = mb_substr($text,0,$chars_limit,'utf-8');
            // Trim off white space
            $new_text = trim($new_text);
            // Add at end of text ...
            return $new_text . "...";
        }
        // If not just return the text as is
        else
        {
        return $text;
        }
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
    
    // For android notification
    function android_notification($gcm_id, $msg) {
        if (!empty($this->android_GCM)) {
            $registrationIds = array($gcm_id);
            //$message = array("msg" => $msg);

            $GOOGLE_API_KEY = $this->android_GCM;

            $fields = array
                (
                'registration_ids' => $registrationIds,
                'data' => $msg
            );
/*
            $headers = array
                (
                'Authorization: key=' . $GOOGLE_API_KEY,
                'Content-Type: application/json'
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            curl_close($ch);
			
			// print_r($result);

            return $result;  */
            //firebase server url to send the curl request
        $url = 'https://fcm.googleapis.com/fcm/send';
 
        //building headers for the request
        $headers = array(
            'Authorization: key=' . $GOOGLE_API_KEY,
            'Content-Type: application/json'
        );
 
        //Initializing curl to open a connection
        $ch = curl_init();
 
        //Setting the curl url
        curl_setopt($ch, CURLOPT_URL, $url);
        
        //setting the method as post
        curl_setopt($ch, CURLOPT_POST, true);
 
        //adding headers 
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
        //disabling ssl support
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        //adding the fields in json format 
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
 
        //finally executing the curl request 
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
 
        //Now close the connection
        curl_close($ch);
 
        //and return the result 
        return $result;
        }
    }
function sendnotification($driver_device_token, $message1)
{
            $url = 'https://fcm.googleapis.com/fcm/send';
            //if($statu == 1){
            $fields = array('registration_ids' => array($driver_device_token), 'data' => $message1);

            $GOOGLE_API_KEY = 'AAAAq2scoI8:APA91bE_F5GPlZWkvCH4kQyC8oT6xwrzXdX4TcaHYOusK2ZM_p8h6tdQCDabSHtVyn-Z4GGIA_KW6nLBtfFzGSIiqLrbTw7l0UywczUfmhSzaeZz8PgKp18IrLjZFWwyWZ_AigUe2GFo4gzR1n37QHUef6EyeacIbg';

            $headers = array('Authorization:key=' . $GOOGLE_API_KEY, 'Content-Type:application/json');
            // Open connection
            $ch = curl_init();

            // Set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);

            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Disabling SSL Certificate support temporarly
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

            // Execute post
            $result = curl_exec($ch);

            //print_r( $result);

            if ($result === FALSE) {
                    die('Curl failed: ' . curl_error($ch));
            }

            // Close connection
            curl_close($ch);
            //}
}
    // For IOS notification
    function push_iOS($token, $msg, $alert) {
        if (!empty($this->pem_Pro) && !empty($this->passPhrase)) {
            // Provide the Host Information.

            if (!empty($this->sandBox))
                $tHost = 'gateway.push.apple.com';
            else
                $tHost = 'gateway.sandbox.push.apple.com';

            $tPort = 2195;

            // Provide the Certificate and Key Data.

            if (!empty($this->sandBox))
                $tCert = $this->pem_Pro;
            else
                $tCert = $this->pem_Dev;

            // Provide the Private Key Passphrase

            $tPassphrase = $this->passPhrase;

            // Provide the Device Identifier (Ensure that the Identifier does not have spaces in it).

            $tToken = $token;

            // The message that is to appear on the dialog.

            $tAlert = $alert;

            // The Badge Number for the Application Icon (integer >=0).
            //            $tBadge = 8;
            // Audible Notification Option.

            $tSound = 'default';

            // The content that is returned by the LiveCode "pushNotificationReceived" message.

            $tPayload = 'Notification sent';

            // Create the message content that is to be sent to the device.

            $tBody['aps'] = array(
                'alert' => $tAlert,
                'msg' => $msg,
                //                'badge' => $tBadge,
                'sound' => $tSound,
            );

            $tBody ['payload'] = $tPayload;

            // Encode the body to JSON.

            $tBody = json_encode($tBody);

            // Create the Socket Stream.

            $tContext = stream_context_create();

            stream_context_set_option($tContext, 'ssl', 'local_cert', $tCert);

            // Remove this line if you would like to enter the Private Key Passphrase manually.

            stream_context_set_option($tContext, 'ssl', 'passphrase', $tPassphrase);

            // Open the Connection to the APNS Server.

            $tSocket = stream_socket_client('ssl://' . $tHost . ':' . $tPort, $error, $errstr, 30, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $tContext);

            // Check if we were able to open a socket.

           if (!$tSocket)
               exit("APNS Connection Failed: $error $errstr" . PHP_EOL);

            // Build the Binary Notification.

            $tMsg = chr(0) . chr(0) . chr(32) . pack('H*', $tToken) . pack('n', strlen($tBody)) . $tBody;

            // Send the Notification to the Server.

            $tResult = fwrite($tSocket, $tMsg, strlen($tMsg));

               // if ($tResult)
                   // echo 'Delivered Message to APNS' . PHP_EOL;
               // else
                   // echo 'Could not Deliver Message to APNS' . PHP_EOL;
                
            // Close the Connection to the Server.

            fclose($tSocket);
        }
    }
    
    function json_output($array){
        if(is_array($array)){
            header('Content-Type: application/json');
            echo json_encode($array);
        }
    }
    
    function send_sms($number, $sms_msg){
        $status = TRUE;
        
        $mobile_no = preg_replace("/[^0-9]/", "", $number);
        
        //Your authentication key
        $authKey = "";
        
        //Multiple mobiles numbers separated by comma
        $mobileNumber = $mobile_no;
        
        //Sender ID,While using route4 sender id should be 6 characters long.
        $senderId = "APTECH";
        
        //Your message to send, Add URL encoding here.
        $message = urlencode($sms_msg);
        
        //Define route 
        $route = "4";
        
        //Prepare you post parameters
        $postData = array(
            'authkey' => $authKey,
            'mobiles' => $mobileNumber,
            'message' => $message,
            'sender' => $senderId,
            'route' => $route
        );

        //API URL
        $url="https://control.msg91.com/sendhttp.php";
        
        // init the resource
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData
        ));


        //Ignore SSL certificate verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        
        //get response
        $output = curl_exec($ch);
        
        //Print error if any
        if(curl_errno($ch)) {
            $status = FALSE;
            $error = 'error:' . curl_error($ch);
        }
        curl_close($ch);
        
        if($status){
            return $output;
        } else {
            return $error;
        }
    }

	public function IndianFormat($date)
	{
		$indian_date = date('d-m-Y', strtotime($date));
		
		return $indian_date;
	}
        function public_encrypt($plaintext){
        $fp=fopen("../public_key.pem","r");
        $pub_key=fread($fp,8192);
        fclose($fp);
        openssl_get_publickey($pub_key);
        openssl_public_encrypt($plaintext,$crypttext, $pub_key);
        //echo 'hello <br>'.$crypttext;die();
        return(base64_encode($crypttext)); 
        }
	public function private_decrypt($encryptedext,$password=''){
		$fp=fopen("../private_key.pem","r");
		$priv_key=fread($fp,8192);
		fclose($fp);
//echo '<pre>';
	//	echo $priv_key;
		$private_key = openssl_get_privatekey($priv_key,$password);
		openssl_private_decrypt(base64_decode($encryptedext), $decrypted, $private_key);
		return $decrypted;
	}
        
        function user_dauth($encrypted,$user_id){
//            $CI = & get_instance();
//            $where = array('user_id' => $user_id);
//
//            $users = $CI->project_model->get_column_data_where('users', '', $where);
//            $key = base64_decode($users[0]->token_value);
            
            $query = "SELECT token_value FROM users WHERE user_id='$user_id'";
            
            $users = $this->query_result($query);
            if(!empty($users)){
                $key = base64_decode($users[0]['token_value']);
            
            //            $plaintext = 'My secret message 1234';
//            $password = '19scpr$06qwe';

            // CBC has an IV and thus needs randomness every time a message is encrypted
            $method = 'aes-256-cbc';

            // Must be exact 32 chars (256 bit)
            // You must store this secret random key in a safe place of your system.
//            $key = substr(hash('sha256', $password, true), 0, 32);
//            echo "Password:" . $password . "\n";

            // Most secure key
            //$key = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));

            // IV must be exact 16 chars (128 bit)
            $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);

            // Most secure iv
            // Never ever use iv=0 in real life. Better use this iv:
            // $ivlen = openssl_cipher_iv_length($method);
            // $iv = openssl_random_pseudo_bytes($ivlen);

            // av3DYGLkwBsErphcyYp+imUW4QKs19hUnFyyYcXwURU=
//            $encrypted = base64_encode(openssl_encrypt($plaintext, $method, $key, OPENSSL_RAW_DATA, $iv));

            // My secret message 1234
            $decrypted = openssl_decrypt(base64_decode($encrypted), $method, $key, OPENSSL_RAW_DATA, $iv);

//            echo 'plaintext=' . $plaintext . "<br><br>";
//            echo 'cipher=' . $method . "<br><br>";
//            echo 'encrypted to: ' . $encrypted . "<br><br>";
//            echo 'decrypted to: ' . $decrypted . "<br><br>";
//
//            echo ''.$key;die();
            return $decrypted;
        }
        }
        
        function user_eauth($plaintext,$user_id=0){
//            $CI = & get_instance();
                    
            //            $plaintext = 'My secret message 1234';
            $password = '19scpr$06qwe';

            // CBC has an IV and thus needs randomness every time a message is encrypted
            $method = 'aes-256-cbc';

            // Must be exact 32 chars (256 bit)
            // You must store this secret random key in a safe place of your system.
            $key = substr(hash('sha256', $password, true), 0, 32);
//            echo "Password:" . $password . "\n";

            // Most secure key
            //$key = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));

            // IV must be exact 16 chars (128 bit)
            $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);

            // Most secure iv
            // Never ever use iv=0 in real life. Better use this iv:
            // $ivlen = openssl_cipher_iv_length($method);
            // $iv = openssl_random_pseudo_bytes($ivlen);

            // av3DYGLkwBsErphcyYp+imUW4QKs19hUnFyyYcXwURU=
            $encrypted = base64_encode(openssl_encrypt($plaintext, $method, $key, OPENSSL_RAW_DATA, $iv));

            // My secret message 1234
            //            $decrypted = openssl_decrypt(base64_decode($encrypted), $method, $key, OPENSSL_RAW_DATA, $iv);

//            echo 'plaintext=' . $plaintext . "<br><br>";
//            echo 'cipher=' . $method . "<br><br>";
//            echo 'encrypted to: ' . $encrypted . "<br><br>";
//            //            echo 'decrypted to: ' . $decrypted . "<br><br>";
//
//            echo ''.$key;

//            $where = array('user_id' => $user_id);
//            $data = array('password'=>$encrypted,'token_value'=> base64_encode($key));
//            $edit = $CI->project_model->update_data('users', $data, $where);
            
            $where = array('user_id'=>$user_id);
            $data = array('password'=>$encrypted,'token_value'=> base64_encode($key));
            $this->update_records('users', $data, $where);
//            die();
        return $encrypted;
            
        }

}
?>
