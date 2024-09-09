<?php
class JD_Rss extends WP_Widget{
    public function __construct()
    {
    parent::__construct(
        'jd_rss',
        'خبر های دانشجویار'
    );
    }

    public function form($instance){
        $title       = isset($instance['title'])       ? $instance['title'] : 'اخرین دوره های دانشجویار';
        $count       = isset($instance['count'])       ? $instance['count'] : 0;
        $show_author = isset($instance['show_author']) ? $instance['show_author'] : 0;
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')) ?>">نام</label>
            <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('title')) ?>" name="<?php echo esc_attr($this->get_field_name('title')) ?>" value="<?php echo esc_attr($title) ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('count')) ?>">تعداد</label>
            <input type="number" class="widefat" id="<?php echo esc_attr($this->get_field_id('count')) ?>" name="<?php echo esc_attr($this->get_field_name('count')) ?>" value="<?php echo esc_attr($count) ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('show_author')) ?>">نمایش نویسنده</label>
            <input type="checkbox" <?php checked($show_author) ?> id="<?php echo esc_attr($this->get_field_id('show_author')) ?>" name="<?php echo esc_attr($this->get_field_name('show_author')) ?>" value="1">
        </p>
        <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = [];
        $instance['title']       = isset($new_instance['title'])       ? strip_tags( $new_instance['title'] ) : 'اخرین دوره های دانشجویار';
        $instance['count']       = isset($new_instance['count'])       ? absint( $new_instance['count'] ) : 0;
        $instance['show_author'] = isset($new_instance['show_author']) ? absint( $new_instance['show_author'] ) : 0;

        return $instance;
    }

    public function widget($args, $instance)
    {
        $title = 'اخرین های دانشجویار';
        $count = isset($instance['count']) ? $instance['count'] : 0;
        $show_author = isset($instance['show_author']) ? $instance['show_author'] : 0;
        echo $args['before_widget'];
            echo $args['before_title'];
                echo $title;
            echo $args['after_title'];
            wp_widget_rss_output('https://www.daneshjooyar.com/feed', 
                [
                    'items' => $count,
                    'show_author' => $show_author,
                ]);
        echo $args['after_widget'];
    }
}

add_action('widgets_init','jd_widget_init');

function jd_widget_init(){
    register_widget('JD_Rss');
}