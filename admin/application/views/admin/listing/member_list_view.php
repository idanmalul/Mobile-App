<style>
    table.dataTable.select tbody tr,
table.dataTable thead th:first-child {
  cursor: pointer;
}

/* Absolute Center Spinner */
.loading {
  position: fixed;
  z-index: 999;
  height: 2em;
  width: 2em;
  overflow: visible;
  margin: auto;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  display: none;
}

/* Transparent Overlay */
.loading:before {
  content: '';
  display: block;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0,0,0,0.3);
}

/* :not(:required) hides these rules from IE9 and below */
.loading:not(:required) {
  /* hide "loading..." text */
  font: 0/0 a;
  color: transparent;
  text-shadow: none;
  background-color: transparent;
  border: 0;
}

.loading:not(:required):after {
  content: '';
  display: block;
  font-size: 10px;
  width: 1em;
  height: 1em;
  margin-top: -0.5em;
  -webkit-animation: spinner 1500ms infinite linear;
  -moz-animation: spinner 1500ms infinite linear;
  -ms-animation: spinner 1500ms infinite linear;
  -o-animation: spinner 1500ms infinite linear;
  animation: spinner 1500ms infinite linear;
  border-radius: 0.5em;
  -webkit-box-shadow: rgba(0, 0, 0, 0.75) 1.5em 0 0 0, rgba(0, 0, 0, 0.75) 1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) 0 1.5em 0 0, rgba(0, 0, 0, 0.75) -1.1em 1.1em 0 0, rgba(0, 0, 0, 0.5) -1.5em 0 0 0, rgba(0, 0, 0, 0.5) -1.1em -1.1em 0 0, rgba(0, 0, 0, 0.75) 0 -1.5em 0 0, rgba(0, 0, 0, 0.75) 1.1em -1.1em 0 0;
  box-shadow: rgba(0, 0, 0, 0.75) 1.5em 0 0 0, rgba(0, 0, 0, 0.75) 1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) 0 1.5em 0 0, rgba(0, 0, 0, 0.75) -1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) -1.5em 0 0 0, rgba(0, 0, 0, 0.75) -1.1em -1.1em 0 0, rgba(0, 0, 0, 0.75) 0 -1.5em 0 0, rgba(0, 0, 0, 0.75) 1.1em -1.1em 0 0;
}

/* Animation */

@-webkit-keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@-moz-keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@-o-keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
</style>

