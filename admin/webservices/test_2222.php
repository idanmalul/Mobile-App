<?php
$user_id = 2;
$story_id = 41;
$sent_primary_id = 309;
echo shell_exec('cd /domains/10k.tempurl.co.il/public_html/webservices');
                            //echo shell_exec('sudo chmod 777 -R /var/www/html/inst/vendor/mgp-25/instagram-php/sessions');
                            echo                            $output_including_status = shell_exec('php uploadStaticStory.php '.$user_id.' '.$story_id.' '.$sent_primary_id.' story');

                            $op = json_decode($output_including_status);
                            print_r($op);die();
