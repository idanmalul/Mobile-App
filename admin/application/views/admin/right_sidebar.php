<div class="panel panel-default" id="left">

    <div class="panel-heading">

        <div class="panel-title">

        </div>
    </div>
<?php 
$admin_type = $this->session->userdata('admin_type');
if($admin_type==4){
    $courseRecs = get_teacher_related_course();
}else{
    $courseRecs = get_all_course(); 
}
if(!empty($courseRecs)){ $i=1; foreach ($courseRecs as $key => $value) {
    if($admin_type==4){
        $subjectRecs = get_teacher_related_subject($value->course_id);
    }else{
        $subjectRecs = get_subject_by_course($value->course_id);
    }
    
    
    ?>
        <ul id="menu-group-1<?php echo $i; ?>" class="nav menu">  

            <li class="item-8 deeper parent <?php if(($this->uri->segment(2) == 'edit_course' && $this->uri->segment(3) == $value->course_id)){ echo 'active'; } ?><?php if(!empty($subjectRecs)){ foreach ($subjectRecs as $first) { 
                $chapterRecs = get_chapter_by_subject($first->subject_id);
                if($this->uri->segment(2) == 'edit_subject' && $this->uri->segment(3) == $first->subject_id){
                    echo 'active';
                }elseif(!empty($chapterRecs)){ foreach ($chapterRecs as $second) { 
                    $lectureRecs = get_lecture_by_chapter($second->chapter_id);
                    if($this->uri->segment(2) == 'edit_chapter' && $this->uri->segment(3) == $second->chapter_id){
                    echo 'active';
                    }elseif(!empty($lectureRecs)){ foreach ($lectureRecs as $third) { 
                    if($this->uri->segment(2) == 'edit_lecture' && $this->uri->segment(3) == $third->lecture_id){
                    echo 'active';
                    } 
                    }} 
                }} }} ?>" >
                    <a class="" href="<?php echo site_url('admin/edit_course') . '/' . $value->course_id; ?>">
                        <span data-toggle="collapse" data-parent="#menu-group-1<?php echo $i; ?>" href="#sub-item-8<?php echo $i; ?>" class="sign"><i class="fa fa-plus icon-plus icon-white"></i></span>
                        <span class="lbl"><?php echo $value->course_title; ?></span>                      
                    </a>
                    
                    <ul class="children nav-child unstyled small collapse <?php if(($this->uri->segment(2) == 'edit_course' && $this->uri->segment(3) == $value->course_id)){ ?>in<?php } ?><?php if(!empty($subjectRecs)){ foreach ($subjectRecs as $first) { 
                $chapterRecs = get_chapter_by_subject($first->subject_id);
                if($this->uri->segment(2) == 'edit_subject' && $this->uri->segment(3) == $first->subject_id){
                    echo 'in';
                }elseif(!empty($chapterRecs)){ foreach ($chapterRecs as $second) { 
                    $lectureRecs = get_lecture_by_chapter($second->chapter_id);
                    if($this->uri->segment(2) == 'edit_chapter' && $this->uri->segment(3) == $second->chapter_id){
                    echo 'in';
                    }elseif(!empty($lectureRecs)){ foreach ($lectureRecs as $third) { 
                    if($this->uri->segment(2) == 'edit_lecture' && $this->uri->segment(3) == $third->lecture_id){
                    echo 'in';
                    } 
                    }} 
                }} }} ?>" id="sub-item-8<?php echo $i; ?>">
                        <?php if(!empty($subjectRecs)){ foreach ($subjectRecs as $value1) { 
                      $chapterRecs = get_chapter_by_subject($value1->subject_id);                               ?>
                        <li class="item-9 deeper parent <?php if($this->uri->segment(2) == 'edit_subject' && $this->uri->segment(3) == $value1->subject_id){ echo 'active'; } ?><?php if(!empty($chapterRecs)){ foreach ($chapterRecs as $second) { 
                    $lectureRecs = get_lecture_by_chapter($second->chapter_id);
                    if($this->uri->segment(2) == 'edit_chapter' && $this->uri->segment(3) == $second->chapter_id){
                    echo 'active';
                    }elseif(!empty($lectureRecs)){ foreach ($lectureRecs as $third) { 
                    if($this->uri->segment(2) == 'edit_lecture' && $this->uri->segment(3) == $third->lecture_id){
                    echo 'active';
                    } 
                    }} 
                }} ?>">
                            <a class="" href="<?php echo site_url('admin/edit_subject') . '/' . $value1->subject_id; ?>">
                                <span data-toggle="collapse" data-parent="#menu-group-1<?php echo $i; ?>" href="#sub-item-9<?php echo $i; ?>" class="sign"><i class="fa fa-plus icon-plus icon-white"></i></span>
                                <span class="lbl"><?php echo $value1->subject_name; ?></span> 
                            </a>
                            
                            <ul class="children nav-child unstyled small collapse <?php if($this->uri->segment(2) == 'edit_subject' && $this->uri->segment(3) == $value1->subject_id){ ?>in<?php } ?><?php if(!empty($chapterRecs)){ foreach ($chapterRecs as $second) { 
                    $lectureRecs = get_lecture_by_chapter($second->chapter_id);
                    if($this->uri->segment(2) == 'edit_chapter' && $this->uri->segment(3) == $second->chapter_id){
                    echo 'in';
                    }elseif(!empty($lectureRecs)){ foreach ($lectureRecs as $third) { 
                    if($this->uri->segment(2) == 'edit_lecture' && $this->uri->segment(3) == $third->lecture_id){
                    echo 'in';
                    } 
                    }} 
                }} ?>" id="sub-item-9<?php echo $i; ?>">
                             <?php if(!empty($chapterRecs)){ foreach ($chapterRecs as $value2) { 
                              $lectureRecs = get_lecture_by_chapter($value2->chapter_id);   
                                 ?>   
                        <li class="item-10 deeper parent <?php if($this->uri->segment(2) == 'edit_chapter' && $this->uri->segment(3) == $value2->chapter_id){ echo 'active'; } ?><?php if(!empty($lectureRecs)){ foreach ($lectureRecs as $third) { if($this->uri->segment(2) == 'edit_lecture' && $this->uri->segment(3) == $third->lecture_id){ echo 'active'; } }} ?>">
                            <a class="" href="<?php echo site_url('admin/edit_chapter') . '/' . $value2->chapter_id; ?>">
                                <span data-toggle="collapse" data-parent="#sub-item-8<?php echo $i; ?>" href="#sub-item-10<?php echo $i; ?>" class="sign"><i class="fa fa-plus icon-plus icon-white"></i></span>
                                <span class="lbl"><?php echo $value2->chapter_title; ?></span> 
                            </a>
                            
                            <ul class="children nav-child unstyled small collapse <?php if($this->uri->segment(2) == 'edit_chapter' && $this->uri->segment(3) == $value2->chapter_id){ echo 'in'; } ?><?php if(!empty($lectureRecs)){ foreach ($lectureRecs as $third) { if($this->uri->segment(2) == 'edit_lecture' && $this->uri->segment(3) == $third->lecture_id){ echo 'in'; } }} ?>" id="sub-item-10<?php echo $i; ?>">
                                <?php if(!empty($lectureRecs)){ foreach ($lectureRecs as $value3) { ?>
                                <li class="item-11 <?php if($this->uri->segment(2) == 'edit_lecture' && $this->uri->segment(3) == $value3->lecture_id){ echo 'active'; } ?>">
                                    <a class="" href="<?php echo site_url('admin/edit_lecture') . '/' . $value3->lecture_id; ?>">
                                        <span class="sign"><i class="icon-play"></i></span>
                                       <span class="lbl">-> <?php echo $value3->lecture_title; ?></span>
                                    </a>
                                    
                                </li>
                                <?php $i=$i+1; }} ?>
<!--                                <li class="item-12">
                                    <a class="" href="https://www.google.co.in/?gfe_rd=cr&ei=VpEuWaOWKoHT8geo-KLYDg&gws_rd=ssl#q=sidebar+menu">
                                        <span class="sign"><i class="icon-play"></i></span>
                                        <span class="lbl">Menu 1.2</span> 
                                    </a>
                                </li>                                -->
                            </ul>
                        </li>
                        <?php $i=$i+1; }} ?>
