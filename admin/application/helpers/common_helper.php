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

if(!function_exists('get_all_campaign'))
{
    function get_all_campaign()
    {

        $CI = & get_instance();
        if($CI->session->userdata('logged_in'))
        {
//          $where = array('story_status' => 2);
            $current_datetime = date('Y-m-d H:i:s');
            $query = "SELECT DISTINCT(csr.campaign_id) as active_camp,c.* FROM campaign c INNER JOIN campaign_story_relationship csr ON csr.campaign_id=c.id WHERE csr.schedule_date_time >= '$current_datetime' ORDER BY created_at DESC";
            $campaigns = $CI->project_model->get_query_result($query);
//            $campaigns = $CI->project_model->get_column_data_where('campaign', '', $where='');

            return $campaigns;

        }
        else 
        {

        }
    }   
}

if(!function_exists('get_story_by_campaign_id'))
{
    function get_story_by_campaign_id($id = 0)
    {

        $CI = & get_instance();
        if($CI->session->userdata('logged_in'))
        {

            $query = "SELECT s.id as story_id,s.story_title,csr.id as campaign_story_relationship_id,csr.campaign_id,c.reward,c.campaign_description FROM story s INNER JOIN campaign_story_relationship csr ON csr.story_id=s.id INNER JOIN campaign c ON c.id=csr.campaign_id WHERE csr.campaign_id='$id'";
            
            $stories = $CI->project_model->get_query_result($query);
           
            return $stories;
            
        }
        else 
        {

        }
    }   
}

if(!function_exists('get_campaign_viewer_count_by_campaign_id'))
{
    function get_campaign_viewer_count_by_campaign_id($id = 0)
    {

        $CI = & get_instance();
        if($CI->session->userdata('logged_in'))
        {

            $query = "SELECT SUM(su.story_viewer_count) as total_campaign_viewer_count FROM campaign c INNER JOIN story_user su ON su.campaign_id=c.id WHERE su.media_pk!='' AND su.media_status='ok' AND su.upload_status=1 AND su.campaign_id='$id'";
            // SELECT SUM(ps.story_viewer_count) as total_campaign_viewer_count FROM campaign c INNER JOIN post_story ps ON ps.campaign_id=c.id WHERE ps.media_pk!='' AND ps.media_status='ok' AND ps.campaign_id='$id'
            $get_campaign_viewer_count = $CI->project_model->get_query_result($query);
            if(!empty($get_campaign_viewer_count))
                return $get_campaign_viewer_count[0]->total_campaign_viewer_count;
            else 
                return 0;
            
        }
        else 
        {

        }
    }   
}

if(!function_exists('get_total_clicks_by_campaign_id'))
{
    function get_total_clicks_by_campaign_id($id = 0)
    {

        $CI = & get_instance();
        if($CI->session->userdata('logged_in'))
        {

            $query = "SELECT SUM(su.total_clicks) as campaign_total_clicks FROM campaign c INNER JOIN story_user su ON su.campaign_id=c.id WHERE su.media_pk!='' AND su.media_status='ok' AND su.upload_status=1 AND su.campaign_id='$id'";
            
            $get_campaign_total_clicks = $CI->project_model->get_query_result($query);
            if(!empty($get_campaign_total_clicks))
                return $get_campaign_total_clicks[0]->campaign_total_clicks;
            else 
                return 0;
            
        }
        else 
        {

        }
    }   
}

if(!function_exists('get_average_clicks_per_campaign_by_user'))
{
    function get_average_clicks_per_campaign_by_user($id = 0)
    {

        $CI = & get_instance();
        if($CI->session->userdata('logged_in'))
        {

            $query = "SELECT SUM(su.total_clicks) as user_total_clicks,COUNT(su.campaign_id) as user_total_campaign, round(SUM(su.total_clicks)/COUNT(su.campaign_id), 2) as clicks_average  FROM campaign c INNER JOIN story_user su ON su.campaign_id=c.id WHERE su.user_id='$id'";
            
            $get_average_clicks = $CI->project_model->get_query_result($query);
            if(!empty($get_average_clicks)){
                return ($get_average_clicks[0]->clicks_average)? $get_average_clicks[0]->clicks_average : 0.00;
            }else {
                return 0.00;
            }
            
        }
        else 
        {

        }
    }   
}

if(!function_exists('get_campaign_by_campaign_id'))
{
    function get_campaign_by_campaign_id($id=0)
    {

        $CI = & get_instance();
        if($CI->session->userdata('logged_in'))
        {
            $where = array('id' => $id);
            $campaign = $CI->project_model->get_column_data_where('campaign', '', $where);

            return $campaign;

        }
        else 
        {

        }
    }   
}

