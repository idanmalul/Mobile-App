<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('humanTiming'))
{
    function humanTiming ($time)
    {
        $time = time() - $time; // to get the time since that moment

        $tokens = array (

            31536000 => 'year',

            2592000 => 'month',

            604800 => 'week',

            86400 => 'day',

            3600 => 'hour',

            60 => 'minute',

            1 => 'second'

        );

        foreach ($tokens as $unit => $text) {

            if ($time < $unit) continue;

            $numberOfUnits = floor($time / $unit);

            return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');

        }

    }
}

if(!function_exists('clean'))
{
    function clean($string) {
    $string = str_replace(' ', '_', $string); // Replaces all spaces with hyphens.

    return preg_replace('/[^A-Za-z0-9\_-]/', '', $string); // Removes special chars.
    }
}

function removeFromString($str, $item) {
    $parts = explode(',', $str);

    while(($i = array_search($item, $parts)) !== false) {
        unset($parts[$i]);
    }

    return implode(',', $parts);
}

if(!function_exists('active_favorite_list'))
{
    function active_favorite_list($favourite_id=0)
    {

        $CI = & get_instance();
        if($CI->session->userdata('logged_in'))
        {
            $where = array('favourite_id' => $favourite_id);

            $favouriteData = $CI->project_model->get_column_data_where('favourites', '', $where);

            return $favouriteData;

        }
        else 
        {

        }
    }   
}

if(!function_exists('get_all_users'))
{
    function get_all_users($user_id=0)
    {

        $CI = & get_instance();
        if($CI->session->userdata('logged_in'))
        {
            $where = array('is_profile_updated' => 1);

            $users = $CI->project_model->get_column_data_where('users', '', $where);

            return $users;

        }
        else 
        {

        }
    }   
}

if(!function_exists('get_users_by_story_id'))
{
    function get_users_by_story_id($story_id=0)
    {

        $CI = & get_instance();
        if($CI->session->userdata('logged_in'))
        {
            $where = array('is_profile_updated' => 1);
//            $query = "SELECT s.* FROM story s INNER JOIN story_user su ON su.story_id=s.id WHERE su.user_id='$user_id'";
            $query = "SELECT u.user_id,u.full_name FROM story s INNER JOIN story_user su ON su.story_id=s.id INNER JOIN users u ON u.user_id=su.user_id WHERE su.story_id='$story_id'";
            
            $users = $CI->project_model->get_query_result($query);
           // print_r($users);die();
//            $users = $CI->project_model->get_column_data_where('users', '', $where);

            return $users;

        }
        else 
        {

        }
    }   
}

if(!function_exists('get_all_stories'))
{
    function get_all_stories()
    {

        $CI = & get_instance();
        if($CI->session->userdata('logged_in'))
        {
//          $where = array('story_status' => 2);
            $stories = $CI->project_model->get_column_data_where('story', '', $where='');

            return $stories;

        }
        else 
        {

        }
    }   
}

if(!function_exists('get_user_by_user_id'))
{
    function get_user_by_user_id($id = 0)
    {

        $CI = & get_instance();
        if($CI->session->userdata('logged_in'))
        {
            $where = array('user_id' => $id);
            $user = $CI->project_model->get_column_data_where('users', '', $where);

            return $user[0];

        }
        else 
        {

        }
    }   
}

if(!function_exists('get_story_by_story_id'))
{
    function get_story_by_story_id($id = 0)
    {

        $CI = & get_instance();
        if($CI->session->userdata('logged_in'))
        {
            $where = array('id' => $id);
            $story = $CI->project_model->get_column_data_where('story', '', $where);

            return $story[0];

        }
        else 
        {

        }
    }   
}

?>