<!--                        <li class="item-13 deeper parent">
                            <a class="" href="#">
                                <span data-toggle="collapse" data-parent="#menu-group-1" href="#sub-item-12" class="sign"><i class="fa fa-plus icon-plus icon-white"></i></span>
                                <span class="lbl">Menu 2</span> 
                            </a>
                            <ul class="children nav-child unstyled small collapse" id="sub-item-12">
                                <li class="item-14">
                                    <a class="" href="#">
                                        <span class="sign"><i class="icon-play"></i></span>
                                        <span class="lbl">Menu 2.1</span>                                    
                                    </a>
                                </li>
                                <li class="item-15">
                                    <a class="" href="#">
                                        <span class="sign"><i class="icon-play"></i></span>
                                        <span class="lbl">Menu 2.2</span>                                    
                                    </a>
                                </li>
                            </ul>
                        </li>-->
                    </ul>
                            
                            
<!--                            <ul class="children nav-child unstyled small collapse" id="sub-item-9">
                                <li class="item-10">
                                    <a class="" href="https://www.google.co.in/?gfe_rd=cr&ei=VpEuWaOWKoHT8geo-KLYDg&gws_rd=ssl#q=sidebar+menu">
                                        <span class="sign"><i class="icon-play"></i></span>
                                        <span class="lbl">Menu 1.1</span>
                                    </a>
                                    
                                </li>
                                <li class="item-11">
                                    <a class="" href="https://www.google.co.in/?gfe_rd=cr&ei=VpEuWaOWKoHT8geo-KLYDg&gws_rd=ssl#q=sidebar+menu">
                                        <span class="sign"><i class="icon-play"></i></span>
                                        <span class="lbl">Menu 1.2</span> 
                                    </a>
                                </li>                                
                            </ul>-->
                        </li>
                        <?php $i=$i+1; }} ?>
