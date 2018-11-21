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
            <?php if(!empty($favourite)) echo 'Edit'; else echo 'Add'; ?> Favourite
        </div>
    </div>
        
    <div class="panel-body">
        <form action="<?php if(!empty($favourite)) echo site_url('admin/edit_favourite'); else echo site_url('admin/add_favourite'); ?>" role="form" id="form1" method="post" enctype="multipart/form-data" class="validate">
            <div class="form-group">
                <label class="control-label">Favourite</label>
                <input type="text" name="favourite_name" placeholder="Favourite Name" class="form-control" data-validate="required" value="<?php if(!empty($favourite)){ echo $favourite[0]->favourite_name; }else{ if(isset($_POST['favourite_name'])){ echo $_POST['favourite_name']; } }?>" />
                
            </div>
            
            <div class="form-group">
                <?php if(!empty($favourite)){ ?>
                <input type="hidden" name="favourite_id" value="<?php echo $favourite[0]->favourite_id; ?>">
                <input type="submit" name="submit" value="Update" class="btn btn-success">
                <?php } else { ?>
                <input type="submit" name="submit" value="Add" class="btn btn-success">
                <?php } ?>
                
            </div>
        </form>
    </div>
</div>