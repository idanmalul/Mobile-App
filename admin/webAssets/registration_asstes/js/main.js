
(function ($) {
    "use strict";

var userid = localStorage.getItem('user_id');
//alert(userid);
//if(!userid){
//    window.location.href= BASE_URL+"login";
//}

var isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
//    var element = document.getElementById('text');
    if (isMobile) {
//            element.innerHTML = "You are using Mobile";
//            alert("You are using Mobile");
    } else {
//            element.innerHTML = "You are using Desktop";
            alert("This website only accessible from mobile.");
            window.location.href= BASE_URL+"request";
    }
//            alert(userid);
    /*==================================================================
    [ Focus Contact2 ]*/
    $('.input100').each(function(){
        $(this).on('blur', function(){
            if($(this).val().trim() != "") {
                $(this).addClass('has-val');
            }
            else {
                $(this).removeClass('has-val');
            }
        })    
    })
  
  
    /*==================================================================
    [ Validate ]*/
    var date_of_birth = $('.validate-input input[name="date_of_birth"]');
    
//     var gender = $('#gender');
     var gender = $('.validate-input select[name="gender"]');
//    var gender = $('#gender');
//    alert(JSON.stringify(gender));
    var city = $('.validate-input input[name="city"]');
    var state = $('.validate-input input[name="state"]');
    // var radio1 = $('.validate-input input[name="radio1"]');
    // var email = $('.validate-input input[name="email"]');
    // var message = $('.validate-input textarea[name="message"]');

var radio1 = $('.validate-input input[name="radio1"]');
//alert(radio1+":checked");
var username = $('.validate-input input[name="username"]');
var password = $('.validate-input input[name="password"]');

$("input[type='radio']").click(function(){
            var radioValue = $("input[name='radio1']:checked").val();
            if(radioValue == 2){
                $("#create_new_pass").show();
                $("#username").show();
                $("#password").show();
                hideValidate(username);
                hideValidate(password);
            }else{
                $("#create_new_pass").hide()
                $("#username").hide();
                $("#password").hide();
                hideValidate(username);
                hideValidate(password);
            }
        });
        
    $('.validate-form').on('submit',function(){
//        alert($("input[name='radio1']:checked").val());
        var check = true;

        if($(date_of_birth).val().trim() == ''){
            showValidate(date_of_birth);
            check=false;
        }

         if($(gender).val().trim() == ''){
             showValidate(gender);
             check=false;
         }else{
             hideValidate(gender);
//             alert("No");
//            check=true;
             
         }
        

        if($(city).val().trim() == ''){
            showValidate(city);
            check=false;
        }

        if($(state).val().trim() == ''){
            showValidate(state);
            check=false;
        }
        
//        if($(username).val().trim() == ''){
//            showValidate(username);
//            check=false;
//        }else{
//             hideValidate(username);
////             alert("No");
////            check=true;
//             
//         }
//        
//        if($(password).val().trim() == ''){
//            showValidate(password);
//            check=false;
//        }else{
//             hideValidate(password);
////             alert("No");
////            check=true;
//             
//         }
//alert($("input[name='radio1']").is(":checked"));
         if($("input[name='radio1']").is(":checked") === false){
//             alert("yes");
             showValidate(radio1);
             check=false;
         }else{
             hideValidate(radio1);
//             alert("No");
//            check=true;
             
         }


        // if($(email).val().trim().match(/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{1,5}|[0-9]{1,3})(\]?)$/) == null) {
        //     showValidate(email);
        //     check=false;
        // }

        // if($(message).val().trim() == ''){
        //     showValidate(message);
        //     check=false;
        // }

        //return check;
        if(check == true){
//            alert($(radio1).val());
//            alert($("input[name='radio1']:checked").val());
//            return false;
//            var user_id = localStorage.getItem('user_id');
//            alert(user_id);
                $('#cover-spin').css('display', 'block');
                $.ajax({
                           url: API_URL + 'edit_user_profile_web.php',
                           type: "POST",
//                           data: {username: $(username).val(), password: $(password).val()},
                             data: {
                                 first_name: '', 
                                    last_name: '',
                                    user_name: '',
                                    user_id: userid,
                                    email: $("#email").val(),
                                    gender: $(gender).val(),
                                    age: '',
                                    birthday : $(date_of_birth).val(),
                                    country_code : '',
                                    phone_number : $("#phone").val(),
                                    address : $("#address").val(),
                                    postal_code : $("#postal_code").val(),
                                    city : $(city).val(),
                                    provision : $(state).val(),
                                    create_pass_type : $("input[name='radio1']:checked").val(),
                                    username : $(username).val(),
                                    password : $(password).val(),
                                },
                           crossDomain: true,
//                           dataType: 'jsonp',
                           success: function(result){
                //               alert(response)
                                // if(response != '') {
                                //    $("#timeSlotsType").html(response);
                                // }
                                if (result != '') {
//                                    var getdata = jQuery.parseJSON(result);
                                    var getdata = jQuery.parseJSON(JSON.stringify(result));
                                    console.log(getdata);
                                    
//                                    window.location.href= BASE_URL+"admin/registration";
                                    var status = getdata.status;
//                                    alert(status);
                                    if (status == 'true') {
                                        localStorage.setItem('user_id','');
                                        localStorage.setItem('challenge_user_id','');
                                        $('#cover-spin').css('display', 'none');
                                        window.location.href= BASE_URL+"success";
                                        $(".UsernameErrors").html('');
                                       // $('html, body').animate({scrollTop: 0}, 800);
                                        $('.UsernameSuccess').html('<div class="alert alert-success" style="text-align:left;color:#C6FFC7;background-color: transparent;border-color: transparent;font-size:15px;">' + getdata.message + '</div>');

                                    }
                                    else
                                    {
                                        
                                        
                                            $('#cover-spin').css('display', 'none');
//                                          setTimeout(() => {
                                            alert(getdata.message);
//                                         }, 100);
                                        
                                        $(".UsernameSuccess").html('');
                                       // $('html, body').animate({scrollTop: 0}, 800);
                                        $('.UsernameErrors').html('<div class="alert alert-danger" style="text-align:left;color: #ff0000;background-color: transparent;border-color: transparent;font-size:15px;">' + getdata.message + '</div>');
                                        return false;
                                    }
                                }
                           }
                        });
                        return false;
        }else{
            return check;
        }
    });


    $('.validate-form .input100').each(function(){
        $(this).focus(function(){
           hideValidate(this);
       });
    });

    function showValidate(input) {
        var thisAlert = $(input).parent();

        $(thisAlert).addClass('alert-validate');
    }

    function hideValidate(input) {
        var thisAlert = $(input).parent();

        $(thisAlert).removeClass('alert-validate');
    }
    
    

})(jQuery);