<div class="page-title">
			
            <div class="title-env">
<!--                <h1 class="title">
                <?php if(!empty($courseTitle)) echo '<strong>Course : </strong>'.$courseTitle; ?>
                <?php if(!empty($subjectName)) echo '<strong>Subject : </strong>'.$subjectName; ?>
                <?php if(!empty($chapterTitle)) echo '<strong>Chapter : </strong>'.$chapterTitle; ?>
                <?php if(!empty($lectureTitle)) echo '<strong>Lecture : </strong>'.$lectureTitle; ?>
                </h1>-->
                <?php //print_r($question_list);
//                $question_id = 0;
//                if(!empty($question_list[0]->question_id))
//                $question_id = $question_list[0]->question_id;
//                
//                $breadcrumRecs = get_breadcrum_of_lecture_question($question_id); 
//                if(!empty($breadcrumRecs)){
//                    $courseTitle = $breadcrumRecs->course_title;
//                    $subjectName = $breadcrumRecs->subject_name;
//                    $chapterTitle = $breadcrumRecs->chapter_title;
//                    $lectureTitle = $breadcrumRecs->lecture_title;
//                    $testName = $breadcrumRecs->test_name;
//                }
 //print_r($breadcrumRecs);
                        ?>
                    <p class="description"></p>
            </div>
			
<div class="breadcrumb-env">

<ol class="breadcrumb bc-1" >
    <?php if(!empty($courseTitle)){ ?>
    <li <?php if(empty($subjectName)){ ?>class="active"<?php } ?>>
        <a href="<?php echo site_url('admin/edit_course') . '/' . $courseID; ?>"><i class=""></i>
            <?php //if(empty($subjectName)){ ?><?php //} ?>
                <?php if(!empty($courseTitle)) echo $courseTitle; ?>
                    <?php //if(empty($subjectName)){ ?><?php //} ?>
        </a>
    </li>
    <?php } ?>
<?php if(!empty($subjectName)){ ?>
    <li <?php if(empty($chapterTitle)){ ?>class="active"<?php } ?>>

        <a href="<?php echo site_url('admin/edit_subject') . '/' . $subjectID; ?>">
            <?php //if(empty($chapterTitle)){ ?><?php //} ?>
                <?php if(!empty($subjectName)) echo $subjectName; ?>
                    <?php //if(empty($chapterTitle)){ ?><?php //} ?>
        </a>
    </li>
<?php } ?>
<?php if(!empty($chapterTitle)){ ?>
    <li <?php if(empty($lectureTitle)){ ?>class="active"<?php } ?>>

        <a href="<?php echo site_url('admin/edit_chapter') . '/' . $chapterID; ?>">
            <?php //if(empty($lectureTitle)){ ?><?php //} ?>
                <?php if(!empty($chapterTitle)) echo $chapterTitle; ?>
                    <?php //if(empty($lectureTitle)){ ?><?php //} ?>
        </a>
    </li>
<?php } ?>

<?php if(!empty($lectureTitle)){ ?>
    <li <?php if(empty($testName)){ ?>class="active"<?php } ?>>

        <a href="<?php echo site_url('admin/edit_lecture') . '/' . $lectureID; ?>">
            <?php //if(empty($testName)){ ?><?php //} ?>
                <?php if(!empty($lectureTitle)) echo $lectureTitle; ?>
                    <?php //if(empty($testName)){ ?><?php //} ?>
        </a>
    </li>
<?php } ?>
<?php if(!empty($testName)){ ?>
    <li class="active">

<!--    <strong>-->
        <?php if(!empty($testName)) echo $testName; ?>
<!--    </strong>-->
    </li>
<?php } ?>


<li class="active">

<strong>
<?php //if(!empty($testName)) echo $testName; ?>
    <?php 
        if($this->uri->segment(2)=='add_subject'){
            echo 'Add Subject';
        }elseif($this->uri->segment(2)=='edit_subject'){
            echo 'Edit Subject';
        }elseif($this->uri->segment(2)=='subject_list'){
            echo 'Subject List';
        }elseif($this->uri->segment(2)=='add_chapter'){
            echo 'Add Chapter';
        }elseif($this->uri->segment(2)=='edit_chapter'){
            echo 'Edit Chapter';
        }elseif($this->uri->segment(2)=='chapter_list'){
            echo 'Chapter List';
        }elseif($this->uri->segment(2)=='add_lecture'){
            echo 'Add Lecture';
        }elseif($this->uri->segment(2)=='edit_lecture'){
            echo 'Edit Lecture';
        }elseif($this->uri->segment(2)=='lecture_list'){
            echo 'Lecture List';
        }elseif($this->uri->segment(2)=='test_list'){
            echo 'Test List';
        }elseif($this->uri->segment(2)=='add_question'){
            echo 'Add Question';
        }elseif($this->uri->segment(2)=='edit_question'){
            echo 'Edit Question';
        }elseif($this->uri->segment(2)=='question_list'){
            echo 'Test Question List';
        }elseif($this->uri->segment(2)=='add_practice_question'){
            echo 'Add Practice Question';
        }elseif($this->uri->segment(2)=='edit_practice_question'){
            echo 'Edit Practice Question';
        }elseif($this->uri->segment(2)=='practice_question_list'){
            echo 'Practice Question List';
        } 
    ?>

</strong>
</li>

</ol>

</div>
				
			</div>