<!--                        <li class="item-12 deeper parent">
                            <a class="" href="#">
                                <span data-toggle="collapse" data-parent="#menu-group-1" href="#sub-item-12" class="sign"><i class="fa fa-plus icon-plus icon-white"></i></span>
                                <span class="lbl">Menu 2</span> 
                            </a>
                            <ul class="children nav-child unstyled small collapse" id="sub-item-12">
                                <li class="item-13">
                                    <a class="" href="#">
                                        <span class="sign"><i class="icon-play"></i></span>
                                        <span class="lbl">Menu 2.1</span>                                    
                                    </a>
                                </li>
                                <li class="item-14">
                                    <a class="" href="#">
                                        <span class="sign"><i class="icon-play"></i></span>
                                        <span class="lbl">Menu 2.2</span>                                    
                                    </a>
                                </li>
                            </ul>
                        </li>-->
                    </ul>
                    
                </li>  
                
            </ul> 
    <?php $i=$i+1; }} ?>

 </div>
<!--<div class="sidebar-menu toggle-others right_menu">-->
<!--<div class="toggle-others right_menu">
<div class="sidebar-menu-inner">

                <?php if(!empty(get_all_course())){ foreach (get_all_course() as $key => $value) { ?>
                    <ul id="main-menu1_<?php echo $value->course_id; ?>" class="main-menu">

                        <li>

                            <a href="<?php echo site_url('admin/edit_course') . '/' . $value->course_id; ?>">

                            <i class="fa fa-list"></i>

                            <span class="title"><?php echo $value->course_title; ?></span>

                            </a>
                            
                        <?php if(!empty(get_subject_by_course($value->course_id))){ foreach (get_subject_by_course($value->course_id) as $value1) { ?>
                            <ul>

                                <li><a href="<?php echo site_url('admin/edit_subject') . '/' . $value1->subject_id; ?>">

                                    <span class="title"><?php echo $value1->subject_name; ?></span>

                                    </a>
<?php if(!empty(get_chapter_by_subject($value1->subject_id))){ foreach (get_chapter_by_subject($value1->subject_id) as $value2) { ?>
                                    <ul>

                                        <li>

                                        <a href="">

                                        <span class="title"><?php echo $value2->chapter_title; ?></span>

                                        </a>
<?php if(!empty(get_lecture_by_chapter($value2->chapter_id))){ foreach (get_lecture_by_chapter($value2->chapter_id) as $value3) { ?>
                                        <ul>

                                            <li><a href="">

                                            <span class="title"><?php echo $value3->lecture_title; ?></span>

                                            </a></li>

                                        </ul>
                                    <?php }} ?>
                                        </li>

                                    </ul>
                                <?php }} ?>
                                </li>

                            </ul>
                        <?php }} ?>
                            
                        </li>

                    </ul>
                <?php }} ?>
                    <ul id="main-menu1" class="main-menu">

                        <li>

                            <a href="">

                            <i class="fa fa-list"></i>

                            <span class="title">Course</span>

                            </a>

                            <ul>

                                <li><a href="">

                                    <span class="title">Subject</span>

                                    </a>

                                    <ul>

                                        <li>

                                        <a href="">

                                        <span class="title">Chapter</span>

                                        </a>

                                        <ul>

                                            <li><a href="">

                                            <span class="title">Lecture</span>

                                            </a></li>

                                        </ul>

                                        </li>

                                    </ul>

                                </li>

                            </ul>

                        </li>

                    </ul>


               </div>
    </div>-->
<script type="text/javascript">
    !function ($) {
    // Le left-menu sign
    /* for older jquery version
    $('#left ul.nav li.parent > a > span.sign').click(function () {
        $(this).find('i:first').toggleClass("icon-minus");
    }); */
    
    $(document).on("click","#left ul.nav li.parent > a > span.sign", function(){
        $(this).find('i:first').toggleClass("icon-minus fa fa-minus");      
    }); 
    
    // Open Le current menu
    $("#left ul.nav li.parent.active > a > span.sign").find('i:first').addClass("icon-minus fa fa-minus");
    $("#left ul.nav li.current").parents('ul.children').addClass("in");

}(window.jQuery);
</script>

