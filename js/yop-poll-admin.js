jQuery(document).ready(function(){
		console.log( jQuery('#postbox-container-1').position() );                                                           
		var top = jQuery('#postbox-container-1').position();
		jQuery(window).scroll(function (event) {
				// what the y position of the scroll is
				var y = jQuery(this).scrollTop();
				var first =  parseInt(jQuery('#post-body-content').css('width'));

				// whether that's below the form
				if (y >= top.top) {
					// if so, ad the fixed class
					jQuery('#postbox-container-1').css('position', 'fixed'); 
					jQuery('#postbox-container-1').css('margin-left', top.left );
					jQuery('#postbox-container-1').css('margin-top', '-70px' );
				} else {
					// otherwise remove it
					jQuery('#postbox-container-1').css('position', 'relative');
					jQuery('#postbox-container-1').css('margin-left', '' );
					jQuery('#postbox-container-1').css('margin-top', '0px' );
				}
		});
});      