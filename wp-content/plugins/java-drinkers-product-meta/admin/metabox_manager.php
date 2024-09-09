<?php 
add_action('add_meta_boxes', 'jdpm_add_metabox' );
function jdpm_add_metabox(){

    // print_r(get_current_screen());
    add_meta_box(
        'jdpm_product_metaa',
        'اطلاعات محصول',
        'jdpm_metabox_callback', //end
        ['post' , 'page', 'attachment'],
        'normal',
        'core',
    );

    add_meta_box(
        'jdpm_comment_meta',
        'اطلاعات دیدگاه',
        'jdpm_comment_callback',
        'comment',
        'normal',
    );
}

function jdpm_comment_callback($comment){
    $score = get_comment_meta( $comment->comment_ID, 'jdpm_score', true);
    $special = get_comment_meta( $comment->comment_ID, 'jdpm_special', true);
    include JDMB_VIEW . 'comment_meta.php' ;
}

add_action( 'edit_comment','jdpm_save_comment_metabox',10 ,2);
function jdpm_save_comment_metabox($comment_id, $comment_data){
    $score = absint( $_POST['jdpm_score']);

    if(isset($_POST['jdpm_special'])){
        update_comment_meta($comment_id, 'jdpm_special', 1);
    }else{
        delete_comment_meta($comment_id, 'jdpm_special', 1);
    }

    update_comment_meta( $comment_id , 'jdpm_score', $score);
}

add_action( 'save_post','jdpm_save_metabox',10 ,3);
add_action( 'edit_attachment','jdpm_save_metabox',10 ,3);
function jdpm_save_metabox( $post_id , $post  , $update ){

    $post_type = get_post_type();

    if ( ! in_array($post_type, ['post','attachment']) ||
         ! isset( $_POST['jdpm_price']) ||
         wp_doing_ajax()
         ) {
        return;
    }

    $price      = absint( $_POST['jdpm_price']);
    $sale_price = absint( $_POST['jdpm_sale_price']);

    $jdpm_meta = [
        'price'  => 0,
        'sale_price'  => 0,
    ];

    update_post_meta( $post_id, '_arr_jdpm_price', $jdpm_meta );

    update_post_meta( $post_id, '_jdpm_price', $price );
    update_post_meta( $post_id, '_jdpm_sale_price', $sale_price );
}

function jdpm_metabox_callback($post, $args){
    $price = get_post_meta( $post->ID,'_jdpm_price', true);
    $sale_price = get_post_meta( $post->ID,'_jdpm_sale_price', true);

    $jdpm_meta = get_post_meta($post->ID, '_jdpm_meta', true);
    if( ! $jdpm_meta ){
        $jdpm_meta = [
            'price'  => 0,
            'sale_price'  => 0,
        ];
    }
    $arr_price = $jdpm_meta['price'];
    $arr_sale_price = $jdpm_meta['sale_price'];

    include JDMB_VIEW . 'mtabox.php' ;

}