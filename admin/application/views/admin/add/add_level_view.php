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
            <?php if(!empty($level_detail)) echo 'Edit'; else echo 'Add'; ?> Level
        </div>
    </div>
        
    <div class="panel-body">
        <form action="<?php if(!empty($level_detail)) echo site_url('admin/edit_level'); else echo site_url('admin/add_level'); ?>" role="form" id="form1" method="post" enctype="multipart/form-data" class="validate">
            <div class="form-group">
                <label class="control-label">Level</label>
                <input type="text" name="level_name" placeholder="Level Name" class="form-control" data-validate="required" value="<?php if(!empty($level_detail)){ echo $level_detail[0]->level_name; }else{ if(isset($_POST['level_name'])){ echo $_POST['level_name']; } }?>" />
                
            </div>
            
            <div class="form-group">
                <label class="control-label">Level Percentage</label>
                <input type="text" name="level_percentage" placeholder="Level Percentage" class="form-control" data-validate="required" value="<?php if(!empty($level_detail)){ echo $level_detail[0]->level_percentage; }else{ if(isset($_POST['level_percentage'])){ echo $_POST['level_percentage']; } }?>" />
                
            </div>
            
            <div class="form-group">
                <label class="control-label">Approved Offers Range</label>
                <input type="text" name="approved_offers_range" placeholder="Approved Offers Range" class="form-control" data-validate="required" value="<?php if(!empty($level_detail)){ echo $level_detail[0]->approved_offers_range; }else{ if(isset($_POST['approved_offers_range'])){ echo $_POST['approved_offers_range']; } }?>" />
                
            </div>
            
            <div class="form-group">
                <label class="control-label">Followers Amount</label>
                <input type="text" name="followers_amount" placeholder="Followers Amount" class="form-control" data-validate="required" value="<?php if(!empty($level_detail)){ echo $level_detail[0]->followers_amount; }else{ if(isset($_POST['followers_amount'])){ echo $_POST['followers_amount']; } }?>" />
                
            </div>
            
            <div class="form-group">

                <label class="control-label">Level Icon</label>
                <?php if(!empty($level_detail[0]->level_icon)){ ?>
                <br/>
                <div class="">
                    <br/>
                    <div class="">
                    <img src="<?php echo base_url('uploads/level_icons').'/'.$level_detail[0]->level_icon; ?>" height="100" width="100"><br/><br/>
                    </div>
                
                
                </div>
                <?php } ?>

            <br/>
            <input type="file" name="level_icon" value="<?php if(!empty($level_detail)){ echo $level_detail[0]->level_icon; } ?>" class="form-control" <?php if(empty($level_detail[0]->level_icon)){ ?>data-validate="required"<?php } ?> />
            
            </div>
            
            <div class="form-group">
                <?php if(!empty($level_detail)){ ?>
                <input type="hidden" name="level_id" value="<?php echo $level_detail[0]->id; ?>">
                <input type="submit" name="submit" value="Update" class="btn btn-success">
                <?php } else { ?>
                <input type="submit" name="submit" value="Add" class="btn btn-success">
                <?php } ?>
                
            </div>
        </form>
    </div>
</div>