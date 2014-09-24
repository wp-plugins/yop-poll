var write_console = false;
var yoppolltitlehint;
function cslw( msg ) {
    if ( write_console ) {
        if ( console && console.log ) {
            console.log( msg );
        }
    }
}

jQuery( document ).ready( function ( jQuery ) {
    var top = jQuery( '#postbox-container-1' ).position();
    if( top !== undefined ) {
        var pos = "-" + top.top + "px";
        jQuery( window ).scroll( function ( event ) {
            // what the y position of the scroll is
            var beginScroll = jQuery( this ).scrollTop();
            //get first column width
            var first = parseInt( jQuery( '#post-body-content' ).css( 'width' ) );

            // whether that's below the form
            if ( beginScroll >= top.top ) {
                //make div's position fixed
                jQuery( '#postbox-container-1' ).css( {
                    'position': 'fixed',
                    'margin-left': (first + 20) + "px",
                    'margin-top': pos
                } );
            }
            else {
                //restore div to initial position
                jQuery( '#postbox-container-1' ).css( {
                    'position': '',
                    'margin-left': '',
                    'margin-top': "0px"
                } );
            }
        } );

    }
    togglehandlediv = function () {
        jQuery( ".stuffbox .handlediv" ).unbind( "click" );
        jQuery( ".stuffbox .handlediv" ).click( function () {
            jQuery( this ).parent().toggleClass( "closed" );

        } );

    }

    yoppolltitlehint = function ( id ) {
        id = id || 'yop-poll-title';

        var title = jQuery( '.' + id ), titleprompt = jQuery( '.' + id + '-prompt-text' );

        title.each( function ( index ) {
            if ( jQuery( this ).val() == '' ) {
                jQuery( this ).parent().children( '.' + id + '-prompt-text' ).removeClass( 'screen-reader-text' );
            }
            else {
                jQuery( this ).parent().children( '.' + id + '-prompt-text' ).addClass( 'screen-reader-text' );
            }
        } );

        titleprompt.click( function () {
            jQuery( this ).addClass( 'screen-reader-text' );
            jQuery( this ).parent().children( '.' + id ).focus();
        } );

        title.blur(function () {
            if ( this.value == '' ) {
                jQuery( this ).parent().children( '.' + id + '-prompt-text' ).removeClass( 'screen-reader-text' );
            }
        } ).focus(function () {
                jQuery( this ).parent().children( '.' + id + '-prompt-text' ).addClass( 'screen-reader-text' );
            } ).keydown( function ( e ) {
                jQuery( this ).parent().children( '.' + id + '-prompt-text' ).addClass( 'screen-reader-text' );
                jQuery( this ).unbind( e );
            } );
    }

    yoppolltitlehint( 'yop-poll-title' );
    yoppolltitlehint( 'yop-poll-subtitle' );
    togglehandlediv();
    console.log(yop_poll_global_settings.date);
    jQuery( '.hasDatePicker' ).datetimepicker( {
        showSecond: true,
        timeFormat: 'hh:mm:ss',
        dateFormat: yop_poll_global_settings.date

    } );

} );

function yop_poll_update_bar_style( obj, property, value ) {
    if (
            'background-color' == property ||
                    'height' == property ||
                    'border-color' == property ||
                    'border-width' == property ||
                    'border-style' == property
            ) {
        if ( jQuery( obj ).length > 0 ) {
            if ( '' != value )
                jQuery( obj ).css( property, value );
        }
    }
}
;




