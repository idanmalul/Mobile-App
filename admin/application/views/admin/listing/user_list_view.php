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

<!-- Custom column filtering -->
<form method="post" action="<?php echo site_url('admin/test'); ?>">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">User List</h3>
                    
                    <div class="panel-options">
                        <!-- <a href="#" data-toggle="panel">
                            <span class="collapse-icon">&ndash;</span>
                            <span class="expand-icon">+</span>
                        </a> -->

                        <a href="#" class="btn btn-turquoise " style="color: #fff;">
                           < Send offer
                        </a>  

<!--                        <input type="submit" name="submit" value="< Send offer">-->

                        <!-- <a href="#" data-toggle="remove">
                            &times;
                        </a> -->
                    </div>
                </div>
                <div class="panel-body table-responsive">
                    
                    <script type="text/javascript">
                    jQuery(document).ready(function($)
                    {
                        $("#example-3").dataTable().yadcf([
                            {column_number : 6, filter_type: 'text'},
                            {column_number : 8},
                            {column_number : 9}
                        ]);

                        // Replace checkboxes when they appear
                        var $state = $("#example-3 thead input[type='checkbox']");
                        
                        $("#example-3").on('draw.dt', function()
                        {

                            cbr_replace();
                            
                            $state.trigger('change');
                        });
                        
                        // Script to select all checkboxes
                        $state.on('change', function(ev)
                        {
                            var $chcks = $("#example-3 tbody input[type='checkbox']");
                            
                            if($state.is(':checked'))
                            {
                                $chcks.prop('checked', true).trigger('change');
                                
                            }
                            else
                            {
                                $chcks.prop('checked', false).trigger('change');
                            }
                        });

                        

                       


                    });
                    </script>
                    
                    <table class="table table-striped table-bordered " id="example-3" style="width: 1366px !important;">
                        <thead>
                            <tr class="replace-inputs">
                                <th class="no-sorting">
                                    <input type="checkbox" class="cbr">

                                </th>
                                <th>S.No.</th>
                                <th>First name</th>
                                <th>Last name</th>
                                <th>ID</th>
                                <th>Email</th>
                                <th>Age</th>
                                <th>Gender</th>
                                <th>Favourites</th>
                                <th>Followers amount</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php if (!empty($user_list)) {
                                $n = 1;
                                foreach ($user_list as $value) {
                                    if(!empty($value->pk)){
                                $favourite_list = active_favorite_list($value->favourites_id);

                                $name = explode(" ", $value->full_name);
                                $first_name = $name[0];
                                $last_name = $name[1];

                                if($value->gender == 1)
                                {
                                    $gender = "Male";
                                }
                                elseif($value->gender == 2)
                                {
                                    $gender = "Female";
                                }
                                elseif($value->gender == 3)
                                {
                                    $gender = "Not Specified";
                                }
                                else
                                {

                                }
                                // echo $first_name; die();
                            ?>

                            <tr id="tr">
                                <td style="width: 2px !important;" >
                                    <input type="checkbox" class="cbr"  name="user_[]" value="<?php echo $value->username; ?>">
                                </td>
                                <td style="width: 2px !important;"><?php echo $n; ?></td>
                                <td style="width: 10px !important;"><?php if(!empty($first_name)) echo $first_name; ?></td>
                                <td style="width: 10px !important;"><?php if(!empty($last_name)) echo $last_name; ?></td>
                                <td style="width: 5px !important;"><?php echo $value->username; ?></td>
                                <td style="width: 5px !important;"><?php echo $value->email; ?></td>
                                <td style="width: 5px !important;" ><?php echo $value->age; ?></td>
                                <td style="width: 10px !important;"><?php if(!empty($gender)) echo $gender; ?></td>
                                <td style="width: 15px !important;" ><?php if(!empty($favourite_list)) echo $favourite_list[0]->favourite_name; ?></td>
                                <td style="width: 15px !important;" ><?php echo $value->follower_count; ?></td>
                            </tr>

                            <?php $n = $n + 1; } } } ?>
                            
                        </tbody>
                        
                    </table>
                    
                </div>
            </div>
        </form>