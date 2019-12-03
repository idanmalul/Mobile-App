<?php
require './db.class.php';

class UserForgot extends DB {

    function user_forgotpass() {
        if(!empty($_REQUEST['email'])){
        $email=$_REQUEST['email'];
        $where = array('email'=>$email);
        $check_email = $this->get_record_where('members', $where);
            	if(!empty($check_email)){

                $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
                $pass = '';
                //password is a string
                $alphaLength = strlen($alphabet) - 1;
                //put the length -1 in cache
                for ($i = 0; $i < 8; $i++) {
                        $n = mt_rand(0, $alphaLength);
                        $pass = $pass . $alphabet[$n];
                }
                $generate_pass = md5($pass);

		$where = array('id'=>$check_email[0]['id']);
		$data = array('password'=>$generate_pass);
		$update_pass = $this->update_records('members', $data, $where);

                $to = $email;
                $subject = "10K CLUB";

                $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                <html xmlns="http://www.w3.org/1999/xhtml">
                <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                <title>Untitled Document</title>
                </head>

                <body bgcolor="#f1f1f1">
                <table cellpadding="0" cellspacing="0" width="600" style="background:#fff; border:1px solid #cbcbcb; margin:0 auto; font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                        <thead class="header">
                        <tr>
                                <td style="background:#000000; height:62px; width:100%; padding:5px; border-bottom:1px solid #DDD;" valign="middle">
                                <!--<a href="#" style="margin-left:10px;"><img width="100" src="'.WEBSITE.'/assets/email_img/logo.png" alt="..."/></a>-->
				    <h1 style="color:#fff;text-align:center;">10K CLUB</h1>
                <!--<div class="social-icons" style="float:right; margin-top:10px;">
                        <a href="#" style="float:left; margin:0px 3px;"><img src="'.WEBSITE.'/assets/email_img/fb.png" alt="..."/></a>
                <a href="#" style="float:left; margin:0px 3px;"><img src="'.WEBSITE.'/assets/email_img/tw.png" alt="..."/></a>
                <a href="#" style="float:left; margin:0px 3px;"><img src="'.WEBSITE.'/assets/email_img/in.png" alt="..."/></a>

                            </div>-->
                        </td>
                    </tr>
                </thead>
                <tbody style=" border-bottom:1px solid #ddd;">
                        <tr>
                        <td style="padding:10px 15px;">
                                <h1 style="margin-bottom:0px; color:#010101;">Dear ' . ucwords($check_email[0]['name']) . '</h1>
                                </td>
                        </tr>
                        <tr>
                <td style="padding:10px 15px;">
                <p>Your new login credential for 10K CLUB :</p>
                <ul style="padding-left:20px;">

                        <li style="margin-bottom:10px;"><strong>Email:</strong>
                        <p style="margin:0; margin-top:5px;">'.$email.'</p>
                    </li>
                    <li style="margin-bottom:10px;"><strong>New Password:</strong><br />
                        <p style="margin:0;  margin-top:5px;">'.$pass.'</p>
                    </li>
                </ul>
            </td>
        </tr>
                        <tr>
                                <td style="padding:10px 15px;">
                                <span><strong>Thanks&Regards</strong></span><br/>
                                <span>10K CLUB</span>

                            </td>
                        </tr>
                        <tr>
                                <td style="background:#ddd; height:1px; width:100%;"></td>
                        </tr>
                    </tbody>

                    <tfoot style="background:#000000; text-align:center; color:#333;">
                        <tr>
                                <td style="color:#fff;"><p>Copyright © 2019 10K CLUB All right reserved</p></td>
                        <tr>
                    </tfoot>

                </table>
                </body>
                </html>';
//		echo $message;die();
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers.= "From:info@10k-club.com" . "\r\n";
                $send=mail($to,$subject,$message,$headers);
	    //Send the mails
                if ($send) {
                    // If success everythig is good send header as "OK" and user details
                    $response = array("status"=>"true","message" =>"Your password is sent to your registered email ID!");
                    //$this->response($this->json($response), 200);
                } else {
                    $response=array("status"=>"false","message" =>"Mail error: .$mail->ErrorInfo");
                    //$this->response($this->json($response), 200);
                }
        }
        else {
        $response=array("status"=>"false","message"=>"Invalid Email-Id");
        }
          
        } else {
            $response = array('status' => 'false', 'message' => 'Invalid request parameter');
        }
        $this->json_output($response);
    }

}

$forgot = new UserForgot();
$forgot->user_forgotpass();