<?php

/*

Plugin Name: Badge Feed Link Widget
Description: Enables you to add a RSS Badge to your sidebar.
Plugin URI: http://dennishoppe.de/wordpress-plugins/badge-feed-link-widget
Version: 1.1
Author: Dennis Hoppe
Author URI: http://DennisHoppe.de

*/


If (!Class_Exists('wp_widget_badge_feed_link')){
Class wp_widget_badge_feed_link Extends WP_Widget {
  var $base_url;
  var $arr_icon;
  var $arr_option;
  
  Function __construct(){
    // Read base_url
    $this->base_url = get_bloginfo('wpurl').'/'.Str_Replace("\\", '/', SubStr(RealPath(DirName(__FILE__)), Strlen(ABSPATH)));
    
    // Get ready to translate
    $this->Load_TextDomain();
    
    // Setup the Widget data
    parent::__construct (
      False,
      $this->t('RSS Badge Feed Linker'),
      Array('description' => $this->t('Enables you to add a RSS Badge to your sidebar.'))
    );

    // Hooks
    If (!Is_Admin()){
      Add_Action ('wp_print_styles', Array($this, 'Enqueue_Style'));
    }
    Else {
      Add_Action('sidebar_admin_setup', Array($this, 'Load_Widget_Page'));
    }

    // Read all .pngs
    $this->arr_icon = $this->Find_Icons();    
  }
  
  Function Load_TextDomain(){
    $locale = Apply_Filters( 'plugin_locale', get_locale(), __CLASS__ );
    Load_TextDomain (__CLASS__, DirName(__FILE__).'/language/' . $locale . '.mo');
  }
  
  Function t ($text, $context = ''){
    // Translates the string $text with context $context
    If ($context == '')
      return Translate ($text, __CLASS__);
    Else
      return Translate_With_GetText_Context ($text, $context, __CLASS__);
  }

  Function Default_Options(){
    // Default settings
    return Array(
      'title' => $this->t('RSS 2.0 Feed'),
      'icon' => Array()
    );
  }
  
  Function Load_Options($options){
    $options = (ARRAY) $options;
    
    // Delete empty values
    ForEach ($options AS $key => $value)
      If (!$value) Unset($options[$key]);
    
    // Check Gravatar size
    If (IsSet($options['gravatar_size'])){
      $options['gravatar_size'] = IntVal ($options['gravatar_size']);
      If ($options['gravatar_size'] == 0) Unset ($options['gravatar_size']);
    }
    
    // Load options
    $this->arr_option = Array_Merge ($this->Default_Options(), $options);
  }
  
  Function Get_Option($key, $default = False){
    If (IsSet($this->arr_option[$key]) && $this->arr_option[$key])
      return $this->arr_option[$key];
    Else
      return $default;
  }
  
  Function Set_Option($key, $value){
    $this->arr_option[$key] = $value;
  }
  
  Function Load_Widget_Page(){
    WP_Enqueue_Style('badge-feed-link-widget', $this->base_url . '/form.css');
    WP_Enqueue_Script('badge-feed-link-widget', $this->base_url . '/form.js');
  }
  
  Function Find_Icons(){
    // Read all .pngs
    $arr_icon = Array_Merge(
      (Array) Glob (DirName(__FILE__).'/rss-icons/*.png'),
      (Array) Glob (DirName(__FILE__).'/rss-icons/*.jpg'),
      (Array) Glob (DirName(__FILE__).'/rss-icons/*.jpeg'),
      (Array) Glob (DirName(__FILE__).'/rss-icons/*.gif')
    );
    return $arr_icon;
  }
  
  Function File_to_URL($path){
    $path = RealPath ($path);
    $root_dir = RealPath($_SERVER['DOCUMENT_ROOT']);
    
    $root_url = (is_ssl() ? 'https://' : 'http://') .
                $_SERVER['SERVER_NAME'] .
                (($_SERVER['SERVER_PORT'] != 80) ? ':'.$_SERVER['SERVER_PORT'] : '');
    
    If ( SubStr($path, 0, StrLen($root_dir)) == $root_dir )
      return $root_url . Str_Replace("\\", '/', SubStr($path, StrLen($root_dir)));
    Else
      return False;
  }
  
  Function Enqueue_Style(){
    If (Is_File(get_stylesheet_directory() . '/badge-feed-link-widget.css'))
      $style_sheet = get_stylesheet_directory_uri() . '/badge-feed-link-widget.css';
    ElseIf (is_child_theme() && Is_File(get_template_directory() . '/badge-feed-link-widget.css'))
      $style_sheet = get_template_directory_uri() . '/badge-feed-link-widget.css';
    ElseIf (Is_File(DirName(__FILE__) . '/badge-feed-link-widget.css'))
      $style_sheet = $this->base_url . '/badge-feed-link-widget.css';
    
    // run the filter for the template file
    $style_sheet = Apply_Filters('badge_feed_link_widget_style_sheet', $style_sheet);
    
    // Enqueue
    If ($style_sheet) WP_Enqueue_Style('badge-feed-link-widget', $style_sheet);    
  }

  Function Widget ($args, $settings){
    // Load options
    $this->load_options ($settings); Unset ($settings);
    
    // Get random icon
    $arr_icon = $this->get_option('icon');
    If (!$arr_icon) return False;
    Shuffle($arr_icon);
    $this->set_option('icon', $arr_icon[0]);

    // Look for the template file
    $template_name = 'badge-feed-link-widget.php';
    $template_file = Get_Query_Template(BaseName($template_name, '.php'));
    If (!Is_File($template_file) && Is_File(DirName(__FILE__) . '/' . $template_name))
      $template_file = DirName(__FILE__) . '/' . $template_name;
    
    // run the filter for the template file
    $template_file = Apply_Filters('badge_feed_link_widget_template', $template_file);
    
    // Print the widet
    If ($template_file && Is_File ($template_file)){
      Echo $args['before_widget'];
      Include $template_file;
      Echo $args['after_widget'];
    }

  }

  Function Form ($settings){
    // Load options
    $this->load_options ($settings); Unset ($settings);

    // Print Form
    Include DirName(__FILE__) . '/form.php';
  }
 
  Function update ($new_settings, $old_settings){
    return $new_settings;
  }

} /* End of Class */
Add_Action ('widgets_init', Create_Function ('','Register_Widget(\'wp_widget_badge_feed_link\');') );
Require_Once DirName(__FILE__).'/contribution.php';
} /* End of If-Class-Exists-Condition */
/* End of File */