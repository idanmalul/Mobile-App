<?php
require './db.class.php';
echo 'yes';
shell_exec('cd /domains/10k.tempurl.co.il/public_html/webservices');
                //echo shell_exec('sudo chmod 777 -R /var/www/html/inst/vendor/mgp-25/instagram-php/sessions');
                echo $output_including_status = shell_exec('php get_story_viewer_count.php');
