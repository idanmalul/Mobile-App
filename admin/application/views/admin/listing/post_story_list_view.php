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
            Story History
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
                    <th>User Name</th>
                    <th>Story Title</th>
                    <!--<th>Story Image/Video</th>-->
                    <th>Viewer Amount</th>
                    <th>Action</th>
                </tr>
            </thead>



            <tbody>
                <?php if (!empty($post_story_list)) {
                    $n = 1;
                    foreach ($post_story_list as $value) {
                        ?>
                        <tr>
                            <td style="width: 10%"><?php echo $n; ?></td>
                            <td><?php echo $value->full_name; ?></td>
                            <td><?php echo $value->story_title;?></td>
<!--                            <td width="10%">
                                <?php if(!empty($value->story_image)){ ?>
                <div class="">
                    <?php if(!empty($value->media_type == 1)){ ?>
                    <div class="">
                    <img src="<?php echo base_url('uploads/story_images').'/'.$value->story_image; ?>" height="100" width="180">
                    </div>
                   

                <?php }elseif(!empty($value->media_type == 2)){ ?>
                    <video width="200" height="120" controls="hide"  class="videoPlayer">
  <source src="<?php echo base_url('uploads/story_images').'/'.$value->story_image; ?>" type="video/webm"> 
  <source src="<?php echo base_url('uploads/story_images').'/'.$value->story_image; ?>" type="video/ogg"> 
  <source src="<?php echo base_url('uploads/story_images').'/'.$value->story_image; ?>" type="video/mp4">
  <source src="<?php echo base_url('uploads/story_images').'/'.$value->story_image; ?>" type="video/3gp">
</video>
                     <?php } ?>

                </div>
                <?php } ?>
                            </td>-->
                            <td><?php echo $value->story_viewer_count;?></td>
                            <td style="width: 25%">
                                
                                <a onClick="if(!confirm('Are you sure, You want delete this record?')){return false;}" href="<?php echo site_url('admin/delete') . '/delete_post_story/post_story/post_id/' . $value->post_id; ?>" class="btn btn-danger btn-sm btn-icon icon-left" data-toggle="tooltip" data-placement="right" title="Delete"><i class="fa fa-trash"></i></a>
                                
                                <br/><br/><br/>
                                
                            </td>
                        </tr>
                <?php $n = $n + 1; } } ?>
            </tbody>
        </table>

    </div>

</div>