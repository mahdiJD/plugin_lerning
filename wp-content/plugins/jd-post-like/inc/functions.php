<?php
function jdpl_install(){
    global $wpdb;
    $table_post_like = $wpdb->prefix . 'jdpl_table';
    $table_collation = $wpdb->collate;
    $sql = "
        CREATE TABLE `{$table_post_like}` (
            `ID` bigint unsigned NOT NULL AUTO_INCREMENT,
            `post_id` bigint unsigned NOT NULL,
            `user_id` bigint unsigned NOT NULL,
            `liked` tinyint(1) NOT NULL DEFAULT '1',
            `ip` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
            `created_at` datetime NOT NULL,
            PRIMARY KEY (`ID`),
            KEY `post_id` (`post_id`),
            KEY `user_id` (`user_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=$table_collation
    ";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}

function jdpl_script(){
    wp_enqueue_script( 
        'jdpl_script', 
        JDPL_JS.'post-like.js',
        ['jquery'], 
        defined('WP_DEBUG') && WP_DEBUG ? time() : JDPL_VERSION,
        true
    );

    wp_localize_script( 'jdpl_script','jdpl',[
        'ajax_url' => admin_url('admin-ajax.php')
    ]);
}

function jdpl_style(){
    ?>
    <style>
        button.like-post {
            background: #FFF;
            border-radius: 4px;
            padding: 4px 15px;
            border: 1px solid #e5e5e5;
            color: #3a3a3a;
            transition: 0.3s ease-in;
            display: inline-flex;
            gap: 5px;
            align-items: center;
            flex-direction: row-reverse;
        }

        button.like-post svg{
            display: none;
        }
        button.like-post.loding svg{
            display: inline;
        }

        button.like-post.post-liked {
            background: #4caf50;
            color: #FFF;
        }

        span.like-message {
            color: green;
            visibility: hidden;
            opacity: 0;
            transition: 0.3s ease-in;
            display: inline;
        }

        span.like-message.error {
            color: red;
        }

        .like-message.show {
            visibility: visible;
            opacity: 1;
        }

    </style>
    <?php
}

function jdpl_get_post_like( $post_id ){
    global $wpdb;
    
    $like_count = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->jdpl_table} WHERE post_id = %d",
            $post_id
        )
    );
    return absint( $like_count );
}

function jdpl_is_liked( $post_id, $user_id){
    global $wpdb;
    $where = '';
    if ( $user_id ) {
        $where = $wpdb->prepare(
            " AND user_id = %d" , $user_id
        );
    }else{
        $where = $wpdb->prepare(
            " AND ip = %s " , $_SERVER['REMOTE_ADDR']
        );
    }
    $liked = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->jdpl_table} WHERE post_id = %d $where ",
            $post_id 
        )
    );
    return !($liked == 0);
}

function jdpl_button($content){
    $post_id = get_the_ID();
    $like_count = jdpl_get_post_like($post_id);
    $liked_class = // is_user_logged_in() &&
     jdpl_is_liked($post_id ,get_current_user_id()) ? 'post-liked' : '';
    $nonce = wp_create_nonce( 'post-like' . $post_id);
    $button = "
    <br> 
    <button class='like-post $liked_class' type='button' data-id='$post_id' data-nonce='$nonce'>
    <svg width='16' height='16' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><style>.spinner_S1WN{animation:spinner_MGfb .8s linear infinite;animation-delay:-.8s}.spinner_Km9P{animation-delay:-.65s}.spinner_JApP{animation-delay:-.5s}@keyframes spinner_MGfb{93.75%,100%{opacity:.2}}</style><circle class='spinner_S1WN' cx='4' cy='12' r='3'/><circle class='spinner_S1WN spinner_Km9P' cx='12' cy='12' r='3'/><circle class='spinner_S1WN spinner_JApP' cx='20' cy='12' r='3'/></svg>

    Like Post
    <span class='like-count'>($like_count)</span>
    </button>
    <span class='like-message'></span>
    ";
    return $content . $button;
}

function jdpl_ajax_callback(){
    // if (! get_current_user_id() ) {
    //     wp_send_json_error( [
    //         'message' => 'you need login'
    //     ] );
    // }

    $result = [];

    global $wpdb;
    $post_id = absint( $_POST['post_id']); //$_REQUEST
    $user_id = get_current_user_id();
    $like = $_POST['like'] == 'true' ? true : false;

    if( !isset( $_POST['_wpnonce'] ) || !wp_verify_nonce( $_POST['_wpnonce'], 'post-like' . $post_id) ){
        wp_send_json_error([
            'message' => 'error, nonce is invalid',
            'code' =>  '403',
        ]);
    }

    $liked = jdpl_like( $post_id, $user_id, $like);

    if ($liked) {
        wp_send_json_error([
            'message' => $liked->get_error_message(),
            'code' =>  $liked->get_error_code(),
        ]);
    }else wp_send_json_success( $liked );
}

function jdpl_like($post_id, $user_id, $like){
    global $wpdb;

    if ( ! get_post_type($post_id)) {
        return new WP_Error( 'invalid_post_id', 'post is invalid');
    }
    
    $exists_id = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT ID FROM {$wpdb->jdpl_table} WHERE post_id = %d AND user_id = %d",
            $post_id , $user_id
        )
    );

    if ($exists_id && $like) {
        return new WP_Error( 'liked_prev', 'you liked previously');
    }
    
    if ( !$exists_id && !$like) {
        return new WP_Error( 'disliked_prev', 'you did not liked previously');
    }

    if ( $like) {
        $like_data = [
            'post_id' => $post_id,
            'user_id' => $user_id,
            'ip'      => $_SERVER['REMOTE_ADDR'],
            'liked'    => 1,
            'created_at' => current_time('mysql')

        ];
        $liked = $wpdb->insert(
            $wpdb->jdpl_table,
            $like_data,
            ['%d' ,'%d' ,'%s' ,'%d' ,'%s']
        );
        if ( $liked ) {
            return[
                'message' => 'Liked',
                'liked'   => true,
                'count'   => jdpl_get_post_like( $post_id ),
            ];
        }else{
            return new WP_Error( 'error_like', 'error in liked');
        }
    }else{
        $disliked = $wpdb->delete(
            $wpdb->jdpl_table,
            [
                'ID' => $exists_id
            ]
        );
        if ( $disliked ) {
            return[
                'message' => 'dis liked',
                'liked'   => true,
                'count'   => jdpl_get_post_like( $post_id ),
            ];
        }else{
            return new WP_Error( 'error_dislike', 'error in disliked');
        }
    }

}
?>
