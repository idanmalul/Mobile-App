<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.1.2/css/buttons.dataTables.min.css">

<!--<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>-->
<script src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.1.2/js/dataTables.buttons.min.js"></script>
<!--<script src="https://cdn.datatables.net/buttons/1.1.2/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>-->
<script src="https://cdn.datatables.net/buttons/1.1.2/js/buttons.html5.min.js"></script>
<!--<script src="https://cdn.datatables.net/buttons/1.1.2/js/buttons.print.min.js"></script>-->
<!--<script src="<?php echo base_url() ?>assets/js/datatables/dataTables.bootstrap.js"></script>-->
<style>
    div.dt-buttons {
	position: relative;
	float: left;
	left: 15px;
	/* margin-top: -74px; */
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
<div class="panel panel-default">

    <div class="panel-heading">
        <div class="panel-title">
            Campaign Detail
        </div>
        <br/>
        <br/>
        <div class="panel-title">
            <?php $campaign_detail = get_campaign_by_campaign_id($this->uri->segment(3)); if(!empty($campaign_detail)){ ?><strong>Campaign Name :</strong> <?php echo $campaign_detail[0]->campaign_name; } ?>
        </div>
        
    </div>

    <div id="user_list" class="panel-body table-responsive">
        <script type="text/javascript">
            jQuery(document).ready(function ($)
            {
                $("#example-1").dataTable({
                    aLengthMenu: [
                        [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]
                    ],
                    dom: 'lBfrtip',
                    buttons: [
                       {
                           extend: 'csv',
                           text: 'Export CSV',
                           filename: 'campaign_details_report',
                           exportOptions: {
                               columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
                           }
                       }
                   ]
                });
                
                $( "a.buttons-csv" ).removeClass( "dt-button" ).addClass( "btn btn-turquoise btn-sm btn-icon" );
                $( "a.buttons-excel" ).removeClass( "dt-button" ).addClass( "btn btn-blue btn-sm btn-icon" );
                $( "a.buttons-pdf" ).removeClass( "dt-button" ).addClass( "btn btn-blue btn-sm btn-icon" );
                $( "a.buttons-print" ).removeClass( "dt-button" ).addClass( "btn btn-blue btn-sm btn-icon" );
            });
        </script>

        <table id="example-1" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Sr.No.</th>
                    <th>Name</th>
                    <th>Instagram ID</th>
                    <th>Story Title</th>
                    <th>Viewer Amount</th>
                    <th>Total clicks</th>
                    <th>Engagement</th>
                    <th>Total Reward</th>
                    <th>Schedule Date</th>
                    <th>Send Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>



            <tbody>
                <?php if (!empty($campaign_detail_list)) {
                    $n = 1;
                    $rewards_sum = 0;
                    foreach ($campaign_detail_list as $value) {
                        ?>
                        <tr>
                            <td style="width: 10%"><?php echo $n; ?></td>
                            <!--<td><?php // echo $value->full_name.' ('.$value->username.')'; ?></td>-->
                            <td><?php echo $value->full_name; ?></td>
                            <td><?php echo $value->username; ?></td>
                            <td><?php echo $value->story_title; ?></td>
                            <td><?php echo $value->story_viewer_count; ?></td>
                            <td><?php echo $value->total_clicks; ?></td>
                            <td><?php echo ($value->follower_count) ? round($value->story_viewer_count/$value->follower_count,2) : 0 ;?></td>
                            <td><?php 
                                      echo $reward = $value->reward_number*$value->story_viewer_count;
                                      $rewards_sum = $rewards_sum + $reward;
//                                      echo '<br>'.$rewards_sum;
                            ?></td>
                            <td><?php 
//                            $get_campaign_schedule=get_campaign_schedule_by_camp_id_story_id($value->campaign_id,$value->story_id);
//                            if(!empty($get_campaign_schedule)){
//                                foreach ($get_campaign_schedule as $sch) {
//                                    $schedule_date_time = date("d M Y h:i A", strtotime($sch->schedule_date_time));
//                                    echo $schedule_date_time;
//                                    echo '<br/>';
//                                }
//                            }
                            
//                            $schedule_date_time = date("d M Y h:i A", strtotime($value->schedule_date_time)); echo $schedule_date_time; ?></td>
                            <td><?php $date = date("d M Y h:i A", strtotime($value->created_at)); echo $date; ?></td>
                            <td>
                                <?php if($value->story_accept_status == 1 && $value->upload_status == 0)
                                      {
                                          echo 'approved';
                                      }
                                      elseif($value->upload_status == 1 && $value->media_status == 'ok')                                       {
                                          echo 'published';
                                      }
                                      else
                                      {
                                          echo 'pending';
                                      }
                                ?>
                            </td>
                            
                            <td>
                                <?php 
                                $check_payout = check_payout($value->id);
                                if(!empty($value->paypal_email) && empty($check_payout) && ($value->upload_status == 1 && $value->media_status == 'ok')){ ?>
<!--                                <a href="<?php //echo site_url('admin/create_payout') . '/' . $value->id; ?>" class="btn btn-blue btn-sm btn-icon icon-left" data-toggle="tooltip" data-placement="right" title="Pay">Pay</a>-->
                                
                                <a onclick="open_amount_modal('<?php echo $value->id;?>','<?php echo $reward;?>')"  class="btn btn-blue btn-sm btn-icon icon-left" data-toggle="tooltip" data-placement="right" title="Pay">Pay</a>
                                <?php }elseif (!empty ($check_payout)) {
                                            echo 'Payout Sent';
                                        } ?>
                            </td>
                            
                            

                        </tr>
                <?php $n = $n + 1; } } ?>
            </tbody>
        </table>

    </div>

</div>

<!-- Create section for payout creation -->
<div data-backdrop="static" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="sendPayoutModal" class="modal fade bs-example-modal-sm" style="display: none;">
         <div role="document" class="modal-dialog modal-md">
    <div class="modal-content">
        
        
      <div class="modal-header">
          <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <div class="testErrors"></div>
        <div class="testSuccess"></div>
        <!--<h4 id="myModalLabel" class="modal-title red" align="center" style="color: #000000;font-family: Times New Roman;">Please Select Story</h4>-->
        <h4 id="myModalLabel" class="modal-title red" align="center" style="color: #000000;font-family: Times New Roman;">Create Payout</h4>
      </div>
        <form name="sendPayoutModal" id="sendPayoutModal" role="form" method="post" class="validate" action="<?php echo site_url('admin/create_payout'); ?>" >
      <div class="modal-body input-style">

          
        
          <div class="form-group">
                
<!--                <label class="control-label">Description</label>-->

<!--                <textarea name="push_message" id="push_message" class="form-control ck_textarea_visible" placeholder="Write something here..." rows="5" data-validate="required"></textarea>-->
                
                <input type="text" name="amount" id="amount" value="" data-validate="required" placeholder="Enter amount here..." class="form-control">
                <!--<input>-->
            </div>
<div class="clearfix"></div>
<div class="clearfix"></div>
<div class="clearfix"></div>
<br/>
 

      </div>
    
      <div class="modal-footer">
          <input type="hidden" name="sent_primary_id" id="sent_primary_id" value="">
          <input type="hidden" name="static_flag" id="static_flag" value="1">
          <input type="submit" class="btn btn-red form-group btn btn-info btn-lg ck_textarea_on" id="testSubmit" value="Send Payout" onclick="closeModal()" />
      </div>
            </form>
    </div>
  </div>
      </div>
<script>
function open_amount_modal(id,amount){
    $('#sent_primary_id').val(id);
    $('#amount').val(amount);
    $('#sendPayoutModal').modal('toggle');
}
function closeModal(){
    if($('#sent_primary_id').val()!= ''){
//        $('.loading').css('display', 'block');
        $('#sendPayoutModal').modal('toggle');
    }
    
}
</script>
