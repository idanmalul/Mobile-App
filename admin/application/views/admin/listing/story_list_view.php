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
            Story List
        </div>
        <div class="panel-options">
             <a href="<?php echo site_url('admin/add_story'); ?>" class="btn btn-turquoise" style="color: #fff;">
                Add New Story
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
                    <th>Title</th>
                    <!--<th>Description</th>-->
                    <th>Ticket Link</th>
                    <th>Timezone</th>
                    <th>Created Date</th>
                    <th>Action</th>
                </tr>
            </thead>



            <tbody>
                <?php if (!empty($story_list)) {
                    $n = 1;
                    foreach ($story_list as $value) {
                        ?>
                        <tr>
                            <td style="width: 10%"><?php echo $n; ?></td>
                            <td><?php echo $value->story_title; ?></td>
                            <!--<td><?php echo $value->story_description; ?></td>-->
                            <td width="25%"><?php echo strlen($value->ticket_link) > 80 ? substr($value->ticket_link,0,80)."..." : $value->ticket_link; ?></td>
                            <td><?php echo $value->post_timezone; ?></td>
                            <td><?php $date = date("d M Y h:i A", strtotime($value->created_at)); echo $date; ?></td>
                            
                            <td width="25%">
                                
                                <!-- <?php $status = $value->admin_status; ?> -->

                                <!-- <?php if ($status == 1) { ?>
                                <a href="<?php echo site_url('admin/change_status') . '/admin_id/' . $value->admin_id; ?>/admin/admin_status/2/story_list" class="btn btn-secondary btn-sm btn-icon icon-left" data-toggle="tooltip" data-placement="right" title="Click to Deactive!"><i class="fa fa-check"></i></a><br><br>
                                <?php } elseif ($status == 2) { ?>

                                <a href="<?php echo site_url('admin/change_status') . '/admin_id/' . $value->admin_id; ?>/admin/admin_status/1/story_list" class="btn btn-warning btn-sm btn-icon icon-left" data-toggle="tooltip" data-placement="right" title="Click to Active!"><i class="fa fa-exclamation"></i></a><br><br>
                                <?php } ?> -->

                                
                                <a href="<?php echo site_url('admin/edit_story') . '/' . $value->id; ?>" class="btn btn-blue btn-sm btn-icon icon-left" data-toggle="tooltip" data-placement="right" title="Edit"><i class="fa fa-pencil"></i></a><br><br>
                                

                                <a onClick="if(!confirm('Are you sure, You want to delete this story?')){return false;}" href="<?php echo site_url('admin/delete') . '/delete_story/story/id/' . $value->id; ?>" class="btn btn-danger btn-sm btn-icon icon-left" data-toggle="tooltip" data-placement="right" title="Delete"><i class="fa fa-trash"></i></a><br><br>

                            </td>

                        </tr>
                <?php $n = $n + 1; } } ?>
            </tbody>
        </table>

    </div>

</div>
