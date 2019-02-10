/* global aht */
(function($,aht){
    var testBasicAuthUser     = Math.random().toString( 36 ).replace( /[^a-z]+/g, '' ),
        testBasicAuthPassword = Math.random().toString( 36 ).replace( /[^a-z]+/g, '' );

    $.ajax( {
        url: aht.root + aht.namespace + '/test-basic-authorization-header',
        method: 'POST',
        beforeSend: function( xhr ) {
            xhr.setRequestHeader( 'Authorization', 'Basic ' + btoa( testBasicAuthUser + ':' + testBasicAuthPassword ) );
        },
        error: function( jqXHR ) {
            if ( 404 === jqXHR.status ) {
                window.alert( aht.text.no_credentials );
            }
        }
    } ).done( function( response ) {
        if ( response.PHP_AUTH_USER === testBasicAuthUser && response.PHP_AUTH_PW === testBasicAuthPassword ) {
            // Save the success in SessionStorage or the like, so we don't do it on every page load?
            console.log( 'worked' );
        } else {
            window.alert( aht.text.no_credentials );
        }
    } );
})( jQuery, aht );