if(!function_exists('get_campaign_schedule_by_camp_id_story_id'))
{
    function get_campaign_schedule_by_camp_id_story_id($camp_id=0,$story_id=0)
    {

        $CI = & get_instance();
        if($CI->session->userdata('logged_in'))
        {
            $where = array('campaign_id' => $camp_id,'story_id' => $story_id);
            $campaign_schedule = $CI->project_model->get_column_data_where('campaign_story_relationship', '', $where);

            return $campaign_schedule;

        }
        else 
        {

        }
    }   
}

if(!function_exists('get_campaign_schedule_by_camp_id'))
{
    function get_campaign_schedule_by_camp_id($camp_id=0)
    {

        $CI = & get_instance();
        if($CI->session->userdata('logged_in'))
        {
            $where = array('campaign_id' => $camp_id);
            $campaign_schedule = $CI->project_model->get_column_data_where('campaign_story_relationship', '', $where);

            return $campaign_schedule;

        }
        else 
        {

        }
    }   
}

if(!function_exists('get_all_country'))
{
    function get_all_country()
    {

        $CI = & get_instance();
        if($CI->session->userdata('logged_in'))
        {
            // $where = array('campaign_id' => $camp_id,'story_id' => $story_id);
            $countries = $CI->project_model->get_column_data_where('country', '', $where='');

            return $countries;

        }
        else 
        {

        }
    }   
}

if(!function_exists('get_timezone_by_country_code'))
{
    function get_timezone_by_country_code($country_code='')
    {

        $CI = & get_instance();
        if($CI->session->userdata('logged_in'))
        {
            $where = array('country_code' => $country_code);
            $zone = $CI->project_model->get_column_data_where('zone', '', $where);
            if(!empty($zone))
            return $zone[0]->zone_name;
            else
            return false;

        }
        else 
        {

        }
    }   
}

if(!function_exists('get_sum_all_campaign_engagement_by_user'))
{
    function get_sum_all_campaign_engagement_by_user($userid=0)
    {

        $CI = & get_instance();
        if($CI->session->userdata('logged_in'))
        {
            $query = "SELECT su.*,c.campaign_name,c.campaign_description,s.story_title,s.story_image,s.story_description,s.media_type,u.first_name,u.last_name,u.full_name,u.username,u.profile_pic_url,csr.schedule_date_time,u.follower_count,(su.story_viewer_count/u.follower_count) as engagement,SUM(su.story_viewer_count/u.follower_count) as engagement_sum FROM story s INNER JOIN story_user su ON su.story_id=s.id INNER JOIN users u ON u.user_id=su.user_id INNER JOIN campaign_story_relationship csr ON csr.id=su.campaign_story_relationship_id INNER JOIN campaign c ON c.id=su.campaign_id WHERE su.user_id='$userid'";
            $engagement_sum_response = $CI->project_model->get_query_result($query);
            
            if(!empty($engagement_sum_response)){
                $engagement_sum = $engagement_sum_response[0]->engagement_sum;
            }else{
                $engagement_sum = 0;
            }

            return $engagement_sum;

        }
        else 
        {

        }
    }   
}

