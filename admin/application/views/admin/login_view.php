<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="PonyX Admin Panel" />
        <meta name="author" content="" />

        <title>10k-Club - Login</title>

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
    <style type="text/css">
        .form-group.text-center.hover :hover {
            color: #4f81bd;
        }
    </style>
    <body class="page-body login-page">


        <div class="login-container">

            <div class="row">
<div class="col-sm-3"></div>
                <div class="col-sm-6">

                    <script type="text/javascript">
                        jQuery(document).ready(function ($)
                        {
                            // Reveal Login form
                            setTimeout(function () {
                                $(".fade-in-effect").addClass('in');
                            }, 1);


                            // Validation and Ajax action
                            $("form#login").validate({
                                rules: {
                                    email: {
                                        required: true,
                                        email: true
                                    },
                                    passwd: {
                                        required: true
                                    }
                                },
                                messages: {
                                    email: {
                                        required: 'Please enter your email.',
                                        email: 'Please enter a valid email.'
                                    },
                                    passwd: {
                                        required: 'Please enter your password.'
                                    }
                                }
                            });

                            // Set Form focus
                            $("form#login .form-group:has(.form-control):first .form-control").focus();
                        });
                    </script>
<?php if($this->session->flashdata('error_msg')){ ?>
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>
            </button>
            <strong><?php echo $this->session->flashdata('error_msg'); ?></strong>
        </div>
    </div>
</div>
<?php } ?>
<?php if($this->session->flashdata('success')){ ?>
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>
            </button>
            <strong><?php echo $this->session->flashdata('success'); ?></strong>
        </div>
    </div>
</div>
<?php } ?>
                    <?php if(!empty($error)){ ?>
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>
            </button>
            <strong><?php echo $error; ?></strong>
        </div>
    </div>
</div>
<?php } ?>
                    
                    <!-- Errors container -->
<!--                    <div class="errors-container">
                        <?php
                        if($this->session->flashdata('error_msg')){
                            echo $this->session->flashdata('error_msg');
                        }
                        ?>
                    </div>-->

                    <!-- Add class "fade-in-effect" for login form effect -->
                    <form method="post" role="form" id="login" class="login-form fade-in-effect" action="<?php echo site_url('login/index'); ?>">

                        <div class="login-header">
                            <!--a href="<?php echo site_url('login'); ?>" class="logo">
                                <img src="<?php echo base_url(); ?>assets/images/logo.png" alt="" width="80" />
                                <span>log in</span>
                            </a-->
                            <h1>10k-Club</h1>
                             <!-- <p>Dear user, log in to access the admin area!</p> --> 
<!--                            <img src="<?php echo base_url(); ?>assets/images/logo.png" alt="" width="200" />-->
                        </div>
                        
                        <!-- fake fields are a workaround for chrome autofill getting the wrong fields -->
                        <input style="display:none" type="text" name="fakeusernameremembered"/>
                        <input style="display:none" type="password" name="fakepasswordremembered"/>
                        
                        <div class="form-group">
                            <label class="control-label" for="email">Email</label>
                            <input type="email" class="form-control input-dark" name="email" id="email" autocomplete="off" />
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="passwd">Password</label>
                            <input type="password" class="form-control input-dark" name="passwd" id="passwd" autocomplete="off" />
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-dark  btn-block text-left">
                                <i class="fa-lock"></i>
                                Log In
                            </button>
                            
                        </div>
                        <div class="form-group text-center hover">
                            
                            <a href="<?php echo site_url('login/forgot_password'); ?>">Forgot your password?</a>
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