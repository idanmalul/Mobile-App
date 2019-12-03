<style>


#cover-spin {
    position:fixed;
    width:100%;
    left:0;right:0;top:0;bottom:0;
    background-color: rgba(255,255,255,0.7);
    z-index:9999;
    display:none;
}

@-webkit-keyframes spin {
	from {-webkit-transform:rotate(0deg);}
	to {-webkit-transform:rotate(360deg);}
}

@keyframes spin {
	from {transform:rotate(0deg);}
	to {transform:rotate(360deg);}
}

#cover-spin::after {
    content:'';
    display:block;
    position:absolute;
    left:48%;top:40%;
    width:40px;height:40px;
    border-style:solid;
    border-color:black;
    border-top-color:transparent;
    border-width: 4px;
    border-radius:50%;
    -webkit-animation: spin .8s linear infinite;
    animation: spin .8s linear infinite;
}



</style>
<script>
var userid = localStorage.getItem('challenge_user_id');
if(!userid){
    window.location.href= BASE_URL+"login";
}
</script>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100" style="background-color: #000;">
				<form class="login100-form validate-challenge-form">
					<!-- <span class="login100-form-title p-b-26">
						Welcome
					</span> -->
					<span class="login100-form-title p-b-15">
						<!-- <i class="zmdi zmdi-font"></i> -->
						<img src="<?php echo base_url('webAssets/registration_asstes/images/logo.png'); ?>" height="100"/>
                                                
					</span>
                                            <span class="contact100-form-title">
                                                    Stay Secure
                                                </span>
                                    
                                        
                                <br>
                                <p style="color:#fff;font-size: 13px;text-align: justify;"> You are just one step away from officially being part of the 10K community! You have just received an OTP to either your email or phone number registered with your Instagram account. For security purposes, please execute the following steps: </p><br>
                                <p style="color:#fff;font-size: 13px;text-align: justify;">
1. Enter the OTP below<br>
2. Click Verify<br>
3. Enable two factor authentication on your Instagram account.<br>
4. Login to the 10K Club application with your Instagram username and password.
                                </p>
<span style="color:#fff;font-size: 10px;text-align: justify;">
                                    </span>
<!--                <span class="UsernameErrors"></span>
                <span class="UsernameSuccess"></span>-->
                <br><br>
					<div class="wrap-input100 validate-input" data-validate = "Please enter OTP">
						<input class="input100" type="text" name="verification_code">
						<span class="focus-input100" data-placeholder="Enter OTP"></span>
					</div>

<!--					<div class="wrap-input100 validate-input" data-validate="Enter password">
						<span class="btn-show-pass">
							<i class="zmdi zmdi-eye"></i>
						</span>
						<input class="input100" type="password" name="password">
						<span class="focus-input100" data-placeholder="Password"></span>
					</div>-->

					<div class="container-login100-form-btn">
						<div class="wrap-login100-form-btn">
							<div class="login100-form-bgbtn"></div>
							<button class="login100-form-btn">
								Verify OTP
							</button>
						</div>
					</div>

					<div class="text-center p-t-115">
						<!-- <span class="txt1">
							Don’t have an account?
						</span>

						<a class="txt2" href="#">
							Sign Up
						</a> -->
					</div>
				</form>
			</div>
		</div>
	</div>
	
        <div id="cover-spin"></div>
	<div id="dropDownSelect1"></div>