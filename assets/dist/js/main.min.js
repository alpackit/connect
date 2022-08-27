jQuery( document ).ready( function( $ ){

    //sync media
    if( $('#sync-packits').length > 0 ){
        $('#sync-packits').on('click tap', start_packit_sync );
    } 

    $('#reveal-unsynced-data').on( 'click tap', function(){
        $('.alpackit-update-overview').show();
    });

});

var alpackit_workflow_id = 0;
var alpackit_sync_poll = 0;
var alpackit_sync_last_update = '';

function start_packit_sync(){

    jQuery('#packit-update-selection').hide();
    jQuery('#packit-update-progress').show();

    //set some updates
    progress_update( 'Starting packit sync' );

    get_remote_file_list();
    show_alpackit_loader();

    alpackit_sync_poll = setInterval( get_remote_file_list, 2000 );
}

function get_remote_file_list(){

    //fetch the remote file list:
    var packits = [];
    jQuery('.packit_id').each( function(){
        packits.push( jQuery( this ).val() );
    });
    
    var data = { 
        'action': 'get-remote-file-list', 
        'packits': packits,
        'workflow_id': alpackit_workflow_id
    }
    
    jQuery.post(ajaxurl, data, function (response) {
        try {

            response = JSON.parse(response);

            //set workflow id: 
            if( typeof( response.workflow_id ) !== 'undefined' && alpackit_workflow_id == 0 ){
                alpackit_workflow_id = response.workflow_id;
                progress_update('Getting remote file list');
            
            }else if( response.current_step !== response.step_total ){

                if (alpackit_sync_last_update !== response.message) {
                    progress_update(response.message);
                    alpackit_sync_last_update = response.message;
                }
            
            }else if( response.current_step === response.step_total ){

                var list = JSON.parse( response.message );

                clearInterval( alpackit_sync_poll );
                //start going one-by one:
                sync_packit( list, 0 );
            }


        } catch (error) {
            console.log(error);
        }
    });

}


/**
 * Sync a single packit
 * @param Json list 
 * @param int cursor 
 */
function sync_packit( list, cursor ){
    
    var data = { 
        'action': 'sync-packit',
        'packit_list': JSON.stringify( list ),
        'cursor': cursor
    }

    jQuery.post( ajaxurl, data, function( response ){

        try{
            response = JSON.parse(response);

            //give update:
            var error = false;
            progress_update( response.message, error );

            //set bar:
            //progress_bar( cursor, list.length );

            //iterate
            if( cursor < list.length ){
                cursor++;
                sync_packit( list, cursor );
            
            }else{
                progress_update( 'Sync done' );
                hide_alpackit_loader();

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

function show_alpackit_loader(){
    jQuery('#alpackit-loader').show();
}

function hide_alpackit_loader(){
    jQuery('#alpackit-loader').hide();
}