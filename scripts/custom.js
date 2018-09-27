function loading(btn) {
	Btn = document.getElementById(btn);
	$('#'+btn).html("processing...");
	Btn.disabled = true;
}
function stop_loading(btn, btnValue) {
	Btn = document.getElementById(btn);

	Btn.innerHTML = btnValue;
	Btn.disabled = false;
}

function showMssgModal(msg_id){
	$("#msg_id_holder").val(msg_id);
	$("#inboxmodal").modal("show");
}
function showDecryKey(){
	dec_key = $(".dec_key").val();
	dataString = 'dec_key='+dec_key;
	$.ajax({
		type: "POST",
		url: "../ajax-pages/verifyDecKey.php",
		data: dataString,
		cache: false,
		success: function (res) {
			$(".decryptedMsg").html("<span style='color:red'>"+res+"</span>");
			if(res.length == 8){

				msg_id = $("#msg_id_holder").val();
				ddataString = 'msg_id=' + msg_id;

				$.ajax({
					type: "POST",
					url: "../ajax-pages/DecMsg.php",
					data: ddataString,
					cache: false,
					success: function (ress) {
								//SHOW DECRYPTED MESSAGE
								$(".decryptedMsg").html(ress);
							}

						});
			} else{
				$(".decryptedMsg").html("<div class='text-center text-warning'>Wrong Key</div>");
			}

		}

	});
}




function signUp(div){
	loading(div);
	username = $("#s_username").val();
	password = $("#s_password").val();
	msg_dspkey = $("#msg_dspkey").val();
	msg_keydec_key = $("#msg_keydec_key").val();
	category = $("#usercat:checked").val();
	if(category == 'mgt'){
		redirect = 'pages/mgt_portal.php';
	}else if(category == 'stf'){
		redirect = 'pages/stf_portal.php';
	}else if(category == 'std'){
		redirect = 'pages/std_portal.php';
	}

	dataString = 'username=' + username+'&password='+password+'&category='+category+'&msg_keydec_key='+msg_keydec_key+'&msg_dspkey='+msg_dspkey;

	$.ajax({
        type: "POST",
        url: "ajax-pages/signUp.php",
        data: dataString,
        cache: false,
        success: function (res) {
            if (res.length == 8) {
                window.location = redirect;
            }else {
               $(".mssg").html("<div class='alert alert-warning'><button type='button' class='close' data-dismiss='alert'>&times;</button>"+res+"</div>");
				stop_loading(div, "SIGNUP");
            }

        }

    });

}