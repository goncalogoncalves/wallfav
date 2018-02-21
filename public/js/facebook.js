function statusChangeCallback(response) {
	console.log('statusChangeCallback');
	console.log(response);
	if (response.status === 'connected') {

		recolherInfo();
	} else if (response.status === 'not_authorized') {
		console.log('Please log into this app.');
	} else {
		console.log('Please log ' + 'into Facebook.');
	}
}

function checkLoginState() {

	FB.getLoginStatus(function(response) {

    console.log("qweqweqwe");

		statusChangeCallback(response);
	});
}

function fbLogout(){
	FB.logout(function(response) {
		statusChangeCallback(response);
	});
}

window.fbAsyncInit = function() {
	FB.init({
		appId      : '',
		cookie     : true,  // enable cookies to allow the server to access the session
		xfbml      : true,  // parse social plugins on this page
		version    : 'v2.0' // use version 2.0
	});

	FB.getLoginStatus(function(response) {
		statusChangeCallback(response);
	});
};

(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/en_US/sdk.js";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));


function recolherInfo() {
	var html_loader = '<i class=\'html_loader fa fa-spinner fa-spin \'></i>';
    $('#estado_login_facebook').html(html_loader);

	console.log('Welcome!  Fetching your information.... ');
	FB.api('/me', function(response) {
		console.log('Successful login for: ' + response.name);
		console.log('Thanks for logging in, ' + response.name + '!');
		console.log(response);

		var fb_user_email        = response.email;
		var fb_user_first_name   = response.first_name;
		var fb_user_gender       = response.gender;
		var fb_user_id           = response.id;
		var fb_user_last_name    = response.last_name;
		var fb_user_link         = response.link;
		var fb_user_locale       = response.locale;
		var fb_user_name         = response.name;
		var fb_user_timezone     = response.timezone;
		var fb_user_updated_time = response.updated_time;
		var fb_user_verified     = response.verified;

		$.ajax({
        type: 'POST',
        url: 'login/loginWithFacebook',
        data: {
            fb_user_email: fb_user_email,
            fb_user_first_name: fb_user_first_name,
            fb_user_gender: fb_user_gender,
            fb_user_id: fb_user_id,
            fb_user_last_name: fb_user_last_name,
            fb_user_link: fb_user_link,
            fb_user_locale: fb_user_locale,
            fb_user_name: fb_user_name,
            fb_user_timezone: fb_user_timezone,
            fb_user_updated_time: fb_user_updated_time,
            fb_user_verified: fb_user_verified
        },
        success:function(data){
            console.log(data);

            var mensagem_login_facebook = '';

            if (data == "1") {
                mensagem_login_facebook = 'Login was successful!';
            }else{
                mensagem_login_facebook = 'The login failed. Please try again.';
            }

            $("#estado_login_facebook").html(mensagem_login_facebook);

        },
        error:function(data){
            //console.log(data);
        }
    });

	});
}
