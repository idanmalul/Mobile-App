<?php

require './db.class.php';

class GetUserProfile extends DB {

    function get_user_profile() {
        
//        $response = array('status' => 'false', 'message' => $_REQUEST);
//            
//        $this->json_output($response);exit();
            if(!empty($_REQUEST['username'])){
             $username = $_REQUEST['username'];
//	    $where = array('username' => $_REQUEST['username']);
//	    $get_user = $this->get_record_where('users', $where);
            $query = "Select * FROM users WHERE username='$username' or email='$username'";
           $get_user = $this->query_result($query);
	    if($get_user){
	    	foreach ($get_user as $key=>$value){
	    		
//                        if(!empty($value['user_profile_img'])){
//                        $value['user_profile_img']= USER_PROFILE_IMAGES.$value['user_profile_img'];
//                        }else{
//                            $value['user_profile_img']= '';
//                        }
	    		unset($value['password']);
                        $value['full_name'] = ucfirst($value['full_name']);
                        if(!empty($value['birthday'])){
                            $value['birthday'] = date('d/m/Y', strtotime($value['birthday']));
                        }
                        
//                        echo $value['birthday'];die();
                        $e=explode(' ', $value['full_name']);
                        if(!empty($e)){
                            if(empty($value['first_name']))
                            $value['first_name'] = ucfirst($e[0]);
                            if(empty($value['last_name']))
                            $value['last_name'] = ucfirst($e[1]);
                        }
                        $e=explode(',', $value['favourites']);
                        if(!empty($e)){
                            $value['favourites'] = $e;
                           // $value['last_name'] = $e[1];
                        }else{
                            
                        }
	    		$user_details = $value;
	    	}
                $favourites = array();
                $where = array('favourite_status' => 1);
                $favourites_data = $this->get_record_where('favourites', $where);
                if(!empty($favourites_data)){
                    foreach ($favourites_data as $key=>$value){
                        if(!empty($value['favourite_image'])){
                            $value['favourite_image']= FAVOURITE_IMAGES.$value['favourite_image'];
                        }else{
                            $value['favourite_image']= '';
                        }
                        $favourites[] = $value;
                    }
                }
                $company_list = array(
                    array('label'=>'University Beyond','value'=>'University Beyond'),
                    array('label'=>'Noodlelove','value'=>'Noodlelove'),
                    array('label'=>'AIM Hospitality','value'=>'AIM Hospitality'),
                    array('label'=>'Babel Community','value'=>'Babel Community'),
                    array('label'=>'Other','value'=>'Other'),
//                    array('label'=>'C','value'=>'C'),
//                    array('label'=>'D','value'=>'D'),
//                    array('label'=>'E','value'=>'E')
                );
        
                
	        $response = array('status' => 'true', 'message' => 'User Found', 'user_details' => $user_details, 'favourites'=>$favourites, 'company_list'=>$company_list);
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