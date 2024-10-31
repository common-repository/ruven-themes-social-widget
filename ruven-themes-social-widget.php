<?
/*
Plugin Name: Ruven Themes: Social Widget
Description: Extends the functionality of Ruven Themes by adding a social widget.
Version: 1.0
Author: Ruven
Author URI: http://ruventhemes.com/
Author Email: info@ruventhemes.com
*/







/* Initialize Social Widget
============================================================ */

if(!class_exists('rt_social_widget')):

  class rt_social_widget extends WP_Widget {

    // Social profile choices
    protected $profiles;

    // Default widget option values
    protected $defaults;



    /* Constructor
    ------------------------------------------------------------ */

  	function __construct()
  	{
      // Setup widget data
      $widget_ops = array(
        'classname' => 'ruven-themes-social-widget',
        'description' => __('Displays select social icons.', 'ruventhemes')
      );
      parent::__construct('ruven-themes-social-widget', __('Ruven Themes: Social Widget', 'ruventhemes'), $widget_ops);

      // Enqueue scripts and styles
  	  add_action('wp_enqueue_scripts', array(&$this, 'enqueue_scripts_and_styles'));

      // Load plugin text domain (for translation)
      add_action('plugins_loaded', array($this, 'load_textdomain'));

      // Social profile choices
      $this->profiles = array(
        'dribbble' => array(
          'label' => __('Dribbble URI', 'ruventhemes'),
          'icon'  => '<li class="social-dribbble"><a href="%s" %s>&#xe800;</a></li>',
        ),
        // 'email' => array(
        //   'label' => __('Email address', 'ruventhemes'),
        //   'icon'  => '<li class="social-email"><a href="%s" %s>&#xe80b;</a></li>',
        // ),
        'facebook' => array(
          'label' => __('Facebook URI', 'ruventhemes'),
          'icon'  => '<li class="social-facebook"><a href="%s" %s>&#xe802;</a></li>',
        ),
        'flickr' => array(
          'label' => __('Flickr URI', 'ruventhemes'),
          'icon'  => '<li class="social-flickr"><a href="%s" %s>&#xe80a;</a></li>',
        ),
        'github' => array(
          'label' => __('GitHub URI', 'ruventhemes'),
          'icon'  => '<li class="social-github"><a href="%s" %s>&#xe80c;</a></li>',
        ),
        'gplus' => array(
          'label' => __('Google+ URI', 'ruventhemes'),
          'icon'  => '<li class="social-gplus"><a href="%s" %s>&#xe801;</a></li>',
        ),
        'instagram' => array(
          'label' => __('Instagram URI', 'ruventhemes'),
          'icon'  => '<li class="social-instagram"><a href="%s" %s>&#xe809;</a></li>',
        ),
        'linkedin' => array(
          'label' => __('LinkedIn URI', 'ruventhemes'),
          'icon'  => '<li class="social-linkedin"><a href="%s" %s>&#xe806;</a></li>',
        ),
        'pinterest' => array(
          'label' => __('Pinterest URI', 'ruventhemes'),
          'icon'  => '<li class="social-pinterest"><a href="%s" %s>&#xe803;</a></li>',
        ),
        'rss' => array(
          'label' => __('RSS URI', 'ruventhemes'),
          'icon'  => '<li class="social-rss"><a href="%s" %s>&#xe805;</a></li>',
        ),
        'stumbleupon' => array(
          'label' => __('StumbleUpon URI', 'ruventhemes'),
          'icon'  => '<li class="social-stumbleupon"><a href="%s" %s>&#xe808;</a></li>',
        ),
        'tumblr' => array(
          'label' => __('Tumblr URI', 'ruventhemes'),
          'icon'  => '<li class="social-tumblr"><a href="%s" %s>&#xe807;</a></li>',
        ),
        'twitter' => array(
          'label' => __('Twitter URI', 'ruventhemes'),
          'icon'  => '<li class="social-twitter"><a href="%s" %s>&#xe80d;</a></li>',
        ),
        'vimeo' => array(
          'label' => __('Vimeo URI', 'ruventhemes'),
          'icon'  => '<li class="social-vimeo"><a href="%s" %s>&#xe80e;</a></li>',
        ),
        'youtube' => array(
          'label' => __('YouTube URI', 'ruventhemes'),
          'icon'  => '<li class="social-youtube"><a href="%s" %s>&#xe804;</a></li>',
        ),
      );

      // Default widget option values
      $this->defaults = array(
        'title'      => '',
        'new_window' => 1,
      );
      // Append profile IDs to default options
      foreach($this->profiles as $profile_id => $profile_data) {
        $this->defaults[$profile_id] = '';
      }
  	}



    /* Load plugin text domain (for translation)
    ------------------------------------------------------------ */

    function load_textdomain()
    {
      load_plugin_textdomain('ruventhemes', false, dirname(plugin_basename(__FILE__)).'/lang');
    }



    /* Enqueue Scripts and Styles
    ------------------------------------------------------------ */

  	function enqueue_scripts_and_styles()
  	{
    	wp_enqueue_style('rt-social-widget-font-icons', plugin_dir_url(__FILE__).'css/style.css');
  	}



    /* Form
    ------------------------------------------------------------ */

    function form($instance)
    {
      $instance = wp_parse_args((array)$instance, $this->defaults);
      ?>

      <p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
      </p>

      <p>
        <label>
          <input id="<?php echo $this->get_field_id('new_window'); ?>" type="checkbox" name="<?php echo $this->get_field_name('new_window'); ?>" value="1" <?php checked(1, $instance['new_window']); ?>/>
          <?php esc_html_e( 'Open links in new window?', 'ruventhemes' ); ?>
        </label>
      </p>

      <?php
      foreach((array)$this->profiles as $profile_id => $profile_data) {
        printf('<p>');
        printf('<label for="%s">%s:</label></p>', esc_attr($this->get_field_id($profile_id)), esc_attr($profile_data['label']));
        printf('<input type="text" id="%s" name="%s" value="%s" class="widefat" />', esc_attr($this->get_field_id($profile_id)), esc_attr($this->get_field_name($profile_id)), esc_url($instance[$profile_id]));
        printf('</p>');
      }

    }



    /* Update
    ------------------------------------------------------------ */

    function update($new_instance, $old_instance)
    {
      $instance = array();
      $instance['title']      = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
      $instance['new_window'] = $new_instance['new_window'];

      // Sanitize Profile URIs
      foreach($new_instance as $key => $value) {
        if(array_key_exists($key, (array)$this->profiles)) {
          $instance[$key] = esc_url($new_instance[$key]);
        }
      }

      return $instance;
    }



    /* Widget output
    ------------------------------------------------------------ */

    function widget($args, $instance)
    {
      extract($args);
      $instance = wp_parse_args((array)$instance, $this->defaults);

      echo $before_widget;

        if(!empty($instance['title'])) {
          $title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
          echo $before_title . $title . $after_title;
        }

        $output = '';
        $new_window = $instance['new_window'] ? 'target="_blank"' : '';
        $profiles = (array)$this->profiles;

        foreach($profiles as $profile_id => $profile_data) {
          if(empty($instance[$profile_id])) {
            continue;
          }
          $output.= sprintf($profile_data['icon'], esc_url($instance[$profile_id]), $new_window);
        }

        if($output) {
          echo "<ul>$output</ul>";
        }

      echo $after_widget;
    }



  }



endif;







/* Register Widget
============================================================ */

if(class_exists('rt_social_widget'))
{
  add_action('widgets_init', 'register_rt_social_widget');

  function register_rt_social_widget() {
    register_widget('rt_social_widget');
  }
}