<?php if ($this->session->flashdata('status')) { ?>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <strong><?php echo $this->session->flashdata('status'); ?></strong>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($this->session->flashdata('error')) { ?>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <strong><?php echo $this->session->flashdata('error'); ?></strong>
            </div>
        </div>
    </div>
<?php } ?>

<!-- Custom column filtering -->
<form method="post" action="<?php echo site_url('admin/story'); ?>">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">User List</h3>
                    
                    <div class="panel-options">
                        <!-- <a href="#" data-toggle="panel">
                            <span class="collapse-icon">&ndash;</span>
                            <span class="expand-icon">+</span>
                        </a> -->
<!--                        <a onclick="open_push_notification_modal();" class="btn btn-turquoise" style="color: #fff;">
                           < Push Notification
                        </a> 
                        <a onclick="open_story_modal();" class="btn btn-turquoise " style="color: #fff;">
                           < Send offer
                        </a>  -->
                        <!--<input type="submit" class="btn btn-turquoise " name="submit" value="< Send offer">-->

<!--                        <input type="submit" name="submit" value="< Send offer">-->

                        <!-- <a href="#" data-toggle="remove">
                            &times;
                        </a> -->
                    </div>
                </div>
                <div class="panel-body table-responsive">
                    
<!--                    <script type="text/javascript">
                    jQuery(document).ready(function($)
                    {
                        $("#example-3").dataTable().yadcf([
                            {column_number : 6, filter_type: 'text'},
                            {column_number : 8},
                            {column_number : 9}
                        ]);

                        // Replace checkboxes when they appear
                        var $state = $("#example-3 thead input[type='checkbox']");
                        
                        $("#example-3").on('draw.dt', function()
                        {

                            cbr_replace();
                            
                            $state.trigger('change');
                        });
                        
                        // Script to select all checkboxes
                        $state.on('change', function(ev)
                        {
                            var $chcks = $("#example-3 tbody input[type='checkbox']");
                            
                            if($state.is(':checked'))
                            {
                                $chcks.prop('checked', true).trigger('change');
                                
                            }
                            else
                            {
                                $chcks.prop('checked', false).trigger('change');
                            }
                        });

                        

                       


                    });
                    </script>-->
                    
                    <table class="table table-striped table-bordered display select" id="example-3" style="width: 1366px !important;">
                        <thead>
                            <tr class="replace-inputs">
                                <th class="no-sorting">
<!--                                    <input type="checkbox" class="cbr">-->
                                    <input name="select_all" value="1" type="checkbox">

                                </th>
                                <th>S.No.</th>
<!--                                <th>Profile Pic</th>
                                <th>First name</th>
                                <th>Last name</th>-->
                                <th>ID</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <!--<th>Age</th>-->
                                <th>Gender</th>
<!--                                <th>Favourites</th>
                                <th>Followers amount</th>
                                <th>Engagement Sum</th>
                                <th>Company</th>-->
                                <th>Date of Birth</th>
                                <th>Address</th>
<!--                                <th>City</th>
                                <th>State</th>-->
                                
                            </tr>
                        </thead>
                        <tbody>

                            <?php if (!empty($user_list)) {
                                $n = 1;
                                foreach ($user_list as $value) {
                                    if(!empty($value->pk)){
                               // $favourite_list = active_favorite_list($value->favourites_id);

                                $name = explode(" ", $value->full_name);
                                $first_name = '';
                                $last_name = '';
                                if(!empty($name[0]))
                                $first_name = $name[0];
                                    
                                if(!empty($name[1]))
                                $last_name = $name[1];

                                if($value->gender == 1)
                                {
                                    $gender = "Male";
                                }
                                elseif($value->gender == 2)
                                {
                                    $gender = "Female";
                                }
                                elseif($value->gender == 3)
                                {
                                    $gender = "Not Specified";
                                }
                                else
                                {

                                }
                                // echo $first_name; die();
                            ?>

                            <tr id="tr">
<!--                                <td style="width: 2px !important;" >
                                    <input type="checkbox" class="cbr"  name="user_[]" value="<?php echo $value->user_id; ?>">
                                </td>-->
                                <td style="width: 2px !important;" ><?php echo $value->user_id; ?></td>
                                <td style="width: 2px !important;"><?php echo $n; ?></td>
                                
<!--                                <td style="width: 10px !important;"><?php if(!empty($first_name)) echo $first_name; ?></td>
                                <td style="width: 10px !important;"><?php if(!empty($last_name)) echo $last_name; ?></td>-->
                                <td style="width: 5px !important;"><?php echo $value->member_username; ?></td>
                                <td style="width: 5px !important;"><?php echo $value->member_email; ?></td>
                                <td style="width: 5px !important;"><?php echo $value->country_code.$value->contact_no; ?></td>
                                <!--<td style="width: 5px !important;" ><?php echo $value->age; ?></td>-->
                                <td style="width: 10px !important;"><?php if(!empty($gender)) echo $gender; ?></td>
<!--                                <td style="width: 15px !important;" ><?php //if(!empty($favourite_list)) echo $favourite_list[0]->favourite_name; ?></td>-->
<!--                                <td style="width: 15px !important;" ><?php if(!empty($value->favourites)) echo $value->favourites; ?></td>
                                <td style="width: 15px !important;" ><?php echo $value->follower_count; ?></td>
                                <td style="width: 15px !important;" ><?php //echo round(get_sum_all_campaign_engagement_by_user($value->user_id), 2); ?></td>-->
                                <!--<td style="width: 15px !important;" ><?php echo $value->company; ?></td>-->
                                <td style="width: 15px !important;" ><?php echo (!empty($value->birthday) && ($value->birthday != '0000-00-00' && $value->birthday !='1970-01-01')) ?date('d-m-Y', strtotime($value->birthday)):''; ?></td>
                                <td style="width: 15px !important;" ><?php echo ((!empty($value->address))? $value->address.', ' :"").((!empty($value->postal_code))? $value->postal_code.', ' :"").((!empty($value->city))? $value->city.', ' :"").((!empty($value->state))? $value->state.', ' :""); ?></td>
                                
<!--                                <td><?php //echo get_average_clicks_per_campaign_by_user($value->user_id);?></td>
                                
                                <td><?php //echo get_viewers_avg_by_user_id($value->user_id);?>
                                
                                </td>-->
                            </tr>

                            <?php $n = $n + 1; } } } ?>
                            
                        </tbody>
                        
                    </table>
                    
                </div>
            </div>
        </form>
<div data-backdrop="static" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="existingStoryModal" class="modal fade bs-example-modal-sm" style="display: none;">
         <div role="document" class="modal-dialog modal-md">
    <div class="modal-content">
        
        
      <div class="modal-header">
          <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
          <div class="testErrors"></div>
        <div class="testSuccess"></div>
        <!--<h4 id="myModalLabel" class="modal-title red" align="center" style="color: #000000;font-family: Times New Roman;">Please Select Story</h4>-->
        <h4 id="myModalLabel" class="modal-title red" align="center" style="color: #000000;font-family: Times New Roman;">Please Select Campaign</h4>
      </div>
        <form name="sendStoryModal" id="sendStoryModal" role="form" method="post" class="validate" action="<?php echo site_url('admin/send_campaign_to_user');?>">
      <div class="modal-body input-style">

          
        
          <div class="form-group">
                <!--<label class="control-label">Story</label>-->
                <!--<div class="col-md-4">-->
<!--                    <select class="form-control input-dark" name="story_id" data-validate="required" data-message-required="Please select story" >
                        <option value="" hidden="">Select Story</option>
                    <?php 
//                    $get_all_stories = get_all_stories();
//                    if(!empty($get_all_stories)){ foreach ($get_all_stories as $value) {
                        ?>
                    <option value="<?php // echo $value->id; ?>"><?php // echo $value->story_title; ?></option>
                    <?php // } } ?>
                    </select>-->
                
                <select class="form-control input-dark" name="campaign_id" data-validate="required" data-message-required="Please select campaign" >
                        <option value="" hidden="">Select Campaign</option>
                    <?php 
                    $get_all_campaign = get_all_campaign();
                    if(!empty($get_all_campaign)){ foreach ($get_all_campaign as $value) {
                        ?>
                    <option value="<?php echo $value->id; ?>"><?php echo $value->campaign_name; ?></option>
                    <?php } } ?>
                    </select>
                   <!--</div>-->
                
            </div>
<div class="clearfix"></div>
<div class="clearfix"></div>
<div class="clearfix"></div>
<br/>
 

      </div>
    
      <div class="modal-footer">
          <input type="hidden" name="users_ids" id="users_ids" value="">
          <!--<input type="hidden" name="type" value="1">-->
          <input type="submit" class="btn btn-red form-group btn btn-info btn-lg ck_textarea_on" id="testSubmit" value="Send" />
      </div>
            </form>
    </div>
  </div>
      </div>
<div class="loading">Loading&#8230;</div>
<!-- Create section for push notification -->
<div data-backdrop="static" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="sendPushNotificationModal" class="modal fade bs-example-modal-sm" style="display: none;">
         <div role="document" class="modal-dialog modal-md">
    <div class="modal-content">
        
        
      <div class="modal-header">
          <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <div class="testErrors"></div>
        <div class="testSuccess"></div>
        <!--<h4 id="myModalLabel" class="modal-title red" align="center" style="color: #000000;font-family: Times New Roman;">Please Select Story</h4>-->
        <h4 id="myModalLabel" class="modal-title red" align="center" style="color: #000000;font-family: Times New Roman;">Push Notification</h4>
      </div>
        <form name="sendPushModal" id="sendPushModal" role="form" method="post" class="validate" action="<?php echo site_url('admin/send_push');?>">
      <div class="modal-body input-style">

          
        
          <div class="form-group">
                
<!--                <label class="control-label">Description</label>-->

                <textarea name="push_message" id="" class="form-control ck_textarea_visible" placeholder="Write something here..." rows="5" data-validate="required"></textarea>
            </div>
<div class="clearfix"></div>
<div class="clearfix"></div>
<div class="clearfix"></div>
<br/>
 

      </div>
    
      <div class="modal-footer">
          <input type="hidden" name="usersids" id="usersids" value="">
          <input type="submit" class="btn btn-red form-group btn btn-info btn-lg ck_textarea_on" id="testSubmit" value="Send Push" onclick="closeModal()" />
      </div>
            </form>
    </div>
  </div>
      </div>
<script>
function myGetValue(fieldName) {
    var value = $('input[name="' + fieldName + '"]:checked, input[name="' + fieldName + '"]:not(:checkbox, :radio)').map(function () {
        return this.value
    }).get();
    return value;
}
/*
function open_story_modal(){
    var users = myGetValue('user_[]');
    if(users == ''){
        alert('Please select at least on user.');
        return false;
    }
    //alert(users);
    $('#users_ids').val(users);
    $('#existingStoryModal').modal('toggle');
    
}
*/


//
// Updates "Select all" control in a data table
//
function updateDataTableSelectAllCtrl(table){
   var $table             = table.table().node();
   var $chkbox_all        = $('tbody input[type="checkbox"]', $table);
   var $chkbox_checked    = $('tbody input[type="checkbox"]:checked', $table);
   var chkbox_select_all  = $('thead input[name="select_all"]', $table).get(0);

   // If none of the checkboxes are checked
   if($chkbox_checked.length === 0){
      chkbox_select_all.checked = false;
      if('indeterminate' in chkbox_select_all){
         chkbox_select_all.indeterminate = false;
      }

   // If all of the checkboxes are checked
   } else if ($chkbox_checked.length === $chkbox_all.length){
      chkbox_select_all.checked = true;
      if('indeterminate' in chkbox_select_all){
         chkbox_select_all.indeterminate = false;
      }

   // If some of the checkboxes are checked
   } else {
      chkbox_select_all.checked = true;
      if('indeterminate' in chkbox_select_all){
         chkbox_select_all.indeterminate = true;
      }
   }
}

$(document).ready(function (){
   // Array holding selected row IDs
   var rows_selected = [];
   var table = $('#example-3').DataTable({
//      'ajax': {
//         'url': '<?php //echo site_url('admin/get_all_users');?>'
//      },
      'columnDefs': [{
         'targets': 0,
         'searchable': false,
         'orderable': false,
         'width': '1%',
         'className': 'dt-body-center',
         'render': function (data, type, full, meta){
//             alert(data);
             return '<input type="checkbox" class="call-checkbox" value="'+data+'">';
         }
      }],
      'order': [[1, 'asc']],
      'rowCallback': function(row, data, dataIndex){
         // Get row ID
         var rowId = data[0];
//         alert(rowId);

         // If row ID is in the list of selected row IDs
         if($.inArray(rowId, rows_selected) !== -1){
            $(row).find('input[type="checkbox"]').prop('checked', true);
            $(row).addClass('selected');
         }
      }
   });

   // Handle click on checkbox
   $('#example-3 tbody').on('click', 'input[type="checkbox"]', function(e){
      var $row = $(this).closest('tr');

      // Get row data
      var data = table.row($row).data();

      // Get row ID
      var rowId = data[0];

      // Determine whether row ID is in the list of selected row IDs
      var index = $.inArray(rowId, rows_selected);

      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
         rows_selected.push(rowId);

      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
      } else if (!this.checked && index !== -1){
         rows_selected.splice(index, 1);
      }

      if(this.checked){
         $row.addClass('selected');
      } else {
         $row.removeClass('selected');
      }

      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(table);

      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle click on table cells with checkboxes
   $('#example-3').on('click', 'tbody td, thead th:first-child', function(e){
      $(this).parent().find('input[type="checkbox"]').trigger('click');
   });

   // Handle click on "Select all" control
   $('thead input[name="select_all"]', table.table().container()).on('click', function(e){
      if(this.checked){
         $('#example-3 tbody input[type="checkbox"]:not(:checked)').trigger('click');
      } else {
         $('#example-3 tbody input[type="checkbox"]:checked').trigger('click');
      }

      // Prevent click event from propagating to parent
      e.stopPropagation();
   });

   // Handle table draw event
   table.on('draw', function(){
      // Update state of "Select all" control
      updateDataTableSelectAllCtrl(table);
   });


   // Handle form submission event
 /*  $('#frm-example').on('submit', function(e){
      var form = this;

      // Iterate over all selected checkboxes
      $.each(rows_selected, function(index, rowId){
          alert(rowId);
         // Create a hidden element
         $(form).append(
             $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'id[]')
                .val(rowId)
         );
      });
   });  */

});

function open_story_modal(){
    
    var checkbox_value=[];
    var oTable = $('#example-3').dataTable();
    var rowcollection =  oTable.$(".call-checkbox:checked", {"page": "all"});
    rowcollection.each(function(index,elem){
    checkbox_value.push($(elem).val());
//        alert(checkbox_value);
    console.log(checkbox_value);
        //Do something with 'checkbox_value'
    });


    
//    var users = myGetValue('user_[]');
//    alert(JSON.stringify(users));
    if(checkbox_value == ''){
        alert('Please select at least on user.');
        return false;
    }
    //alert(users);
//var user_ids  =  document.write(checkbox_value.join(","));
    $('#users_ids').val(checkbox_value);
    $('#existingStoryModal').modal('toggle');
    
    
    
}

function open_push_notification_modal(){
    
    var checkbox_value=[];
    var oTable = $('#example-3').dataTable();
    var rowcollection =  oTable.$(".call-checkbox:checked", {"page": "all"});
    rowcollection.each(function(index,elem){
    checkbox_value.push($(elem).val());
//        alert(checkbox_value);
    console.log(checkbox_value);
        //Do something with 'checkbox_value'
    });


    
//    var users = myGetValue('user_[]');
//    alert(JSON.stringify(users));
    if(checkbox_value == ''){
        if(!confirm('Are you sure, You want to send push notification to all user?')){return false;}
//        alert('Please select at least on user.');
//        return false;
    }
    //alert(users);
//var user_ids  =  document.write(checkbox_value.join(","));
    $('#usersids').val(checkbox_value);
    $('#sendPushNotificationModal').modal('toggle');
}
function closeModal(){
    if($('#push_message').val()!= ''){
        $('.loading').css('display', 'block');
        $('#sendPushNotificationModal').modal('toggle');
    }
    
}
</script>

<script>
$(function() {
		$('.pop').on('click', function() {
			$('.imagepreview').attr('src', $(this).find('img').attr('src'));
			$('#imagemodal').modal('show');   
		});		
});
$( document ).on( "pagecreate", function() {
    $( ".photopopup" ).on({
        popupbeforeposition: function() {
            var maxHeight = $( window ).height() - 60 + "px";
            $( ".photopopup img" ).css( "max-height", maxHeight );
        }
    });
});
</script>
