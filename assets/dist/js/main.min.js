jQuery( document ).ready( function( $ ){

    //sync media
    if ( $('#sync-media').length > 0 ){
        $('#sync-media').on('click tap', start_media_sync );
    } 

});


function start_media_sync(){

    //set some updates
    progress_update( 'Starting media sync' );
    progress_update( 'Getting remote file list' );

    //fetch the remote file list:
    var data = { 'action': 'get-remote-file-list' }
    jQuery.post( ajaxurl, data, function( response ){
        try{
            
            response = JSON.parse(response);
            sync_media(response, 0);

        }catch( error ){
            console.log( error );
        }
    });
}


/**
 * Sync a single piece of media
 * @param Json list 
 * @param int cursor 
 */
function sync_media( list, cursor ){
    
    var data = { 
        'action': 'sync-media',
        'file_list': JSON.stringify( list ),
        'cursor': cursor
    }

    jQuery.post( ajaxurl, data, function( response ){

        try{
            response = JSON.parse(response);

            //give update:
            progress_update( response.message, response.error );

            //set bar:
            progress_bar( cursor, list.files.length );

            //iterate
            if( cursor < list.files.length ){
                cursor++;
                sync_media( list, cursor );
            
            }else{
                progress_update( 'Sync done' );
            }

        }catch( error ){
            console.log( error );
        }
    });
}


/**
 * Append a progress line to the progress list
 * 
 * @param String string 
 * @param Boolean error 
 */
function progress_update( string, error = false){
    var _class = ( error ? 'error' : 'success' );
    var update = '<li class="'+_class+'">'+string+'</li>';
    jQuery('#progress-list').append( update );
}


function progress_bar( cursor, max ){
    var percent = cursor / ( max - 1 ) * 100;
    jQuery('#progress').css({ 'width': percent + '%' });
}