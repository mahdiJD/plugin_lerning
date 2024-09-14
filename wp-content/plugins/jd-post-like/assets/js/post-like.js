jQuery(document).ready(function($){
    $('.like-post').click(function(){
        if( $(this).hasClass('loading')){
            return;
        }
        let count = $(this).find('.like-count');
        let btn = $(this);
        let msg = $(this).next;
        $.ajax({
            url : jdpl.ajax_url,
            type : 'POST',
            data : {
                action : 'jdpl_like',
                post_id: $(this).data('id'),
                like: ! $(this).hasClass('post-liked'),
            },
            beforeSend: function(){
                $(btn).addClass('loding');
                $(btn).removeClass('show error').text('');
            },
            complete: function(){
                $(btn).removeClass('loding');
            },
            success: function(result){
                if( result.success ){
                    if( result.data.liked ){
                        $(btn).addClass('post-liked');
                        console.log('add');
                    }else{
                        $(btn).removeClass('post-liked');
                        console.log('rem');
                    }
                    $(count).text( `(${result.data.count})` );
                }else console.log('not success');
                $(msg)
                .text(result.data.message )
                .addClass(result.success ? 'success' : 'error')
                .addClass('show')
                .delay(3000)
                .queue( function( next ) {
                    $(this).removeClass('show success error')
                    next();
                })
                ;
            },
            error: function( xhr, status, http_error){

                let message = xhr.responseJSON !== undefined ? xhr.responseJSON.data.message : false;
                if (!message && http_error !== undefined ) {
                    message = http_error;
                }
                if (message !== undefined ) {
                    $(msg)
                    .text(message )
                    .addClass('error')
                    .addClass('show')
                    .delay(3000)
                    .queue( function( next ) {
                        $(this).removeClass('show success error')
                        next();
                    });
                }

                console.log(xhr);
                console.log(status);
                console.log(http_error);
            }
        });
    });
});
