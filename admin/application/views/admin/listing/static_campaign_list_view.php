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
            Static Campaign List
        </div>
        <div class="panel-options">
             <a href="<?php echo site_url('admin/add_static_campaign'); ?>" class="btn btn-turquoise" style="color: #fff;">
                Add New Static Campaign
            </a>  
            <!--<input type="submit" class="btn btn-turquoise " name="submit" value="< Send offer">-->
        </div>
    </div>

    <div id="user_list" class="panel-body">
        <script type="text/javascript">
            jQuery(document).ready(function ($)
            {
                $("#example-1").dataTable({
                    aLengthMenu: [
                        [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]
                    ]
                });
            });
        </script>

        <table id="example-1" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Sr.No.</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Reward</th>
                    <!--<th>Timezone</th>-->
                    <th>Created Date</th>
                    <th>Campaign Viewer Amount</th>
                    <th>Total clicks</th>
                    <th>Total Rewards</th>
                    <th>Action</th>
                </tr>
            </thead>



            <tbody>
                <?php if (!empty($campaign_list)) {
                    $n = 1;
                    foreach ($campaign_list as $value) {
                        ?>
                        <tr>
                            <td style="width: 10%"><?php echo $n; ?></td>
                            <td><?php echo $value->campaign_name; ?></td>
                            <td><?php echo $value->campaign_description; ?></td>
                            <td><?php echo $value->reward; ?></td>
                            <!--<td><?php //echo $value->post_timezone; ?></td>-->
                            <td><?php $date = date("d M Y h:i A", strtotime($value->created_at)); echo $date; ?></td>
                            <td><?php echo $viewer_amount = get_campaign_viewer_count_by_campaign_id($value->id); ?></td>
                            <td><?php echo get_total_clicks_by_campaign_id($value->id); ?></td>
                            <td><?php echo $rewards_sum = $value->reward_number*$viewer_amount; ?></td>
                            
                            <td width="25%">
                                
                                 <?php $status = $value->campaign_status; ?> 

                                <!-- <?php if ($status == 1) { ?>
                                <a href="<?php echo site_url('admin/change_status') . '/id/' . $value->id; ?>/campaign/campaign_status/2/campaign_list" class="btn btn-secondary btn-sm btn-icon icon-left" data-toggle="tooltip" data-placement="right" title="Click to Deactive!"><i class="fa fa-check"></i></a><br><br>
                                <?php } elseif ($status == 2) { ?>

                                <a href="<?php echo site_url('admin/change_status') . '/id/' . $value->id; ?>/campaign/campaign_status/1/campaign_list" class="btn btn-warning btn-sm btn-icon icon-left" data-toggle="tooltip" data-placement="right" title="Click to Active!"><i class="fa fa-exclamation"></i></a><br><br>
                                <?php } ?> -->

                         <a href="<?php echo site_url('admin/edit_static_campaign') . '/' . $value->id; ?>" class="btn btn-blue btn-sm btn-icon icon-left" data-toggle="tooltip" data-placement="right" title="Edit"><i class="fa fa-pencil"></i></a><br><br> 
                         
<a onClick="if(!confirm('Are you sure, You want to delete this static campaign?')){return false;}" href="<?php echo site_url('admin/delete') . '/delete_static_campaign/campaign/id/' . $value->id; ?>" class="btn btn-danger btn-sm btn-icon icon-left" data-toggle="tooltip" data-placement="right" title="Delete"><i class="fa fa-trash"></i></a><br><br>
                                
                                <a href="<?php echo site_url('admin/static_campaign_detail_list') . '/' . $value->id; ?>" class="btn btn-blue btn-sm btn-icon icon-left" data-toggle="tooltip" data-placement="right" title="View Campaign Details"><i class="fa fa-eye" aria-hidden="true"></i>
									</a>
                            </td>

                        </tr>
                <?php $n = $n + 1; } } ?>
            </tbody>
        </table>

    </div>

</div>
