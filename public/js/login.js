$( window ).load(function() {

    loginUser();

    $.dform.addType("estrela-vermelha", function(options) {
        return $("<span>").dform('attr', options).html("*");
    });

    /* {
            "type": "p",
            "class": "p-checkbox",
            "html": [{
                "type": "checkbox",
                "id": "remember_me",
                "caption": "Remember Me",
                "class": "remember_me",
                "validar_tamanho": "",
                "validar_obrigatorio": "0",
                "validar_tipo": "checkbox"
            }]

        },*/

    $('#div-container-form-login').dform({
        "method": "post",
        "id": "form-login",

        "html": [{
            "type": "p",
            "html": [{
                "id": "user_email",
                "caption": "Email",
                "placeholder": "Your email",
                "type": "email",
                "validar_tamanho": "",
                "validar_obrigatorio": "1",
                "validar_tipo": "email"
            }, {
                "type": "estrela-vermelha",
            }]
        },
        {
            "type": "p",
            "html": [{
                "caption": "Password",
                "placeholder": "Your password",
                "type": "password",
                "id": "user_password",
                "validar_tamanho": "5",
                "validar_obrigatorio": "1",
                "validar_tipo": "password"
            }, {
                "type": "estrela-vermelha",
            }]
        },{
            "type": "div",
            "id": "espaco-captcha",
            "html": ""
        }
        ,{
            "type": "div",
            "id": "estado_login"
        },{
            "type": "submit",
            "class": "bt-login",
            "value": "Login"
        },{
            "type": "div",
            "id": "disability",
            "html": "<div class=\'clearfix links\'><a  title=\'Register\' href=\'/register\'>Register</a>&nbsp;&nbsp;&nbsp;<a target=\'_blank\' title=\'Forgot my Password\' href=\'/login/requestPasswordReset\'>Forgot my Password</a></div>"
        }]
    });
/*
setTimeout(function() {
    var user = '';
    user = $("#user_email").val();
    if (user != '') {
        loginUser();
    }
}, 1000);*/

    //addCaptcha();

    $(".bt-login").click(function(event) {

        event.preventDefault(); // nao submete o form

        //var html_loader = '<i class=\'html_loader fa fa-spinner fa-spin \'></i>';
        //$('#estado_login').html(html_loader);

        //var formulario_valido = validar_formulario('#form-login');

        //if (formulario_valido == true ) {

            loginUser();

        //};

    });

    /*var user_email    = $("#user_email").val();
    var user_password = $("#user_password").val();

    if (user_email != "" && user_password != "") {
        var formulario_valido = validar_formulario('#form-login');
        if (formulario_valido == true ) {
            loginUser();
        };
    };*/

});


/**
 * Responsavel por validar o formulario
 * @param  {string} formulario  o que identifica o formulario
 * @return {boolean}            se o formulario é valido
 */
 function validar_formulario(formulario) {

    var formulario_valido = true;
    var temp_password;

    $(formulario+' *').filter(':input').each(function(){
        var elemento        = $(this);
        var el_valor        = elemento.val();
        var el_tamanho      = elemento.attr("validar_tamanho");
        var el_obrigatorio  = elemento.attr("validar_obrigatorio");
        var el_tipo         = elemento.attr("validar_tipo");

        if ( (el_obrigatorio == "1" && el_valor == "") || (el_tamanho != "0" && el_valor.length < el_tamanho) ) {
            elemento.addClass("form-erro");
            formulario_valido = false;
        }else{
            elemento.removeClass("form-erro");
        }

        switch(el_tipo)
        {
            case "email":
            var email_valido = valida_email(el_valor);
            if (email_valido == false) {
                elemento.addClass("form-erro");
                formulario_valido = false;
            }else{
                elemento.removeClass("form-erro");
            }
            break;
            case "password":
            temp_password = el_valor;
            break;
            case "password2":
            if (temp_password != el_valor || el_valor.length < el_tamanho || el_valor == "") {
                elemento.addClass("form-erro");
                formulario_valido = false;
            }else{
                elemento.removeClass("form-erro");
            }
            break;
            case "checkbox":
            var checkbox_activa  = elemento.is(':checked');
            if (checkbox_activa == true) { checkbox_activa = "1"; }else{ checkbox_activa = "0"; }
            if (el_obrigatorio == "1" && checkbox_activa == "0") {
                elemento.addClass("form-erro");
                formulario_valido = false;
            }else{
                elemento.removeClass("form-erro");
            }
            break;
            default:
        }

    });

    // validar captcha
    /*var captcha_valido = valida_captcha();
    if (captcha_valido == "0") {
        $("#input-captcha").addClass("form-erro");
        formulario_valido = false;
    }else{
        $("#input-captcha").removeClass("form-erro");
    };*/

    $('#estado_login').html("");

    return formulario_valido;
}


/**
 * Valida o captcha
 * @return {boolean}  se esta valido ou nao 1/0
 */
 function valida_captcha() {

    var input_captcha = $("#input-captcha").val();
    var captcha_valido;

    $.ajax({
        url: "/login/validateCaptcha",
        cache: false,
        async: false,
        data: {
            aux: "validateCaptcha",
            input_captcha: input_captcha
        }
    }).done(function( resposta ) {
        captcha_valido = resposta;
    });

    return captcha_valido;
}

/**
 * Cria o captcha
 */
 function addCaptcha() {
    $.ajax({
        url: "/login/addCaptcha",
        cache: false
    }).done(function( resposta ) {
        $('#espaco-captcha').html(resposta);
    });
}


function loginUser() {

    $("#estado_login").html("<i class='fa fa-spinner fa-spin '></i>");

    var user_email    = $("#user_email").val();
    var user_password = $("#user_password").val();
    //var remember_me   = $("#remember_me").is(':checked');

    //if (remember_me == true){ remember_me = "1"; }else{remember_me = "0";}

    $.ajax({
        type: 'POST',
        url: 'login/login',
        data: {
            aux: "login/login",
            user_email: user_email,
            user_password: user_password,
            remember_me: 0
        },
        success:function(data){

            var mensagem_login = '';

            if (data == "1") {
                mensagem_login = 'Login was successful!';

                window.location = BASE_URL + "dashboard";

            }else{
                mensagem_login = 'The login failed. Please try again.';
            }

            $("#estado_login").html(mensagem_login);

        },
        error:function(data){ $("#estado_login").html("The login failed. Please try again."); }
    });
}