if(!function_exists('update_user_info'))
{
    function update_user_info($id=0)
    {
        
        $where = array("pk !=''" => NULL);  // ,"user_id in(34,723,786,1019)" => NULL
        $CI = & get_instance();
        $users = $CI->project_model->get_column_data_where('users', '', $where);
        if(!empty($users)){
            foreach ($users as $value) {
                $user_id = $value->user_id;
                $username = $value->username;
                $curl = curl_init();

                curl_setopt_array($curl, array(
                  CURLOPT_URL => "https://www.instagram.com/$username/?__a=1",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "GET",
                  CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"buyer_id\"\r\n\r\n15\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"shop_id\"\r\n\r\n3\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"bill_status\"\r\n\r\n4\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
                  CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                    "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
                    "postman-token: 5b24eccb-9783-8586-b719-7d4d417a699c"
                  ),
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                if ($err) {
                  echo "cURL Error #:" . $err;
                } else {
                    $result = json_decode($response);
                    if(!empty($result)){
                        $profile_pic_url_hd = $result->graphql->user->profile_pic_url_hd;
                        $edge_followed_by = $result->graphql->user->edge_followed_by->count;
                        $edge_follow = $result->graphql->user->edge_follow->count;
                        if(!empty($profile_pic_url_hd)){
                            $where = array('user_id' => $user_id);
                            $data = array('profile_pic_url'=>$profile_pic_url_hd);  // ,'follower_count'=>$edge_followed_by,'following_count'=>$edge_follow
                            $edit = $CI->project_model->update_data('users', $data, $where);
                        }
                    }
//                  echo $response;die();
                  
                }
                
            }
        }
        //print_r($users);die();
        return false;
        
    }
}

if(!function_exists('get_viewers_avg_by_user_id'))
{
    function get_viewers_avg_by_user_id($id = 0)
    {

        $CI = & get_instance();
        if($CI->session->userdata('logged_in'))
        {

            $query = "SELECT SUM(su.story_viewer_count) as total_viewer_count, COUNT(su.campaign_id) as no_of_approved_campaign, round(SUM(su.story_viewer_count)/COUNT(su.campaign_id), 2) as viewers_avg FROM campaign c INNER JOIN story_user su ON su.campaign_id=c.id WHERE su.media_pk!='' AND su.media_status='ok' AND su.upload_status=1 AND su.user_id='$id'";
            // SELECT SUM(ps.story_viewer_count) as total_campaign_viewer_count FROM campaign c INNER JOIN post_story ps ON ps.campaign_id=c.id WHERE ps.media_pk!='' AND ps.media_status='ok' AND ps.campaign_id='$id'
            $get_viewers_avg = $CI->project_model->get_query_result($query);
            if(!empty($get_viewers_avg))
                return $get_viewers_avg[0]->viewers_avg;
            else 
                return 0;
            
        }
        else 
        {

        }
    }   
}


        function user_eauth_all(){
            $CI = & get_instance();
            $where = array('user_id in(34)' => NULL);

            $users = $CI->project_model->get_column_data_where('users', '', $where='');
            if(!empty($users)){
                foreach ($users as $value) {
                    $user_id = $value->user_id;
                    $plaintext = base64_decode($value->password);
                    
                    //            $plaintext = 'My secret message 1234';
                    $password = '19scpr$06qwe';

                    // CBC has an IV and thus needs randomness every time a message is encrypted
                    $method = 'aes-256-cbc';

                    // Must be exact 32 chars (256 bit)
                    // You must store this secret random key in a safe place of your system.
                    $key = substr(hash('sha256', $password, true), 0, 32);
//                    echo "Password:" . $password . "\n";

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

//                    echo 'plaintext=' . $plaintext . "<br><br>";
//                    echo 'cipher=' . $method . "<br><br>";
//                    echo 'encrypted to: ' . $encrypted . "<br><br>";
//                    //            echo 'decrypted to: ' . $decrypted . "<br><br>";
//
//                    echo ''.$key;

                    $where = array('user_id' => $user_id);
                    $data = array('password'=>$encrypted,'token_value'=> base64_encode($key));
                    $edit = $CI->project_model->update_data('users', $data, $where);
//                    die();
                }
            }
            
        }
        function user_dauth($encrypted,$user_id){
            $CI = & get_instance();
            $where = array('user_id' => $user_id);

            $users = $CI->project_model->get_column_data_where('users', '', $where);
            $key = base64_decode($users[0]->token_value);
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
        
        function user_eauth($plaintext,$user_id){
            $CI = & get_instance();
                    
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

            $where = array('user_id' => $user_id);
            $data = array('password'=>$encrypted,'token_value'=> base64_encode($key));
            $edit = $CI->project_model->update_data('users', $data, $where); 
//            die();
               
            
        }
        
        function payouts_access_token(){
            
//                $url = "https://api.sandbox.paypal.com/v1/oauth2/token/";
                            $url = "https://api.paypal.com/v1/oauth2/token";
                $sandbox =  "QWIwSUVucGVTcnFjSVU2cnBtay15a1hUVkNTNHFsUTRjWm82aHpOdjhod3IyZVYxME5EVHRiZzh5MGp1NTZUclFQbnVyZjFNeEV1cVY1NHY6RU1EWENMV2dwUi1RZnV4cjJtQldOdFVIYTV6Rnc0X29HeTFvcHRWa3lnc0hlcEdVZ2t2N3A4Z2ZhT3JxYXNQLURyUDhwZGIxUHpxZ0ZCQ0Q=";
                $live =  "QVpRRmhfTE94MHoxM18xOXBkcXYzU2l3VzBZWUY4OGtIUi16a00weWNCZ3ZqYkR0ZzRKU19NbE5CYVZ2UnZSaF82aktLZUZhdWtZWTQ0SFo6RU8tTm01UWxFRThGa1RraWFoaGowYzVudWxJbEZXSmJwZ212Qi1oTlloYmw5YW5HVU1QQ21lUGE4REdmUjAzYy1QTjVYc2RTUEVKa1F5SUk=";
                $curl = curl_init();

                curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
//                    CURLOPT_SSL_VERIFYPEER => false,
                    //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "grant_type=client_credentials",
                CURLOPT_HTTPHEADER => array(
                "authorization: Basic ".$live,
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded",
                "postman-token: f308d7af-29cc-1532-a25d-bf3433d0ba05"
                ),
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);

                if ($err) {
                  echo "cURL Error #:" . $err;//die();
                } else {
                             // echo $response;die();
                    if(!empty($response)){
                        $response = json_decode($response);
                        return $response->access_token;
                    }
                }
        }
        
        function create_payouts($sent_primary_id,$email,$amount,$currency,$user_id,$campaign_id){
            $CI = & get_instance();
//            $url = "https://api.sandbox.paypal.com/v1/payments/payouts";
            $url = "https://api.paypal.com/v1/payments/payouts";
            
//            $email = 'rahul.tectum@gmail.com';
//            $email = "niv-buyer@10k-club.com";
//            $amount = 1;
            $currency ="USD";
            $access_token = payouts_access_token();
            $email_subject = "You have a payment of some amount.";
            $sender_batch_id = "batch-".microtime().$sent_primary_id;
            $sender_item_id = "item-".microtime().$sent_primary_id;
            
            $recipient_type = "EMAIL";
            $note = "Payout transaction";
            
            $request_payout = '{
              "sender_batch_header": {
                "email_subject": "'.$email_subject.'",
                "sender_batch_id": "'.$sender_batch_id.'"
              },
              "items": [
                {
                  "recipient_type": "'.$recipient_type.'",
                  "amount": {
                    "value": "'.$amount.'",
                    "currency": "'.$currency.'"
                  },
                  "receiver": "'.$email.'",
                  "note": "'.$note.'",
                  "sender_item_id": "'.$sender_item_id.'"
                }
              ]
            }';
            
//            echo '{
//              "sender_batch_header": {
//                "email_subject": "'.$email_subject.'",
//                "sender_batch_id": "'.$sender_batch_id.'"
//              },
//              "items": [
//                {
//                  "recipient_type": "EMAIL",
//                  "amount": {
//                    "value": "'.$amount.'",
//                    "currency": "'.$currency.'"
//                  },
//                  "receiver": "'.$email.'",
//                  "note": "Payout transaction",
//                  "sender_item_id": "'.$sender_item_id.'"
//                }
//              ]
//            }';die();
            $curl = curl_init();

            curl_setopt_array($curl, [CURLOPT_URL => $url,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS => '{
              "sender_batch_header": {
                "email_subject": "'.$email_subject.'",
                "sender_batch_id": "'.$sender_batch_id.'"
              },
              "items": [
                {
                  "recipient_type": "'.$recipient_type.'",
                  "amount": {
                    "value": "'.$amount.'",
                    "currency": "'.$currency.'"
                  },
                  "receiver": "'.$email.'",
                  "note": "'.$note.'",
                  "sender_item_id": "'.$sender_item_id.'"
                }
              ]
            }',
              CURLOPT_HTTPHEADER => array(
                'accept: application/json',
                'authorization: Bearer '.$access_token,
                'content-type: application/json'
              ),
              ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
              echo "cURL Error #:" . $err;
            } else {
                //echo $response;
                if(!empty($response)){
                    $data = array();
                    $data['payout_response'] = $response;
                    $response = json_decode($response);
                    $admin_id = $CI->session->userdata('admin_id');
                    $data['created_by'] = $admin_id;
                    $data['created_at'] = date('Y-m-d H:i:s');
                    $data['payout_request'] = $request_payout;
                    
                    $data['user_id'] = $user_id;
                    $data['campaign_id'] = $campaign_id;
                    $data['sent_primary_id'] = $sent_primary_id;
                    $data['email_subject'] = $email_subject;
                    $data['sender_batch_id'] = $sender_batch_id;
                    $data['receipient_type'] = $recipient_type;
                    $data['amount'] = $amount;
                    $data['currency'] = $currency;
                    $data['receiver'] = $email;
                    $data['note'] = $note;
                    $data['sender_item_id'] = $sender_item_id;
                    $data['payout_batch_id'] = $response->batch_header->payout_batch_id;
                    $data['batch_status'] = $response->batch_header->batch_status;
                    $data['links'] = $response->links[0]->href;
                    
                    $payment_id = $CI->project_model->insert_data('payment', $data);
//                    echo "Inserted ID is : ".$payment_id;
                    return $payment_id;
                    //return $response->access_token;
                }
            }
        }
        
    if(!function_exists('check_payout'))
    {
        function check_payout($sent_primary_id=0)
        {

            $CI = & get_instance();
            if($CI->session->userdata('logged_in'))
            {
                $where = array('sent_primary_id' => $sent_primary_id);
                $payment_details = $CI->project_model->get_column_data_where('payment', '', $where);

                return $payment_details;

            }
            else 
            {

            }
        }   
    }

?>
