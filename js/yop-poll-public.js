function yop_poll_do_vote( poll_id ) {
	var yop_poll_public_config	= window['yop_poll_public_config_' +poll_id ];
	jQuery('#yop_poll_vote-button-'+ poll_id).hide();
	var vote_button_loading_image	= document.createElement('img');
	vote_button_loading_image.src	= yop_poll_public_config.loading_image_src;
	vote_button_loading_image.alt	= yop_poll_public_config.loading_image_alt;
	vote_button_loading_image.id	= 'yop_poll_vote_button_loading_img-'+ poll_id;
	jQuery('#yop_poll_vote-button-'+ poll_id).after( vote_button_loading_image );
	jQuery('#yop_poll_vote_button_loading_img-'+ poll_id).css( 'border', 'none' );
	jQuery.ajax({
		type: 'POST',
		url: yop_poll_public_config.ajax.url,
		data: 'action='+yop_poll_public_config.ajax.vote_action+'&poll_id=' + poll_id + '&' + jQuery('#yop-poll-form-'+ poll_id).serialize(),
		cache: false,
		error: function() {
			alert('An error has occured!');
			jQuery('#yop_poll_vote_button_loading_img-'+ poll_id).remove();
			jQuery('#yop_poll_vote-button-'+ poll_id).show();
		},
		success:
		function( data ){
			data		= yop_poll_extractResponse( data );
			response	= JSON.parse(data);
			if ( '' != response.error )
				jQuery('#yop-poll-container-error-'+ poll_id).html(response.error);
			else {
				if ( '' != response.message ) {
					jQuery('#yop-poll-container-'+ poll_id).replaceWith(response.message);
					jQuery('#yop-poll-container-error-'+ poll_id).html('');
					eval(
						"if(typeof window.strip_results_"+poll_id+" == 'function')  strip_results_"+poll_id+"();" +
						"if(typeof window.tabulate_answers_"+poll_id+" == 'function')  tabulate_answers_"+poll_id+"();" +
						"if(typeof window.tabulate_results_"+poll_id+" == 'function')	tabulate_results_"+poll_id+"(); "
					);
				}
				else
					jQuery('#yop-poll-container-error-'+ poll_id).html('An Error Has Occured!');
			}
			jQuery('#yop_poll_vote_button_loading_img-'+ poll_id).remove();
			jQuery('#yop_poll_vote-button-'+ poll_id).show();
		}
	});
}

function yop_poll_view_results( poll_id ) {
	var yop_poll_public_config	= window['yop_poll_public_config_' +poll_id ];
	jQuery('#yop_poll_result_link'+ poll_id).hide();
	var result_link_loading_image	= document.createElement('img');
	result_link_loading_image.src	= yop_poll_public_config.loading_image_src;
	result_link_loading_image.alt	= yop_poll_public_config.loading_image_alt;
	result_link_loading_image.id	= 'yop_poll_result_link_loading_img-'+ poll_id;
	jQuery('#yop_poll_result_link'+ poll_id).after( result_link_loading_image );
	jQuery('#yop_poll_result_link_loading_img-'+ poll_id).css( 'border', 'none' );
	jQuery.ajax({
		type: 'POST',
		url: yop_poll_public_config.ajax.url,
		data: 'action='+yop_poll_public_config.ajax.view_results_action+'&poll_id=' + poll_id,
		cache: false,
		error: function() {
			alert('An error has occured!');
			jQuery('#yop_poll_result_link_loading_img-'+ poll_id).remove();
			jQuery('#yop_poll_result_link'+ poll_id).show();
		},
		success:
		function( data ){
			data		= yop_poll_extractResponse( data );
			response	= JSON.parse(data);
			if ( '' != response.error )
				jQuery('#yop-poll-container-error-'+ poll_id).html(response.error);
			else {
				if ( '' != response.message ) {
					jQuery('#yop-poll-container-'+ poll_id).replaceWith(response.message);
					jQuery('#yop-poll-container-error-'+ poll_id).html('');
					eval(
						"if(typeof window.strip_results_"+poll_id+" == 'function')  strip_results_"+poll_id+"();" +
						"if(typeof window.tabulate_answers_"+poll_id+" == 'function')  tabulate_answers_"+poll_id+"();" +
						"if(typeof window.tabulate_results_"+poll_id+" == 'function')	tabulate_results_"+poll_id+"(); "
					);
				}
				else
					jQuery('#yop-poll-container-error-'+ poll_id).replaceWith('An Error Has Occured!');
			}
			jQuery('#yop_poll_result_link_loading_img-'+ poll_id).remove();
			jQuery('#yop_poll_result_link'+ poll_id).show();
		}
	});
}

function yop_poll_back_to_vote( poll_id ) {
	var yop_poll_public_config	= window['yop_poll_public_config_' +poll_id ];
	jQuery('#yop_poll_back_to_vote_link'+ poll_id).hide();
	var back_to_vote_loading_image	= document.createElement('img');
	back_to_vote_loading_image.src	= yop_poll_public_config.loading_image_src;
	back_to_vote_loading_image.alt	= yop_poll_public_config.loading_image_alt;
	back_to_vote_loading_image.id	= 'yop_poll_back_to_vote_loading_img-'+ poll_id;
	jQuery('#yop_poll_back_to_vote_link'+ poll_id).after( back_to_vote_loading_image );
	jQuery('#yop_poll_back_to_vote_loading_img-'+ poll_id).css( 'border', 'none' );
	jQuery.ajax({
		type: 'POST',
		url: yop_poll_public_config.ajax.url,
		data: 'action='+yop_poll_public_config.ajax.back_to_vote_action+'&poll_id=' + poll_id,
		cache: false,
		error: function() {
			alert('An error has occured!');
			jQuery('#yop_poll_back_to_vote_loading_img-'+ poll_id).remove();
			jQuery('#yop_poll_result_link'+ poll_id).show();
		},
		success:
		function( data ){
			data		= yop_poll_extractResponse( data );
			response	= JSON.parse(data);
			if ( '' != response.error )
				jQuery('#yop-poll-container-error-'+ poll_id).html(response.error);
			else {
				if ( '' != response.message ) {
					jQuery('#yop-poll-container-'+ poll_id).replaceWith(response.message);
					jQuery('#yop-poll-container-error-'+ poll_id).html('');
					eval(
						"if(typeof window.strip_results_"+poll_id+" == 'function')  strip_results_"+poll_id+"();" +
						"if(typeof window.tabulate_answers_"+poll_id+" == 'function')  tabulate_answers_"+poll_id+"();" +
						"if(typeof window.tabulate_results_"+poll_id+" == 'function')	tabulate_results_"+poll_id+"(); "
					);
				}
				else
					jQuery('#yop-poll-container-error-'+ poll_id).html('An Error Has Occured!');
			}
			jQuery('#yop_poll_back_to_vote_loading_img-'+ poll_id).remove();
			jQuery('#yop_poll_result_link'+ poll_id).show();
		}
	});
}

function yop_poll_extractResponse( str ) {
	var patt	= /\[ajax-response\](.*)\[\/ajax-response\]/m;
	resp 		= str.match( patt )
	return resp[1];
}

function yop_poll_reloadCaptcha( poll_id ) {
	var yop_poll_public_config	= window['yop_poll_public_config_' +poll_id ];
	jQuery('#yop_poll_captcha_image_' + poll_id).attr( 'src', yop_poll_public_config.ajax.url + '?action=' + yop_poll_public_config.ajax.captcha_action + '&poll_id=' + poll_id + '&sid=' + Math.random() );
}
