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
var userid = localStorage.getItem('user_id');
//alert(userid);
if(!userid){
    window.location.href= BASE_URL+"login";
}
</script>
<body>

	<div class="container-contact100">
		<div class="wrap-contact100" style="background-color: #000;">
			<form class="contact100-form validate-form">
				<span class="contact100-form-title">
					<img src="<?php echo base_url('webAssets/registration_asstes/images/logo.png'); ?>" height="100">
					
				</span>
				<span class="contact100-form-title">
					Register
				</span>

				<div class="wrap-input100 input100-select"  >
					<span class="label-input100">GENDER</span>
					<div class="validate-input" data-validate="Please select gender.">
                                            <select class="selection-2 input100" name="gender" id="gender" >
							<option value="">Select Gender</option>
							<option value="Male">Male</option>
							<option value="Female">Female</option>
						</select>
					</div>
					<span class="focus-input100"></span>
				</div>

				<div class="wrap-input100 validate-input" data-validate="Please select DOB.">
					<span class="label-input100">DATE OF BIRTH</span>
					<input class="input100" type="date" name="date_of_birth">
					<span class="focus-input100"></span>
				</div>

				<!-- <div class="input-group">
				    <input type="tel" class="form-control">
				    <span class="input-group-addon">Tel</span>
			  	</div> -->

			  	<!-- <div class="form-group input-group">
    <input placeholder="123" type="text" readonly="" style="background-color: #bcb4b4; color:white; width: 50px">

    <input placeholder="Phone number" type="text" style="margin-left: -5px;">
    </div>   -->

    			<!-- <div class="input-group">
			    <input type="tel" class="form-control" id="tel">
			    <span class="input-group-addon">Tel</span>
			  </div> -->

				<div class="wrap-input100 validate-input " data-validate="Phone number is required">
					<span class="label-input100">PHONE NUMBER</span>
					<br>
					<input id="phone" name="phone" class="input100" type="tel">
					 <!-- <input id="tel"  class="input100" name="phone" type="tel"> -->
					<!-- <span class="input-group-addon">+672</span> -->
					<!-- <input class="input100" type="text" name="name" placeholder="+672   123456789"> -->
					<span class=""></span>
				</div>

				<div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
					<span class="label-input100">Email</span>
                                        <input class="input100" type="text" name="email" id="email" placeholder="Email adress">
					<span class="focus-input100"></span>
				</div>

				<div class="wrap-input100 validate-input" data-validate = "Address is required">
					<span class="label-input100">ADDRESS</span>
                                        <textarea class="input100" name="address" id="address" placeholder="Your street address"></textarea>
					<span class="focus-input100"></span>
				</div>
				<div class="wrap-input100 validate-input" data-validate="City is required">
					<span class="label-input100">POSTAL CODE</span>
                                        <input class="input100" type="text" name="postal_code" id="postal_code" placeholder="Enter your postal code" autocomplete="off">
					<span class="focus-input100"></span>
				</div>

				<div class="wrap-input100 validate-input" data-validate="City is required">
					<span class="label-input100">CITY</span>
					<input class="input100" type="text" name="city" placeholder="Enter your city here" autocomplete="off">
					<span class="focus-input100"></span>
				</div>

				<div class="wrap-input100 validate-input" data-validate="State is required">
					<span class="label-input100">STATE</span>
					<input class="input100" type="text" name="state" placeholder="Enter your state here" autocomplete="off">
					<span class="focus-input100"></span>
				</div>

				<div class="row " style="color: #fff;" >

                	<div class="col-md-12 validate-input" data-validate="Please select at least one.">
                                <input type="radio" name="radio1" value="1" > Use instagram password<br/>
                                <input type="radio" name="radio1" value="2" > Create new password
                        </div>
<!--                	<div class="col-md-6">
                		
                	</div>-->
                </div>
                        <div id="create_new_pass" style="display: none;">
               <div class="wrap-input100 validate-input" data-validate="Username is required"><br/><br/>
					<span class="label-input100">USERNAME</span>
                                        <input class="input100" type="text" name="username" id="username" placeholder="Enter username" autocomplete="off">
					<span class="focus-input100"></span>
				</div>

				<div class="wrap-input100 validate-input" data-validate="Password is required">
					<span class="label-input100">PASSWORD</span>
                                        <input type="password" class="input100" name="password" id="password" placeholder="Enter password" autocomplete="off">
					<span class="focus-input100"></span>
				</div>
                </div>

				<div class="container-contact100-form-btn">
					<div class="wrap-contact100-form-btn">
						<div class="contact100-form-bgbtn"></div>
						<button class="contact100-form-btn">
							<span>
								CREATE ACCOUNT
								<i class="fa fa-long-arrow-right m-l-7" aria-hidden="true"></i>
							</span>
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>


        <div id="cover-spin"></div>
	<div id="dropDownSelect1"></div>