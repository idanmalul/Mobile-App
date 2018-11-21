<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="Maltoob Admin Panel" />
	<meta name="author" content="" />
<!--<meta http-equiv="content-type" content="text/html; charset=utf-8"/>-->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>10k-Club - Dashboard</title>

	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Arimo:400,700,400italic">
	
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/css/fonts/linecons/css/linecons.css">
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/css/fonts/fontawesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/css/bootstrap.css">
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/css/xenon-core.css">
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/css/xenon-forms.css">
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/css/xenon-components.css">
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/css/xenon-skins.css">
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/css/custom.css">
        
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/js/multiselect/css/multi-select.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" integrity="sha256-rDWX6XrmRttWyVBePhmrpHnnZ1EPmM6WQRQl6h0h7J8=" crossorigin="anonymous" />

	<script src="<?php echo base_url() ?>assets/js/jquery-1.11.1.min.js"></script>
<!--        <div id="google_translate_element"></div><script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: 'ar', layout: google.translate.TranslateElement.FloatPosition.TOP_LEFT, multilanguagePage: true}, 'google_translate_element');
}
</script><script type="text/javascript" src="http://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>-->
	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

<script src="https://www.google.com/jsapi?key={AIzaSyBZJgcLzaKwy4B4UYIhrAQDoDonThbiDBg}" type="text/javascript"></script>
<!-- Imported styles on this page -->

<!-- Bottom Scripts -->
<script src="<?php echo base_url() ?>assets/js/bootstrap.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/TweenMax.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/resizeable.js"></script>
<script src="<?php echo base_url() ?>assets/js/joinable.js"></script>
<script src="<?php echo base_url() ?>assets/js/xenon-api.js"></script>
<script src="<?php echo base_url() ?>assets/js/xenon-toggles.js"></script>

<script src="<?php echo base_url() ?>assets/js/datatables/js/jquery.dataTables.min.js"></script>

<!-- Imported scripts on this page -->
<script src="<?php echo base_url() ?>assets/js/datatables/dataTables.bootstrap.js"></script>
<script src="<?php echo base_url() ?>assets/js/jquery-validate/jquery.validate.js"></script>
<script src="<?php echo base_url() ?>assets/js/jquery-validate/additional-methods.js"></script>
<script src="<?php echo base_url() ?>assets/js/xenon-widgets.js"></script>
<script src="<?php echo base_url(); ?>assets/js/ckeditor/ckeditor.js"></script>
<script src="<?php echo base_url(); ?>assets/js/ckeditor/adapters/jquery.js"></script>
<!-- JavaScripts initializations and stuff -->
<script src="<?php echo base_url() ?>assets/js/xenon-custom.js"></script>
<script src="<?php echo base_url() ?>assets/js/maltoob-custom.js"></script>
<script src="<?php echo base_url() ?>assets/js/daterangepicker/daterangepicker.js"></script>
<script src="<?php echo base_url() ?>assets/js/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url() ?>assets/js/timepicker/bootstrap-timepicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/moment.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/combodate.js"></script>
<script src="<?php echo base_url() ?>assets/js/colorpicker/bootstrap-colorpicker.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/formwizard/jquery.bootstrap.wizard.min.js"></script>
<script src="<?php echo base_url() ?>assets/js/multiselect/js/jquery.multi-select.js"></script>
<!--<script src="<?php echo base_url() ?>assets/js/selectboxit/jquery.selectBoxIt.min.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js" integrity="sha256-eZNgBgutLI47rKzpfUji/dD9t6LRs2gI3YqXKdoDOmo=" crossorigin="anonymous"></script>
</head>
<!-- Page Loading Overlay -->
<!--<div class="page-loading-overlay">
    <div class="loader-2"></div>
</div>-->