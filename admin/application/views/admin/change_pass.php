<div class="row">

    <div class="col-md-12">
        <?php if($this->session->flashdata('error_msg')){ ?>
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">
                <span aria-hidden="true">×</span>
                <span class="sr-only">Close</span>
            </button>

            <?php echo $this->session->flashdata('error_msg'); ?>
        </div>
        <?php } ?>
        <?php if($this->session->flashdata('success_msg')){ ?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">
                <span aria-hidden="true">×</span>
                <span class="sr-only">Close</span>
            </button>

            <?php echo $this->session->flashdata('success_msg'); ?>
        </div>
        <?php } ?>
    </div>

</div>
<div class="panel panel-default">

    <div class="panel-heading">
        <div class="panel-title">
            Change Password
        </div>
    </div>

    <div class="panel-body">

        <form role="form" id="form1" method="post" class="validate">
            
            <input style="display:none" type="password" name="fakepasswordremembered"/>
            
            <div class="form-group">
                <label class="control-label">Old Password</label>
                
                <input type="password" class="form-control" name="old_pass" placeholder="Old Password" data-rule-required="true" />
            </div>

            <div class="form-group">
                <label class="control-label">New Password</label>

                <input type="password" class="form-control" id="new_pass" name="new_pass" placeholder="New Password" data-rule-required="true" data-rule-minlength="6" />
            </div>

            <div class="form-group">
                <label class="control-label">Confirm Password</label>

                <input type="password" class="form-control" placeholder="Confirm Password" data-rule-required="true" data-rule-equalto="#new_pass" />
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success">Submit</button>
            </div>

        </form>

    </div>

</div>