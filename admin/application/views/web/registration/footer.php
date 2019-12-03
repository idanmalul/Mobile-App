<!--===============================================================================================-->
	<script src="<?php echo base_url() ?>/webAssets/registration_asstes/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="<?php echo base_url() ?>/webAssets/registration_asstes/vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="<?php echo base_url() ?>/webAssets/registration_asstes/vendor/bootstrap/js/popper.js"></script>
	<script src="<?php echo base_url() ?>/webAssets/registration_asstes/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="<?php echo base_url() ?>/webAssets/registration_asstes/vendor/select2/select2.min.js"></script>
	<script>
		$(".selection-2").select2({
			minimumResultsForSearch: 20,
			dropdownParent: $('#dropDownSelect1')
		});
	</script>
<!--===============================================================================================-->
	<script src="<?php echo base_url() ?>/webAssets/registration_asstes/vendor/daterangepicker/moment.min.js"></script>
	<script src="<?php echo base_url() ?>/webAssets/registration_asstes/vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="<?php echo base_url() ?>/webAssets/registration_asstes/vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="<?php echo base_url() ?>/webAssets/registration_asstes/js/main.js"></script>

	<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-23581568-13"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-23581568-13');
</script>

<!-- <script type="text/javascript" async="" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->
<script src="<?php echo base_url() ?>webAssets/registration_asstes/build/js/intlTelInput.js"></script>
<script>
     var input = document.querySelector("#phone");
    window.intlTelInput(input, {
     
      utilsScript: "<?php echo base_url() ?>webAssets/registration_asstes/build/js/utils.js",
    });
  </script>  



</body>
</html>
