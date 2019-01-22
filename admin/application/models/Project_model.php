<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project_model extends CI_Model {

        public function __construct()
        {
            // Call the CI_Model constructor
            parent::__construct();
            
        }

        public function get_query_result($sql)
        {
            $result = array();
            $query = $this->db->query($sql);
            
            if($query->num_rows()){
                $result = $query->result();
            }
            
            return $result;
        }
        
        public function get_records($table) {
            $result = array();
            $query = $this->db->get($table);
            
            if($query->num_rows()){
                $result = $query->result();
            }
            
            return $result;
        }
        
        public function get_records_where($table, $where, $column = '*') {
            $result = array();
            
            $query = $this->db->select($column)
                ->where($where)
                ->get($table);
            
            if($query->num_rows()){
                $result = $query->result();
            }
            
            return $result;
        }
        
        public function update_records($table, $where, $data) {
            $affected_rows = 0;
            
            $this->db->where($where);
            $this->db->update($table, $data);
            
            $affected_rows = $this->db->affected_rows();
            
            return $affected_rows;
        }
        
        public function get_records_join_where($table1, $table2, $id_table1, $id_table2, $where, $column = '*') {
            $result = array();
            
            $query = $this->db->select($column)
                ->from($table1)
                ->join($table2, $table1.'.'.$id_table1.' = '.$table2.'.'.$id_table2)
                ->where($where)
                ->get();
            
            if($query->num_rows()){
                $result = $query->result();
            }
            
            return $result;
        }
        
        function get_data_where_condition($table,$where)//fatch all data with where condition
	{
		$this->db->select('*');
		$this->db->from($table);
		$this->db->where($where);
		$query=$this->db->get();
		return $query->result();
	}
        //*************** Function for Update Data**************************//	
    
    function update_data($table,$data,$where) {
        $this->db->where($where);
        $this->db->update($table,$data);
    }
//***********************End Update Data******************************//	
//*************** Function for Insert Data**************************//	
    
    function insert_data($table,$data) {
        $sql = $this->db->insert_string($table,$data);
        $this->db->query($sql);
        $last_id = $this->db->insert_id();
        return $last_id;
    }
//***********************End Insert Data******************************//
   
    //  //for count records for specific part
    //***************************************************************//    
    function count_records_where($table, $where = '', $groupby = '') {
        $this->db->select('*');
        $this->db->from($table);
        if ($where != '') {
            $this->db->where($where);
        }
        if ($groupby != '') {
            $this->db->group_by($groupby);
        }
        $query = $this->db->get();
        return $query->num_rows();
    }

//***************************************************************//    
    //  // for count all records
    //***************************************************************//    
    function count_records($table) {
        $count = $this->db->count_all($table);
        return $count;
    }
    public function get_column_data_where($table,$column='',$where='',$orderby='') {
        if($column !='')
        {
            $this->db->select($column);
        }
        else
        {
            $this->db->select('*');
        }
        $this->db->from($table);
        if($where !='')
        {
            $this->db->where($where);
        }
        if($orderby !='')
        {
            $this->db->order_by($orderby,'ASC');
        }
        $query = $this->db->get();
        return $query->result();
    }
    public function get_column_data_where_single($table,$column='',$where='',$orderby='') {
        if($column !='')
        {
            $this->db->select($column);
        }
        else
        {
            $this->db->select('*');
        }
        $this->db->from($table);
        if($where !='')
        {
            $this->db->where($where);
        }
        if($orderby !='')
        {
            $this->db->order_by($orderby,'ASC');
        }
        $query = $this->db->get();
        return $query->row();
    }
    //------------------Delete Record-------------------------//
    
    function delete_record($table,$where){
        $this->db->delete($table, $where);
        if (!$this->db->affected_rows()) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    //--------------End Delete Record-------------------------//

    //--------------------Join Two Table---------------------------//
    
    function get_record_join_two_table($table1,$table2,$id1,$id2,$column='',$where='',$groupby='',$orderby=''){
        if($column !='')
        {
            $this->db->select($column);
        }
        else
        {
            $this->db->select('*');
        }    
        $this->db->from($table1);
        $this->db->join($table2,$table2.'.'.$id2.'='.$table1.'.'.$id1);        
        if($where !='')
        {
            $this->db->where($where);
        }
        if($groupby != ''){
            $this->db->group_by($groupby);
        }
		if($orderby!='') {
                $this->db->order_by($orderby, 'desc');
        }
        $query=$this->db->get();
        return $query->result();
    }
     
    // this function is used for multiple join operations
	function get_all_list_join($star, $table_name, $table_foreign_key_1, $table_foreign_key_2, $join_type, $order_by = "", $group_by = "", $where = "", $having = "", $limit = "", $where_in = "") {
        $this->db->select($star);
        $this->db->from($table_name[0]);
        $j = 1;
        for ($i = 0; $i < count($table_foreign_key_1); $i++) {
            $this->db->join($table_name[$j], "$table_foreign_key_1[$i] = $table_foreign_key_2[$i]", $join_type[$i]);
            $j++;
        }

        if (!empty($where)) {
            $this->db->where($where);
        }

        if (!empty($where_in)) {
            foreach ($where_in as $key => $value) {
                $dj_genres = trim($value, "'");
                $dj_genres_array = explode(",", $dj_genres);
                $this->db->where_in($key, $dj_genres_array);
            }
        }

        if (!empty($order_by)) {
            $this->db->order_by($order_by[0], $order_by[1]);
        }

        if (!empty($group_by)) {
            $this->db->group_by($group_by);
        }

        if (!empty($having)) {
            $this->db->having($having);
        }

        if (!empty($limit)) {
            $this->db->limit($limit[0], $limit[1]);
        }
        
        $query = $this->db->get();
       // echo $this->db->last_query();
        if ($query->num_rows() > 0) {

            foreach ($query->result() as $v) {
                $data[] = $v;
            }
            return $data;
        }
    }

    function getUserDetails(){
 
    $response = array();
 
    // Select record
    $this->db->select('sap_code,no_of_permission,name,email,mobile_no,center_name,city,zone,country,brand_name,current_activate_device,created_at');
    $q = $this->db->get('users');
    // echo $this->db->last_query();die();
    $response = $q->result_array();
    // echo "<pre>";print_r($response);die();
    return $response;
  }

  public function getUserDetails_where($table, $where) {
            $result = array();
            
            $query = $this->db->select('sap_code,no_of_permission,name,email,mobile_no,center_name,city,zone,country,brand_name,current_activate_device,created_at')
                ->where($where)
                ->get($table);
            
            if($query->num_rows()){
                $result = $query->result_array();
            }
            
            return $result;
        }


}