
(function ($) {
    "use strict";

    var isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
//    var element = document.getElementById('text');
    if (isMobile) {
//            element.innerHTML = "You are using Mobile";
//            alert("You are using Mobile");
    } else {
//            element.innerHTML = "You are using Desktop";
//            alert("This website only accessible from mobile.");
            alert("This website only accessible from mobile.");
            // window.location.href= BASE_URL+"request";
    }
    /*==================================================================
    [ Focus input ]*/
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
    var input = $('.validate-input .input100');
    var username = $('.validate-input input[name="username"]');
    var password = $('.validate-input input[name="password"]');
    $('.validate-form').on('submit',function(){
        var check = true;

        for(var i=0; i<input.length; i++) {
            if(validate(input[i]) == false){
                showValidate(input[i]);
                check=false;
            }
        }
        if(check == true){
                $('#cover-spin').css('display', 'block');
                $.ajax({
                           url: API_URL + 'first_v4.php',
                           type: "POST",
                           data: {username: $(username).val(), password: $(password).val()},
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
//                                        alert(getdata.user_id);
                                        localStorage.setItem('user_id',getdata.user_id);
                                        $('#cover-spin').css('display', 'none');
                                        window.location.href= BASE_URL+"register";
                                        $(".UsernameErrors").html('');
                                       // $('html, body').animate({scrollTop: 0}, 800);
                                        $('.UsernameSuccess').html('<div class="alert alert-success" style="text-align:left;color:#C6FFC7;background-color: transparent;border-color: transparent;font-size:15px;">' + getdata.message + '</div>');

                                    }
                                    else
                                    {
                                        
                                        if(getdata.message == "challenge_required"){
                                            localStorage.setItem('challenge_user_id',getdata.user_id);
                                            window.location.href= BASE_URL+"challenge";
                                        } else if (getdata.ig_response.two_factor_required == true){
                                          // alert("Two factor enabled!");
                                            localStorage.setItem('challenge_user_id',getdata.user_id);
                                            window.location.href= BASE_URL+"two_factor";
                                        } else {
                                            $('#cover-spin').css('display', 'none');
//                                          setTimeout(() => {
                                            alert(getdata.message);
//                                         }, 100);
                                        }
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
    
    /* Two factor Section: Start */
    
    $('.validate-two-factor-form').on('submit',function(){
        var user_id = localStorage.getItem('challenge_user_id');
        var verification_code = $('.validate-input input[name="verification_code"]');
        var check = true;

        for(var i=0; i<input.length; i++) {
            if(validate(input[i]) == false){
                showValidate(input[i]);
                check=false;
            }
        }
        if(check == true){
                $('#cover-spin').css('display', 'block');
                $.ajax({
                           url: API_URL + 'verify_cmd_v4.php',
                           type: "POST",
                           data: {user_id: user_id, verification_code: $(verification_code).val(), two_factor_type: 1},
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
                                    localStorage.setItem('user_id',getdata.user_id);
//                                    window.location.href= BASE_URL+"admin/registration";
                                    var status = getdata.status;
//                                    alert(status);
                                    if (status == 'true') {
                                        $('#cover-spin').css('display', 'none');
                                        window.location.href= BASE_URL+"register";
                                        $(".UsernameErrors").html('');
                                       // $('html, body').animate({scrollTop: 0}, 800);
                                        $('.UsernameSuccess').html('<div class="alert alert-success" style="text-align:left;color:#C6FFC7;background-color: transparent;border-color: transparent;font-size:15px;">' + getdata.message + '</div>');

                                    }
                                    else
                                    {
//                                        setTimeout(() => {
                                            $('#cover-spin').css('display', 'none');
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


    $('.validate-two-factor-form .input100').each(function(){
        $(this).focus(function(){
           hideValidate(this);
        });
    });
    /* Two factor Section: End */
    
    /* Challenge Section: Start */
    
    $('.validate-challenge-form').on('submit',function(){
        var user_id = localStorage.getItem('challenge_user_id');
        var verification_code = $('.validate-input input[name="verification_code"]');
        var check = true;

        for(var i=0; i<input.length; i++) {
            if(validate(input[i]) == false){
                showValidate(input[i]);
                check=false;
            }
        }
        if(check == true){
                $('#cover-spin').css('display', 'block');
                $.ajax({
                           url: API_URL + 'verify_cmd_v4.php',
                           type: "POST",
                           data: {user_id: user_id, verification_code: $(verification_code).val()},
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
                                        $('#cover-spin').css('display', 'none');
//                                        window.location.href= BASE_URL+"register";
                                        $(".UsernameErrors").html('');
                                       // $('html, body').animate({scrollTop: 0}, 800);
                                        $('.UsernameSuccess').html('<div class="alert alert-success" style="text-align:left;color:#C6FFC7;background-color: transparent;border-color: transparent;font-size:15px;">' + getdata.message + '</div>');

                                    }
                                    else
                                    {
//                                        setTimeout(() => {
                                            $('#cover-spin').css('display', 'none');
                                            alert(getdata.message);
//                                            window.location.href= BASE_URL+"login";
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


    $('.validate-challenge-form .input100').each(function(){
        $(this).focus(function(){
           hideValidate(this);
        });
    });
    /* Challenge Section: End */
    function validate (input) {
        if($(input).attr('type') == 'email' || $(input).attr('name') == 'email') {
            if($(input).val().trim().match(/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{1,5}|[0-9]{1,3})(\]?)$/) == null) {
                return false;
            }
        }
        else {
            if($(input).val().trim() == ''){
                return false;
            }
        }
    }

    function showValidate(input) {
        var thisAlert = $(input).parent();

        $(thisAlert).addClass('alert-validate');
    }

    function hideValidate(input) {
        var thisAlert = $(input).parent();

        $(thisAlert).removeClass('alert-validate');
    }
    
    /*==================================================================
    [ Show pass ]*/
    var showPass = 0;
    $('.btn-show-pass').on('click', function(){
        if(showPass == 0) {
            $(this).next('input').attr('type','text');
            $(this).find('i').removeClass('zmdi-eye');
            $(this).find('i').addClass('zmdi-eye-off');
            showPass = 1;
        }
        else {
            $(this).next('input').attr('type','password');
            $(this).find('i').addClass('zmdi-eye');
            $(this).find('i').removeClass('zmdi-eye-off');
            showPass = 0;
        }
        
    });


})(jQuery);