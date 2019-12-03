<?php
require './db.class.php';

class EditUserFavourite extends DB {
    
    function edit_user_favourite() {
//        $response = array('status' => 'true', 'message' => $_REQUEST);
//        $this->json_output($response);        exit();
        if(!empty($_REQUEST['user_id'])){
                    $user_id = $_REQUEST['user_id'];
                    $where = array('user_id'=>$user_id);
                    $check_user = $this->get_record_where('users', $where);
                    if(empty($check_user)){
                       $response = array('status' => 'false', 'message' => 'User not found!');
                       $this->json_output($response);
                       exit();
                    }
                    
                    $json = $_REQUEST['favourites'];
                    $dataArr = json_decode($json);
                    $favouritesIds_arr = array();
                    $favouritesNames_arr = array();
                    if (!empty($dataArr)) {
                        $i = 0;
                        foreach ($dataArr as $value) {
                            $i++;
                            $favouritesIds_arr[] = $value->favourite_id;
                            $favouritesNames_arr[] = $value->favourite_name;
                            $favourites_id = implode(',', $favouritesIds_arr);
                            $favourites_name = implode(',', $favouritesNames_arr);
                            $data = array('favourites_id'=>$favourites_id,'favourites'=>$favourites_name);
                            $update_table = $this->update_records('users', $data, $where);
                        }
                        $response = array('status' => 'true', 'message' => 'Favourites successfully updated.');
                        
                    }else{
                        $response = array('status' => 'false', 'message' => 'Favourites record not found!');
                    }
                    
                    
                    
        } else {
            $response = array('status' => 'false', 'message' => 'Invalid request parameter');
        }
        $this->json_output($response);
    }
}

$edit = new EditUserFavourite();
$edit->edit_user_favourite();
