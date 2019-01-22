<?php

require './db.class.php';

class GetUserProfile extends DB {

    function get_user_profile() {

            if(!empty($_REQUEST['username'])){
	    $where = array('username' => $_REQUEST['username']);
	    $get_user = $this->get_record_where('users', $where);
	    if($get_user){
	    	foreach ($get_user as $key=>$value){
	    		

	    		unset($value['password']);
                        $e=explode(' ', $value['full_name']);
                        if(!empty($e)){
                            $value['first_name'] = $e[0];
                            $value['last_name'] = $e[1];
                        }
                        $e=explode(',', $value['favourites']);
                        if(!empty($e)){
                            $value['favourites'] = $e;
                           // $value['last_name'] = $e[1];
                        }else{
                            
                        }
	    		$user_details = $value;
	    	}
                $where = array('favourite_status' => 1);
                $favourites = $this->get_record_where('favourites', $where);
                if(empty($favourites)){
                    $favourites = array();
                }
                
	        $response = array('status' => 'true', 'message' => 'User Found', 'user_details' => $user_details, 'favourites'=>$favourites);
	    } else {
	        $response = array('status' => 'false', 'message' => 'User Not Found!');
	    }    
        } else {
                $response = array('status' => 'false', 'message' => 'Invalid request parameter');
            }
        $this->json_output($response);
    }

}

$usr = new GetUserProfile();
$usr->get_user_profile();