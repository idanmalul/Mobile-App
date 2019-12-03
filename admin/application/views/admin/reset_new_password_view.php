<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="PonyX Admin Panel" />
        <meta name="author" content="" />

        <title>10k-Club - Reset Password</title>

        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Arimo:400,700,400italic">
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/fonts/linecons/css/linecons.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/fonts/fontawesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/bootstrap.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/xenon-core.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/xenon-forms.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/xenon-components.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/xenon-skins.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/custom.css">

        <script src="<?php echo base_url() ?>assets/js/jquery-1.11.1.min.js"></script>

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
                <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
                <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->


    </head>
<body class="page-body login-page">

	
	<div class="login-container">
	
		<div class="row">
	<div class="col-sm-3"></div>
			<div class="col-sm-6">
                            <script type="text/javascript">
                                jQuery(document).ready(function($)
					{
						// Reveal Login form
						setTimeout(function(){ $(".fade-in-effect").addClass('in'); }, 1);
                                        });
                            </script>
                            <script type="text/javascript">
                                function remove_class(id){
                                    document.getElementById(id).className = 'form-control input-dark';
                                    var divName = id.split('_');
                                    var div = divName[1];
                                }
                            </script>
                            
                            <!-- Errors container -->
				<div class="errors-container">
					
				</div>
                            <?php if(!empty($error)){ ?>
                            <div class="alert alert-danger">
                                <?php echo $error; ?>
                            </div>
                            <?php } ?>
	
				<!-- Add class "fade-in-effect" for login form effect -->
<!--                                <form action="<?php echo site_url('login') ?>" method="post" role="form" id="login" class="login-form fade-in-effect">-->
				    <form action="<?php echo site_url('login/reset_password'); ?>" role="form" id="form1" method="post" class="login-form fade-in-effect validate">
	
					<div class="login-header">
                                            <a href="<?php echo site_url(); ?>" class="logo">
<!--							<img src="<?php echo base_url(); ?>assets/images/logo_admin.png" alt="" width="200" />-->
						</a>
<!--						<h3>Topcoachingclasses</h3>-->
	
                                            <p>Reset Password</p>
					</div>
					<?php if($this->session->flashdata('msg')){ ?>
		<div class="alert-message alert-<?php echo $this -> session -> flashdata('msg-class');?>"> <?php echo $this -> session -> flashdata('msg'); ?> <span class="close-message"><span class="glyphicon glyphicon-remove"></span></span></div>	
		<?php } ?>
		<input type="hidden" name="admin_id" value="<?php echo $this->uri->segment(3);?>">         
		<input type="hidden" name="rand_code" value="<?php echo $this->uri->segment(4);?>">         
		<div class="form-group">
<!--                <label class="control-label">New Password</label>-->
                <input type="password" placeholder="New Password" id="new_pass" class="form-control input-dark" data-validate="required,minlength[6]" name="new_pass"  />
            </div>
            <div class="form-group">
<!--                <label class="control-label">Confirm Password</label>-->
                <input type="password" placeholder="Confirm Password" class="form-control input-dark" equalto="#new_pass" data-validate="required" name="confirm_pass" />
            </div>
                                    
	
		    <div class="form-group">
			<input type="submit" value="Reset" name="change" class="btn btn-dark btn-block">
		    </div>
	
					
	
				</form>
	
				
	
			</div>
        <div class="col-sm-3"></div>
	
		</div>
	
	</div>


<!-- Bottom Scripts -->
        <script src="<?php echo base_url() ?>assets/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url() ?>assets/js/TweenMax.min.js"></script>
        <script src="<?php echo base_url() ?>assets/js/resizeable.js"></script>
        <script src="<?php echo base_url() ?>assets/js/joinable.js"></script>
        <script src="<?php echo base_url() ?>assets/js/xenon-api.js"></script>
        <script src="<?php echo base_url() ?>assets/js/xenon-toggles.js"></script>
        <script src="<?php echo base_url() ?>assets/js/jquery-validate/jquery.validate.min.js"></script>
        <script src="<?php echo base_url() ?>assets/js/toastr/toastr.min.js"></script>


        <!-- JavaScripts initializations and stuff -->
        <script src="<?php echo base_url() ?>assets/js/xenon-custom.js"></script>



</body>
</html>
