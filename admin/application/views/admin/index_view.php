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
<!--<div class="row">
    <div class="col-sm-4">

        <div class="xe-widget xe-counter xe-counter-purple" data-count=".num" data-from="1" data-to="25" data-suffix="" data-duration="3" data-easing="false">
            <div class="xe-icon">
                <i class="linecons-user"></i>
            </div>
            <div class="xe-label">
                <strong class="num">1</strong>
                <span>Customers Total</span>
            </div>
        </div>
        
    </div>
    <div class="col-sm-4">

        <div class="xe-widget xe-counter xe-counter-info" data-count=".num" data-from="0" data-to="50" data-duration="4" data-easing="true">
            <div class="xe-icon">
                <i class="linecons-tag"></i>
            </div>
            <div class="xe-label">
                <strong class="num">1000</strong>
                <span>New Daily Request</span>
            </div>
        </div>

    </div>
</div>-->
<div class="panel panel-default">
			
				<div class="panel-heading">
					<div class="panel-title">
						Dashboard
					</div>
					
					
				</div>
				
				<div class="panel-body">
				
				</div>
    
                                
			
			</div>
<div class="row">
			
                        
<!--				<div class="col-sm-4">
                                    <div class="xe-widget xe-counter xe-counter-blue" data-count=".num" data-from="1" data-to="<?php if(!empty($no_of_users)){ echo $no_of_users; }?>" data-suffix="" data-duration="1" data-easing="false">
                                                <div class="xe-icon">
                                                        <i class="linecons-user"></i>
                                                </div>
                                                <div class="xe-label">
                                                        <strong class="num">0</strong>
                                                        <span>Users</span>
                                                </div>
                                        </div>
                                </div>-->
<!--				<div class="col-sm-4">
                                        <div class="xe-widget xe-counter xe-counter-info" data-count=".num" data-from="1" data-to="<?php if(!empty($no_of_active_vendors)){ echo $no_of_active_vendors; }?>" data-duration="2" data-easing="true">
                                                <div class="xe-icon">
                                                        <i class="fa-life-ring"></i>
                                                </div>
                                                <div class="xe-label">
                                                        <strong class="num">0</strong>
                                                        <span>Active Vendors</span>
                                                </div>
                                        </div>
                                </div>
    
                                <div class="col-sm-4">
                                        <div data-duration="2" data-suffix="" data-to="<?php if(!empty($no_of_inactive_vendors)){ echo $no_of_inactive_vendors; }?>" data-from="0" data-count=".num" class="xe-widget xe-counter xe-counter-block-orange">
                                            <div class="xe-icon">
                                                <i class="fa-life-ring"></i>
                                            </div>
                                            <div class="xe-label">
                                                <strong class="num">0</strong>
                                                <span>Inactive Vendors</span>
                                            </div> 
                                        </div>

                                </div>-->
                    
    
                                </div>