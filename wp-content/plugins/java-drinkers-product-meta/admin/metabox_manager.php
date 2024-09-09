<?php 
add_action('add_meta_boxes', 'jdpm_add_metabox' );
function jdpm_add_metabox(){

    add_meta_box(
        'jdpm_product_metaa',
        'اطلاعات محصول',
        'jdpm_metabox_callback', //end
        'post',
        'normal',
        'core',
        // [
        //     'name' => 'mahdi',
        // ]
    );
}

add_action( 'save_post','jdpm_save_metabox',10 ,3);
function jdpm_save_metabox( $post_id , $post , $update ){
    $price      = absint( $_POST['jdpm_price']);
    $sale_price = absint( $_POST['jdpm_sale_price']);

    update_post_meta( $post_id, '_jdpm_price', $price );
    update_post_meta( $post_id, '_jdpm_sale_price', $sale_price );
}

function jdpm_metabox_callback($post, $args){
    $price = get_post_meta( $post->ID,'_jdpm_price', true);
    $sale_price = get_post_meta( $post->ID,'_jdpm_sale_price', true);
    include JDMB_VIEW . 'mtabox.php' ;

}