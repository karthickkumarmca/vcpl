/**
 - config.js
 - App Configuration Values
 - Project: Corono Admin Portal
 **/
 let path = window.location.href;
 var appConfig = {
 	"user_role" : {
 		"super_admin" 	: 1,
 		"admin" 		: 2,
 	},
 	"response_code" : {
 		"ok" 			: 200,
 		"created" 		: 201,
 		"accepted"  	: 202,
 		"non-auth"  	: 203,
 		"bad_request" 	: 400,
 		"unauthorized"	: 401,
 		"not_found"     : 404,
 	},
 	"quarantine_order_type" : {
 		"bubble" : 1,
 		"facility" : 2,
 		"tourist" : 3
		// "facility" 		: 1,
		// "home"			: 2,
		// "facility_home" : 3,
		// "no_qtn" 		: 4,
		// "zone_order" 	: 5,
		// "home_order" 	: 6,
		// "business" 		: 7,
		// "isolation" 	: 8
	}
}

if (path.indexOf('localhost') != -1) {
	appConfig.portalUrl = 'http://localhost/mrs_chains/';
}

function loader(type = 0) {
	if (type == 0) {
		$('#preloader').addClass('startloader');
		$('.actualbtn').hide();
		$('.loadingbtn').show();

	} else {
		$('#preloader').removeClass('startloader');
		$('.loadingbtn').hide();
		$('.actualbtn').show();
	}
}
