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
            <?php if(!empty($users)) echo 'Edit'; else echo 'Add'; ?> User
        </div>
    </div>
        
    <div class="panel-body">
        <form action="<?php if(!empty($users)) echo site_url('admin/edit_user'); else echo site_url('admin/edit_user'); ?>" role="form" id="form1" method="post" enctype="multipart/form-data" class="validate">
            <div class="form-group">
                <label class="control-label">Company</label>
                <input type="text" name="company" placeholder="Company Name" class="form-control" data-validate="required" value="<?php if(!empty($users)){ echo $users[0]->company; }else{ if(isset($_POST['company'])){ echo $_POST['company']; } }?>" />
                
            </div>
            

            
            <div class="form-group">
                <?php if(!empty($users)){ ?>
                <input type="hidden" name="user_id" value="<?php echo $users[0]->user_id; ?>">
                <input type="submit" name="submit" value="Update" class="btn btn-success">
                <?php } else { ?>
                <input type="submit" name="submit" value="Add" class="btn btn-success">
                <?php } ?>
                
            </div>
        </form>
    </div>
</div>