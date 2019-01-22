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
            <?php if(!empty($story_detail)) echo 'Edit'; else echo 'Add'; ?> Story
        </div>
    </div>
        
    <div class="panel-body">
        <form action="<?php if(!empty($story_detail)) echo site_url('admin/edit_story'); else echo site_url('admin/add_story'); ?>" role="form" id="form1" method="post" enctype="multipart/form-data" class="validate">

            <?php /*if(!empty($user_arr)){ foreach($user_arr as $v){
                $id[] = $v;

             } $id_user = implode(",", $id); } */ ?>

             <input type="hidden" name="user_hidden" value="<?php //if(!empty($id_user)){ echo $id_user; } ?>">
<!--             <div class="form-group">
                <label class="control-label">User Name</label>
                <?php if(!empty($story_detail)) $already_story_user = get_users_by_story_id($story_detail[0]->id); ?>
                <select id="send_user_id" class="form-control input-dark" name="send_user_id[]" data-validate="required" multiple="">
                    <option value="" ></option>
                                <optgroup label="---Select Users---">
				<?php $active_user = get_all_users(); foreach ($active_user as $value) { ?>
                                <option value="<?php echo $value->user_id; ?>"<?php if(!empty($already_story_user)){     foreach ($already_story_user as $su) {
         if($value->user_id==$su->user_id){ echo "selected='selected'"; }
     } }else{ if(isset($_POST['user_id'])) { if($value->user_id==$_POST['user_id']){ echo "selected='selected'"; } }}?>><?php echo $value->full_name; ?></option>
				<?php } ?>
                                </optgroup>
				</select>
                 
            </div>-->
            <div class="form-group">

                <label class="control-label">Image/Video</label>
                <?php if(!empty($story_detail[0]->story_image)){ ?>
                <br/>
                <div class="">
                    <?php if(!empty($story_detail[0]->media_type == 1)){ ?>
                    <br/>
                    <div class="">
                    <img src="<?php echo base_url('uploads/story_images').'/'.$story_detail[0]->story_image; ?>" height="100" width="100"><br/><br/>
                    </div>

                <?php }elseif(!empty($story_detail[0]->media_type == 2)){ ?>
                    <video width="200" height="150" controls="hide"  class="videoPlayer">
  <source src="<?php echo base_url('uploads/story_images').'/'.$story_detail[0]->story_image; ?>" type="video/webm"> 
  <source src="<?php echo base_url('uploads/story_images').'/'.$story_detail[0]->story_image; ?>" type="video/ogg"> 
  <source src="<?php echo base_url('uploads/story_images').'/'.$story_detail[0]->story_image; ?>" type="video/mp4">
  <source src="<?php echo base_url('uploads/story_images').'/'.$story_detail[0]->story_image; ?>" type="video/3gp">
</video>
                     <?php } ?>
                <br/><br/>
                </div>
                <?php } ?>

            <br/>
            <input type="file" name="image" value="<?php if(!empty($story_detail)){ echo $story_detail[0]->story_image; } ?>" class="form-control" <?php if(empty($story_detail[0]->story_image)){ ?>data-validate="required"<?php } ?> />
            
            </div>
             
             <div class="form-group">
                <label class="control-label">Title</label>
                <input type="text" name="story_title" placeholder="" class="form-control" data-validate="required" value="<?php if(!empty($story_detail)){ echo $story_detail[0]->story_title; }else{ if(isset($_POST['story_title'])){ echo $_POST['story_title']; } }?>" />
                
            </div>
             <div class="form-group">

                <label class="control-label">Description</label>

                <textarea name="story_description" id="" class="form-control ck_textarea_visible" placeholder="" rows="5" data-validate="required"><?php if(!empty($story_detail)){ echo trim($story_detail[0]->story_description); }else{ if(isset($_POST['story_description'])){ echo trim($_POST['story_description']); } }?></textarea>

            </div>
            <div class="form-group">

                <label class="control-label">Caption(optional)</label>

                <textarea name="story_caption" id="" class="form-control ck_textarea_visible" placeholder="" rows="5" ><?php if(!empty($story_detail)){ echo trim($story_detail[0]->story_caption); }else{ if(isset($_POST['story_caption'])){ echo trim($_POST['story_caption']); } }?></textarea>

            </div>

            <div class="form-group">
                <label class="control-label">First Comment(optional)</label>
                <input type="text" name="story_first_comment" placeholder="" class="form-control" data-validate="" value="<?php if(!empty($story_detail)){ echo $story_detail[0]->story_first_comment; }else{ if(isset($_POST['story_first_comment'])){ echo $_POST['story_first_comment']; } }?>" />
                
            </div>

            <div class="form-group">
                <label class="control-label">e-Ticket URL</label>
                <input type="text" name="ticket_link" placeholder="" class="form-control" data-validate="required" value="<?php if(!empty($story_detail)){ echo $story_detail[0]->ticket_link; }else{ if(isset($_POST['ticket_link'])){ echo $_POST['ticket_link']; } }?>" />
                
            </div>

<!--            <div class="form-group">

                <label class="control-label">When to post</label>

                <input type="datetime-local" name="post_datetime" placeholder="" class="form-control" value="<?php if(!empty($story_detail)){ $date = date('Y-m-d\Th:i', strtotime($story_detail[0]->post_datetime)); echo $date; }else{ if(isset($_POST['post_datetime'])){ echo $_POST['post_datetime']; } }?>" data-validate="required" /><br>

                <select class="form-control input-dark" name="post_timezone" data-validate="">

                    <option disabled="" selected="" hidden="">--Select Timezone--</option>
                    
                    <option <?php if(!empty($story_detail)){ if($story_detail[0]->post_timezone == "Israel/Jerusalem (UTC/GMT +02:00)"){ echo "selected='selected'"; } }  ?> value="Israel/Jerusalem (UTC/GMT +02:00)">Israel/Jerusalem (UTC/GMT +02:00)</option>
                    <option <?php if(!empty($story_detail)){ if($story_detail[0]->post_timezone == "Asia/Calcutta (UTC/GMT +05:30)"){ echo "selected='selected'"; } }  ?> value="Asia/Calcutta (UTC/GMT +05:30)">Asia/Calcutta (UTC/GMT +05:30)</option>
                    
                </select>

            </div>-->

            
            <div class="form-group">
                <label class="control-label">Set Limit for users</label>
                <input type="text" name="approved_user_limit" placeholder="" class="form-control" data-validate="required,number" value="<?php if(!empty($story_detail)){ echo $story_detail[0]->approved_user_limit; }else{ if(isset($_POST['approved_user_limit'])){ echo $_POST['approved_user_limit']; } }?>" />
                
            </div>
            <div class="form-group">
                <!--<label class="control-label">You can schedule your posts for up to a month.</label><br><br>-->
                <?php if(!empty($story_detail)){ ?>
                <input type="hidden" name="story_id" value="<?php echo $story_detail[0]->id; ?>" class="form-control">

                <input type="submit" name="submit" value="Update" class="btn btn-success">
                <?php } else { ?>
                <!--<input type="submit" name="submit_schedule" value="Schedule" class="btn btn-success" >-->

                <input type="submit" name="submit_post" value="Post Now" class="btn btn-success" >
<!--                <input type="submit" name="submit_post" value="Post Now" class="btn btn-default" style="border: 1px solid; border-color: #396C67 !important;">-->

                <!--<input type="submit" name="submit_save" value="Save as Draft" class="btn btn-basic">-->
                <?php } ?>
                
            </div>
        </form>
    </div>
</div>