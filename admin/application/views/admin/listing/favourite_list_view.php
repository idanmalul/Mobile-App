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
            Favourite List
        </div>
        <div class="panel-options">
            <a href="<?php echo site_url('admin/add_favourite'); ?>" class="btn btn-turquoise fa-plus-circle" style="color: #fff;">
                Add Favourite
            </a> 
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
                    <th>Favourite Name</th>
                    <th>Action</th>
                </tr>
            </thead>



            <tbody>
                <?php if (!empty($favourite_list)) {
                    $n = 1;
                    foreach ($favourite_list as $value) {
                        ?>
                        <tr>
                            <td style="width: 10%"><?php echo $n; ?></td>
                            <td><?php echo $value->favourite_name; ?></td>
                            
                            <td style="width: 25%">
<!--                                <a href="<?php echo site_url('admin/edit_favourite') . '/' . $value->favourite_id; ?>" class="btn btn-blue btn-sm btn-icon icon-left">Edit</a>-->
                                <a href="<?php echo site_url('admin/edit_favourite') . '/' . $value->favourite_id; ?>" class="btn btn-blue btn-sm btn-icon icon-left" data-toggle="tooltip" data-placement="right" title="Edit"><i class="fa fa-pencil"></i></a>
                                <?php $status = $value->favourite_status; ?>
                                <?php if ($status == 1) { ?>
<!--                                <a href="<?php echo site_url('admin/change_status') . '/favourite_id/' . $value->favourite_id; ?>/favourites/favourite_status/2/favourite_list" class="btn btn-secondary btn-sm btn-icon icon-left">&nbsp;Active&nbsp;</a>-->
                                
                                <a href="<?php echo site_url('admin/change_status') . '/favourite_id/' . $value->favourite_id; ?>/favourites/favourite_status/2/favourite_list" class="btn btn-secondary btn-sm btn-icon icon-left" data-toggle="tooltip" data-placement="right" title="Click to Deactive!"><i class="fa fa-check"></i></a>
                                <?php } elseif ($status == 2) { ?>
<!--                                <a href="<?php echo site_url('admin/change_status') . '/favourite_id/' . $value->favourite_id; ?>/favourites/favourite_status/1/favourite_list" class="btn btn-warning btn-sm btn-icon icon-left">Inactive</a>-->
                                <a href="<?php echo site_url('admin/change_status') . '/favourite_id/' . $value->favourite_id; ?>/favourites/favourite_status/1/favourite_list" class="btn btn-warning btn-sm btn-icon icon-left" data-placement="right" title="Click to Active!"><i class="fa fa-exclamation"></i></a>
                                <?php } ?>
                                
                                <a onClick="if(!confirm('Are you sure, You want delete this favourite?')){return false;}" href="<?php echo site_url('admin/delete') . '/delete_favourite/favourites/favourite_id/' . $value->favourite_id; ?>" class="btn btn-danger btn-sm btn-icon icon-left" data-toggle="tooltip" data-placement="right" title="Delete"><i class="fa fa-trash"></i></a>
                                
                                
                                <br/><br/><br/>
                                
                            </td>
                        </tr>
                <?php $n = $n + 1; } } ?>
            </tbody>
        </table>

    </div>

</div>