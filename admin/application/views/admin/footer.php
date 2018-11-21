<!-- Main Footer -->
<!-- Choose between footer styles: "footer-type-1" or "footer-type-2" -->
<!-- Add class "sticky" to  always stick the footer to the end of page (if page contents is small) -->
<!-- Or class "fixed" to  always fix the footer to the end of page -->
<footer class="main-footer sticky footer-type-1">

    <div class="footer-inner">

        <!-- Add your copyright text here -->
        <div class="footer-text">
            &copy; 2018 
            <strong>10k-Club</strong> 
        </div>


        <!-- Go to Top Link, just add rel="go-top" to any link to add this functionality -->
        <div class="go-up">

            <a href="#" rel="go-top">
                <i class="fa-angle-up"></i>
            </a>

        </div>

    </div>

</footer>
</div>

</div>


<script type="text/javascript">
$(document).ready(function(){
    $("#sendMsgForm").on('submit',(function(e){
        $('.msg_error').html("");            
        e.preventDefault();
        if($('#message').val().trim()=='' || $('#course_id').val().trim()=='' || $('#student_id').val().trim()=='' || $('#parent_id').val().trim()=='' || $('#parent_name').val().trim()=='')
        {
            return false;
        }
        $.ajax({
            url: "<?php echo site_url('admin/send_msg'); ?>",
            type: "POST",                
            data:  new FormData(this),
            contentType: false,
            cache: false,
            processData:false,
            success: function(data){
                // alert(data);
                var obj = JSON.parse(data);
                if(obj.success!=null)
                {
                    if(obj.success==true)
                    {                            
                        $('#message').val("");
                        var msg = '<div class="row"><div class="msg msg_sent">'+obj.msg+'</div></div>';
                        $('#message_list').append(msg);
                        $("#message_list").animate({ scrollTop: $('#message_list').prop("scrollHeight")}, 1000);
                    }
                    else
                    {                        
                        $('.msg_error').html(obj.msg);
                    }
                }
            },
            error: function(){
                alert("Some error occured!");
            }             
        });
    }));

});    

</script>
        
</body>
</html>
