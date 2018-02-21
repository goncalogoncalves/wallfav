$( window ).load(function() {

    $.dform.addType("estrela-vermelha", function(options) {
        return $("<span>").dform('attr', options).html("*");
    });

    $('#div-container-form-registo').dform({
        "method": "post",
        "id": "form-registar",

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
                    "placeholder": "Choose a password",
                    "type": "password",
                    "id": "user_password",
                    "validar_tamanho": "5",
                    "validar_obrigatorio": "1",
                    "validar_tipo": "password"
                }, {
                    "type": "estrela-vermelha",
                }]
            }, {
                "type": "p",
                "html": [{
                    "caption": "Repeat password",
                    "placeholder": "Repeat the password",
                    "type": "password",
                    "id": "user_password2",
                    "validar_tamanho": "5",
                    "validar_obrigatorio": "1",
                    "validar_tipo": "password2"
                }, {
                    "type": "estrela-vermelha",
                }]
            }, {
                "type": "p",
                "class": "p-checkbox",
                "html": [{
                    "type": "checkbox",
                    "caption": "<a target=\'_blank' href=\'/terms-and-conditions\'>I accept terms and conditions</a>",
                    "class": "termos_condicoes",
                    "validar_tamanho": "",
                    "validar_obrigatorio": "1",
                    "validar_tipo": "checkbox"
                }, {
                    "type": "estrela-vermelha",
                }]

        },{
            "type": "div",
            "id": "espaco-captcha",
            "html": "<i class='html_loader fa fa-spinner fa-spin '></i>"
        }
        ,{
            "type": "div",
            "id": "estado_registo"
        },{
            "type": "submit",
            "class": "bt-registar",
            "value": 'Submit'
        },{
            "type": "div",
            "id": "disability",
            "html": "<div class=\'disability\'>If you have a disability and you want to register, please <a target=\'_blank\' title=\'Contact us\' href=\'/contacts\'>contact us.</a></div>"
        }]
    });

    addCaptcha();

    $(".bt-registar").click(function(event) {

        event.preventDefault(); // nao submete o form

        var html_loader = '<i class=\'html_loader fa fa-spinner fa-spin \'></i>';
        $('#estado_registo').html(html_loader);

        var formulario_valido = validar_formulario('#form-registar');

        if (formulario_valido == true ) {

            // juntar a info do form
            /*var array_info = {};
            $('#form-registar *').filter(':input').each(function(){
                var elemento    = $(this);
                var el_valor    = elemento.val();
                var el_id       = elemento.attr("id");
                if (el_id != "undefined" && el_id != undefined) {
                    array_info[el_id] = el_valor;
                };
            });*/

            registarUser();
        };

    });

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
    var captcha_valido = valida_captcha();

    if (captcha_valido == "0") {
        $("#input-captcha").addClass("form-erro");

        formulario_valido = false;
    }else{
        $("#input-captcha").removeClass("form-erro");
    };

    $('#estado_registo').html("");

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
        url: "validateCaptcha",
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


function registarUser() {

    $("#estado_registo").html("<i class='fa fa-spinner fa-spin '></i>");

    var user_email     = $("#user_email").val();
    var user_password  = $("#user_password").val();
    var user_password2 = $("#user_password2").val();

    $.ajax({
        type: 'POST',
        url: '/login/registerUser',
        data: {
            user_email: user_email,
            user_password: user_password,
            user_password2: user_password2
        },
        success:function(data){

            var mensagem_registo = '';

            if (data == "1") {
                //mensagem_registo = 'Registration was successful! You can now <a href="'+BASE_URL+'login">login</a>.';
                mensagem_registo = 'Registration was successful! An email was sent with an activation link.';
            }else{
                mensagem_registo = 'The registration failed. Please try again.';
            }

            $("#estado_registo").html(mensagem_registo);

            $(".bt-registar").fadeOut();

        },
        error:function(data){
            $("#estado_registo").html('The registration failed. Please try again.');
        }
    });
}
