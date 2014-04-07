<?php

class BWGViewWidgetTags {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  private $model;

  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct($model) {
    $this->model = $model;
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////

  public function display() {
  }

  function widget($args, $instance) {
    extract($args);
    $title = $instance['title'];
    $type = (isset($instance['type']) ? $instance['type'] : "text");
    $show_name = (isset($instance['show_name']) ? $instance['show_name'] : 0);
    $count = (isset($instance['count']) ? $instance['count'] : 0);
    $width = (isset($instance['width']) ? $instance['width'] : 250);
    $height = (isset($instance['height']) ? $instance['height'] : 250);
    $background_transparent = (isset($instance['background_transparent']) ? $instance['background_transparent'] : 1);
    $background_color = (isset($instance['background_color']) ? $instance['background_color'] : "000000");
    $text_color = (isset($instance['text_color']) ? $instance['text_color'] : "ffffff");
    $theme_id = (isset($instance['theme_id']) ? $instance['theme_id'] : 0);
    // Before widget.
    echo $before_widget;
    // Title of widget.
    if ($title) {
      echo $before_title . $title . $after_title;
    }
    // Widget output.
    if ($id) {
      require_once(WD_BWG_DIR . '/frontend/controllers/BWGControllerWidget.php');
      $controller_class = 'BWGControllerWidgetFrontEnd';
      $controller = new $controller_class();
      global $bwg;
      $params = array (
        'type' => $type,
        'show_name' => $show_name,
        'count' => $count, 
        'width' => $width, 
        'height' => $height, 
        'background_transparent' => $background_transparent, 
        'background_color' => $background_color, 
        'text_color' => $text_color,
        'theme_id' => $theme_id);
      $controller->execute($params);
      $bwg++;
    }
    // After widget.
    echo $after_widget;
  }
  
  // Widget Control Panel.
  function form($instance, $id_title, $name_title, $id_type, $name_type, $id_show_name, $name_show_name, $id_count, $name_count, $id_width, $name_width, $id_height, $name_height, $id_background_transparent, $name_background_transparent, $id_background_color, $name_background_color, $id_text_color, $name_text_color, $id_theme_id, $name_theme_id) {
    $defaults = array(
      'title' => 'Photo Gallery Tags Cloud',
      'type' => 'text',      
      'show_name' => 0,      
      'count' => 0,
      'width' => 250,
      'height' => 250,
      'background_transparent' => 1,
      'background_color' => '000000',
      'text_color' => 'ffffff',
      'theme_id' => 0,
    );
    $instance = wp_parse_args((array) $instance, $defaults);
    $theme_rows = $this->model->get_theme_rows_data();
    ?>
    <script>
      function bwg_change_type_tag(event, obj) {
        var div = jQuery(obj).closest("div");
        if(jQuery(jQuery(div).find(".sel_image")[0]).prop("checked")) {
          jQuery(jQuery(div).find("#p_show_name")).css("display", "");
        }
        else {
          jQuery(jQuery(div).find("#p_show_name")).css("display", "none");
        }
      }
      
      function bwg_change_bg_transparency(event, obj) {
        var div = jQuery(obj).closest("div");
        if(jQuery(jQuery(div).find(".bg_transparent")[0]).prop("checked")) {
          jQuery(jQuery(div).find("#p_bg_color")).css("display", "none");
        }
        else {
          jQuery(jQuery(div).find("#p_bg_color")).css("display", "");
        }
      }
    </script>
    <script src="<?php echo WD_BWG_URL . '/js/jscolor/jscolor.js'?>" type="text/javascript" charset="utf-8"></script>
    <p>
      <label for="<?php echo $id_title; ?>">Title:</label>
      <input class="widefat" id="<?php echo $id_title; ?>" name="<?php echo $name_title; ?>'" type="text" value="<?php echo $instance['title']; ?>"/>
    </p>    
    <p>
      <input type="radio" name="<?php echo $name_type; ?>" id="<?php echo $id_type . "_1"; ?>" value="text" class="sel_text" <?php if ($instance['type'] == "text") echo 'checked="checked"'; ?> onclick="bwg_change_type_tag(event, this)" /><label for="<?php echo $id_type . "_1"; ?>">Text</label>
      <input type="radio" name="<?php echo $name_type; ?>" id="<?php echo $id_type . "_2"; ?>" value="image" class="sel_image" <?php if ($instance['type'] == "image") echo 'checked="checked"'; ?> onclick="bwg_change_type_tag(event, this)" /><label for="<?php echo $id_type . "_2"; ?>">Image</label>     
    </p>
    <p id="p_show_name" style="display:<?php echo ($instance['type'] == 'image') ? "" : "none" ?>;">
      <label>Show Tag Names:</label>
      <input type="radio" name="<?php echo $name_show_name; ?>" id="<?php echo $id_show_name . "_1"; ?>" value="1" <?php if ($instance['show_name']) echo 'checked="checked"'; ?> /><label for="<?php echo $id_show_name . "_1"; ?>">Yes</label>
      <input type="radio" name="<?php echo $name_show_name; ?>" id="<?php echo $id_show_name . "_0"; ?>" value="0" <?php if (!$instance['show_name']) echo 'checked="checked"'; ?>  /><label for="<?php echo $id_show_name . "_0"; ?>">No</label>     
    </p>
    <p>
      <label for="<?php echo $id_count; ?>">Number (0 for all):</label>
      <input class="widefat" style="width:25%;" id="<?php echo $id_count; ?>" name="<?php echo $name_count; ?>'" type="text" value="<?php echo $instance['count']; ?>"/>
    </p>
    <p>
      <label for="<?php echo $id_width; ?>">Dimensions:</label>
      <input class="widefat" style="width:25%;" id="<?php echo $id_width; ?>" name="<?php echo $name_width; ?>'" type="text" value="<?php echo $instance['width']; ?>"/> x 
      <input class="widefat" style="width:25%;" id="<?php echo $id_height; ?>" name="<?php echo $name_height; ?>'" type="text" value="<?php echo $instance['height']; ?>"/> px
    </p>
    <p>
      <label>Transparent Background:</label>
      <input type="radio" name="<?php echo $name_background_transparent; ?>" id="<?php echo $id_background_transparent . "_1"; ?>" value="1" <?php if ($instance['background_transparent']) echo 'checked="checked"'; ?> onclick="bwg_change_bg_transparency(event, this)" class="bg_transparent" /><label for="<?php echo $id_background_transparent . "_1"; ?>">Yes</label>
      <input type="radio" name="<?php echo $name_background_transparent; ?>" id="<?php echo $id_background_transparent . "_0"; ?>" value="0" <?php if (!$instance['background_transparent']) echo 'checked="checked"'; ?> onclick="bwg_change_bg_transparency(event, this)" /><label for="<?php echo $id_background_transparent . "_0"; ?>">No</label>     
    </p>
    <p id="p_bg_color" style="display:<?php echo (!$instance['background_transparent']) ? "" : "none" ?>;">
      <label for="<?php echo $id_background_color; ?>">Background Color:</label>
      <input class="color" style="width:25%;" id="<?php echo $id_background_color; ?>" name="<?php echo $name_background_color; ?>'" type="text" value="<?php echo $instance['background_color']; ?>"/>
    </p> 
    <p>
      <label for="<?php echo $id_text_color; ?>">Text Color:</label>
      <input class="color" style="width:25%;" id="<?php echo $id_text_color; ?>" name="<?php echo $name_text_color; ?>'" type="text" value="<?php echo $instance['text_color']; ?>"/>
    </p> 
    <p>
      <select name="<?php echo $name_theme_id; ?>" id="<?php echo $id_theme_id; ?>" class="widefat">
        <?php
        foreach ($theme_rows as $theme_row) {
          ?>
          <option value="<?php echo $theme_row->id; ?>" <?php echo (($instance['theme_id'] == $theme_row->id || $theme_row->default_theme == 1) ? 'selected="selected"' : ''); ?>><?php echo $theme_row->name; ?></option>
          <?php
        }
        ?>
      </select>
    </p> 
    <script>
      jscolor.init();
    </script>
    <?php
  }
  
  ////////////////////////////////////////////////////////////////////////////////////////
  // Getters & Setters                                                                  //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Private Methods                                                                    //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Listeners                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
}