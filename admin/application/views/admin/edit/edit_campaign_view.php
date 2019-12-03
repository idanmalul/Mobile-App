<style>
	span.errormsg {
  color:#CC3F44;
 font-size:12px;
  padding-top:5px;
}

</style>
<?php if($this->session->flashdata('error') || !empty($error)){ ?>
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                    <strong><?php if($this->session->flashdata('error')){ echo $this->session->flashdata('error'); }else{ echo $error; }  ?></strong>
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
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title">
            <?php if(!empty($campaign_detail)) echo 'Edit'; else echo 'Add'; ?> Campaign
        </div>
    </div>
        
    <div class="panel-body">
        <form action="<?php if(!empty($campaign_detail)) echo site_url('admin/edit_campaign'); else echo site_url('admin/add_campaign'); ?>" role="form" id="form1" name="form1" method="post" enctype="multipart/form-data" class="validate">

             <div class="form-group">
                <label class="control-label">Title</label>
                <input type="text" name="campaign_name" placeholder="" class="form-control" data-validate="required" value="<?php if(!empty($campaign_detail)){ echo $campaign_detail[0]->campaign_name; }else{ if(isset($_POST['campaign_name'])){ echo $_POST['campaign_name']; } }?>" />
                
            </div>
             <div class="form-group">

                <label class="control-label">Description</label>

                <textarea name="campaign_description" id="" class="form-control ck_textarea_visible" placeholder="" rows="5" data-validate="required"><?php if(!empty($campaign_detail)){ echo trim($campaign_detail[0]->campaign_description); }else{ if(isset($_POST['campaign_description'])){ echo trim($_POST['campaign_description']); } }?></textarea>

            </div>
            
            <div class="form-group">
                <label class="control-label">Reward</label>
                <input type="text" name="reward" placeholder="" class="form-control" data-validate="required" value="<?php if(!empty($campaign_detail)){ echo $campaign_detail[0]->reward; }else{ if(isset($_POST['reward'])){ echo $_POST['reward']; } }?>" />
                
            </div>
            
            <div class="form-group">
                <label class="control-label">Set Limit for users</label>
                <input type="text" name="campaign_approved_user_limit" placeholder="" class="form-control" data-validate="required,number" value="<?php if(!empty($campaign_detail)){ echo $campaign_detail[0]->campaign_approved_user_limit; }else{ if(isset($_POST['campaign_approved_user_limit'])){ echo $_POST['campaign_approved_user_limit']; } }?>" />
                
            </div>
            
            <div class="form-group">
                <label class="control-label">Country Timezone</label>
                <select id="country_code" class="form-control input-dark" name="country_code" data-validate="required" >
                    <option value="" hidden="">--Select Country Timezone--</option>
				<?php $get_country = get_all_country(); foreach ($get_country as $value) { ?>
                    <option value="<?php echo $value->country_code; ?>" <?php if($campaign_detail[0]->country_code==$value->country_code){ echo 'selected="selected"'; } ?>><?php echo $value->country_name.' ('.get_timezone_by_country_code($value->country_code).')'; ?></option>
				<?php } ?>
				</select>
                
            </div>
<!--            <div class="form-group">
									<label class="col-sm-3 control-label">Date &amp; Time Picker</label>
									
									<div class="col-sm-4">
										
										<div class="date-and-time">
											<input type="text" class="form-control datepicker" data-format="D, dd MM yyyy">
											<input type="text" class="form-control timepicker" data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" />
										</div>
									</div>
								</div>-->
            <!--<br/><br/>-->
            
<!--            <div class="field_wrapper">
    <div>
        <input type="text" name="field_name[]" value=""/>
        <a href="javascript:void(0);" class="add_button" title="Add field"><img src="add-icon.png"/></a>
    </div>
</div>-->
            <!--<br/><br/>-->
            
            
            <div class="row field_wrapper">
                
                <?php $campaign_schedules = get_campaign_schedule_by_camp_id($campaign_detail[0]->id);
                $count = count($campaign_schedules);
                if(!empty($campaign_schedules)){
                foreach ($campaign_schedules as $key => $sch) {
                    $date = date('d-m-Y', strtotime($sch->schedule_date_time));
                    $time = date('h:i A', strtotime($sch->schedule_date_time));
                    ?>
    <div class="col-md-12">
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label">Select Story</label>
                
                <select id="story_id" class="form-control input-dark" name="story_id_arr_update[]" data-validate="required" disabled="" >
                    <option value="" hidden="">--Select Story--</option>
				<?php $active_story = get_all_stories(); foreach ($active_story as $value) { ?>
                                <option value="<?php echo $value->id; ?>"<?php if(!empty($campaign_schedules)){    
         if($value->id==$sch->story_id){ echo "selected='selected'"; }
     }else{ if(isset($_POST['story_id_arr'])) { foreach ($_POST['story_id_arr'] as $cs) { if($value->id==$cs){ echo "selected='selected'"; } } }}?>><?php echo addslashes($value->story_title); ?></option>
				<?php } ?>
				</select>
                 
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                    <label class="control-label">Date &amp; Time</label>
                    <div class="date-and-time">
                        <input type="text" id="schedule_date" name="schedule_date_arr_update[]" class="form-control datepicker" data-format="dd-mm-yyyy" data-validate="required" value="<?php if(!empty($date)) echo $date;?>">
                            <input type="text" id="schedule_time" name="schedule_time_arr_update[]" class="form-control timepicker" data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" value="<?php if(!empty($time)) echo $time;?>"/>
                    </div>
            </div>
        </div>
        <!--<div class="col-md-2">-->
        <?php if($key+1 == $count) { ?>
            <a style="margin-top:25px;" href="javascript:void(0);" class="col-md-1 add_button" title="Add field"><img src="<?php echo base_url(); ?>assets/images/add-icon.png"/></a>
        <?php } ?>
        <!--</div>-->
    </div>
 <?php 
                }}
            ?>               
</div>

                         
            
<!--            <div class="row field_wrapper">
    <div class="col-md-12">
        <div class="col-md-5">
            <div class="form-group">
                <label class="control-label">Select Story</label>
                <?php if(!empty($campaign_detail)) $already_story_user = get_users_by_story_id($campaign_detail[0]->id); ?>
                <select id="story_id" class="form-control input-dark" name="story_id_arr[]" data-validate="required" >
                    <option value="" hidden="">--Select Story--</option>
				<?php $active_story = get_all_stories(); foreach ($active_story as $value) { ?>
                                <option value="<?php echo $value->id; ?>"<?php if(!empty($already_story_user)){     foreach ($already_story_user as $su) {
         if($value->id==$su->user_id){ echo "selected='selected'"; }
     } }else{ if(isset($_POST['user_id'])) { if($value->id==$_POST['user_id']){ echo "selected='selected'"; } }}?>><?php echo addslashes($value->story_title); ?></option>
				<?php } ?>
				</select>
                 
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                    <label class="control-label">Date &amp; Time</label>
                    <div class="date-and-time">
                        <input type="text" id="schedule_date" name="schedule_date_arr[]" class="form-control datepicker" data-format="dd-mm-yyyy" data-validate="required">
                            <input type="text" id="schedule_time" name="schedule_time_arr[]" class="form-control timepicker" data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" />
                    </div>
            </div>
        </div>
            <a style="margin-top:25px;" href="javascript:void(0);" class="col-md-1 add_button" title="Add field"><img src="<?php echo base_url(); ?>assets/images/add-icon.png"/></a>
    </div>
                
</div>-->
<!--            <div class="form-group">

                <label class="control-label">When to post</label>

                <input type="datetime-local" name="post_datetime" placeholder="" class="form-control" value="<?php if(!empty($campaign_detail)){ $date = date('Y-m-d\Th:i', strtotime($campaign_detail[0]->post_datetime)); echo $date; }else{ if(isset($_POST['post_datetime'])){ echo $_POST['post_datetime']; } }?>" data-validate="required" /><br>

                <select class="form-control input-dark" name="post_timezone" data-validate="">

                    <option disabled="" selected="" hidden="">--Select Timezone--</option>
                    
                    <option <?php if(!empty($campaign_detail)){ if($campaign_detail[0]->post_timezone == "Israel/Jerusalem (UTC/GMT +02:00)"){ echo "selected='selected'"; } }  ?> value="Israel/Jerusalem (UTC/GMT +02:00)">Israel/Jerusalem (UTC/GMT +02:00)</option>
                    <option <?php if(!empty($campaign_detail)){ if($campaign_detail[0]->post_timezone == "Asia/Calcutta (UTC/GMT +05:30)"){ echo "selected='selected'"; } }  ?> value="Asia/Calcutta (UTC/GMT +05:30)">Asia/Calcutta (UTC/GMT +05:30)</option>
                    
                </select>

            </div>-->

            
            
            <div class="form-group">
                <!--<label class="control-label">You can schedule your posts for up to a month.</label><br><br>-->
                <?php if(!empty($campaign_detail)){ ?>
                <input type="hidden" name="campaign_id" value="<?php echo $campaign_detail[0]->id; ?>" class="form-control">

                <input type="submit" name="submit" value="Update" class="btn btn-success">
                <?php } else { ?>
                <!--<input type="submit" name="submit_schedule" value="Schedule" class="btn btn-success" >-->

                <input type="submit" name="submit" id="submit_btn" value="Submit" class="btn btn-success" >
<!--                <input type="submit" name="submit_post" value="Post Now" class="btn btn-default" style="border: 1px solid; border-color: #396C67 !important;">-->

                <!--<input type="submit" name="submit_save" value="Save as Draft" class="btn btn-basic">-->
                <?php } ?>
                
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function(){
    
    
    
                                    
    var maxField = 10; //Input fields increment limitation
    var addButton = $('.add_button'); //Add button selector
    var wrapper = $('.field_wrapper'); //Input field wrapper
    var fieldHTML = '<div><input type="text" name="field_name[]" value=""/><a href="javascript:void(0);" class="remove_button"><img src="<?php echo base_url(); ?>assets/images/remove-icon.png"/></a></div>'; //New input field html 
    var x = 1; //Initial field counter is 1
    
    //Once add button is clicked
    $(addButton).click(function(e){
        //Check maximum number of input fields
        if(x < maxField){ 
            var finalHtml = '<div class="col-md-12"><div class="col-md-5"><div class="form-group"><label class="control-label">Select Story</label><?php if(!empty($campaign_detail)) $already_story_user = ""; ?><select id="story_id_'+x+'" class="form-control input-dark campaign_err" name="story_id_arr[]" ><option value="" hidden="">--Select Story--</option><?php $active_story = get_all_stories(); foreach ($active_story as $value) { ?><option value="<?php echo $value->id; ?>"<?php if(!empty($already_story_user)){ foreach ($already_story_user as $su) { if($value->id==$su->user_id){ echo "selected='selected'"; } } }else{ if(isset($_POST["user_id"])) { if($value->id==$_POST["user_id"]){ echo "selected='selected'"; } }}?>><?php echo addslashes($value->story_title); ?></option><?php } ?></select></div></div><div class="col-md-5"><div class="form-group"><label class="control-label">Date &amp; Time</label><div class="date-and-time"><input type="text" id="schedule_date_'+x+'" name="schedule_date_arr[]" class="form-control datepicker campaign_err" data-format="dd-mm-yyyy" ><input type="text" id="schedule_time_'+x+'" name="schedule_time_arr[]" class="form-control timepicker campaign_err" data-template="dropdown" data-show-seconds="true" data-default-time="11:25 AM" data-show-meridian="true" data-minute-step="5" data-second-step="5" /></div></div></div><a style="margin-top:25px;" href="javascript:void(0);" class="col-md-1 remove_button"><img src="<?php echo base_url(); ?>assets/images/remove-icon.png"/></a></div>';
            
            jQuery.validator.format($.trim(finalHtml));
            
            x++; //Increment field counter
            
            $(wrapper).append(finalHtml); //Add field html
            
            $('.campaign_err').each(function () {
                    $(this).rules("add", {
                        required: true
                    });
                });
                e.preventDefault();
        }
    });
    
    //Once remove button is clicked
    $(wrapper).on('click', '.remove_button', function(e){
        e.preventDefault();
        $(this).parent('div').remove(); //Remove field html
        x--; //Decrement field counter
    });
    
    $(document).on("focus", ".datepicker", function(){
        $(this).datepicker({
            format: 'dd-mm-yyyy'
        });
    });
    $(document).on("focus", ".timepicker", function(){
        $(this).timepicker();
    });
    
    $("#form1").validate({
            		rules:{
                 	Name:"required"
                }
            });
//    $(document).on("focus", ".story_id_arr", function(){
//        $("#form1").validate();
//    });
//    $(document).on("focus", ".schedule_date_arr", function(){
//        $("#form1").validate();
//    });
//    $('#form1').submit(function(){
//        if($(this).validate()){
//            return true;
//        }else{
//            return false;
//        }
//    });
//    $("#form1").validate({
//  submitHandler: function(form) {
//    // do other things for a valid form
//    form.submit();
//  }
//});

//$("#form1").submit(function(){
//    alert("yes");
//    var isFormValid = true;
//
//    $("input").each(function(){
//        if ($.trim($(this).val()).length == 0){
//            $(this).addClass("highlight");
//            isFormValid = false;
//        }
//        else{
//            $(this).removeClass("highlight");
//        }
//    });
//
//    if (!isFormValid) alert("Please fill in all the required fields (indicated by *)");
//
//    return isFormValid;
//});


/*
$('form#form1').on('submit', function(event) {
    
        //Add validation rule for dynamically generated name fields
    $('.story_id_arr').each(function() {
        //alert("yes");
        $(this).rules("add", 
            {
                required: true,
//                messages: {
//                    required: "Name is required",
//                }
            });
    });
    //Add validation rule for dynamically generated email fields
    $('.schedule_date_arr').each(function() {
        $(this).rules("add", 
            {
                required: true,
//                email: true,
//                messages: {
//                    required: "Email is required",
//                    email: "Invalid email",
//                  }
            });
    });
});  */
//if($("#form1").validate()){
//    alert("yes");
//}else{
//    alert("no");
//}

//$("#submit_btn").click(function(){
//    e.preventDefault();
//xuv();
//    $('.story_id_arr').attr('data-validate', 'required');
//        $('.schedule_date_arr').attr('data-validate', 'required');
//    $('.validate-has-error').css('visibility','visible');
//        alert("y");
//        if($("#form1").valid()==false){
//            alert("false");
//                return false;
//            }else{
//               alert("true"); 
//            }
//        $("#form1").validate({
//  submitHandler: function(form) {
//    // do other things for a valid form
//    form.submit();
//  }
//});


//    });
/*
$('form#form1').on('submit', function(event) {

            // adding rules for inputs with class 'comment'
            $('.story_id_arr').each(function() {
                alert("yy");
                $(this).rules("add", 
                    {
                        required: true
                    })
            });            

            // prevent default submit action         
//            event.preventDefault();

            // test if form is valid 
            if($('#form1').validate().form()) {
                console.log("validates");
            } else {
                console.log("does not validate");
                return false;
            }
        })
*/
        // set handler for addInput button click
//        $("#addInput").on('click', addInput);

        // initialize the validator
//        $('#form1').validate();

});


</script>


