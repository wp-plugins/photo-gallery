<?php

class BWGControllerTags_bwg {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct() {
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function execute() {
    $task = ((isset($_POST['task'])) ? esc_html(stripslashes($_POST['task'])) : '');
    $id = ((isset($_POST['current_id'])) ? esc_html(stripslashes($_POST['current_id'])) : 0);
    if (method_exists($this, $task)) {
      $this->$task($id);
    }
    else {
      $this->display();
    }
  }

  public function display() {
    require_once WD_BWG_DIR . "/admin/models/BWGModelTags_bwg.php";
    $model = new BWGModelTags_bwg();

    require_once WD_BWG_DIR . "/admin/views/BWGViewTags_bwg.php";
    $view = new BWGViewTags_bwg($model);
    $view->display();
  }

  public function save() {
    $this->save_tag();
    $this->display();
  } 
  
  public function bwg_get_unique_slug($slug, $id) {
    global $wpdb;
    $slug = sanitize_title($slug);
    if ($id != 0) {
      $query = $wpdb->prepare("SELECT slug FROM " . $wpdb->prefix . "terms WHERE slug = %s AND term_id != %d", $slug, $id);
    }
    else {
      $query = $wpdb->prepare("SELECT slug FROM " . $wpdb->prefix . "terms WHERE slug = %s", $slug);
    }
    if ($wpdb->get_var($query)) {
      $num = 2;
      do {
        $alt_slug = $slug . "-$num";
        $num++;
        $slug_check = $wpdb->get_var($wpdb->prepare("SELECT slug FROM " . $wpdb->prefix . "terms WHERE slug = %s", $alt_slug));
      } while ($slug_check);
      $slug = $alt_slug;
    }
    return $slug;
  }
  
  public function bwg_get_unique_name($name, $id) {
    global $wpdb;
    if ($id != 0) {
      $query = $wpdb->prepare("SELECT name FROM " . $wpdb->prefix . "terms WHERE name = %s AND term_id != %d", $name, $id);
    }
    else {
      $query = $wpdb->prepare("SELECT name FROM " . $wpdb->prefix . "terms WHERE name = %s", $name);
    }
    if ($wpdb->get_var($query)) {
      $num = 2;
      do {
        $alt_name = $name . "-$num";
        $num++;
        $slug_check = $wpdb->get_var($wpdb->prepare("SELECT name FROM " . $wpdb->prefix . "terms WHERE name = %s", $alt_name));
      } while ($slug_check);
      $name = $alt_name;
    }
    return $name;
  }
  
  public function save_tag() {
    $name = ((isset($_POST['tagname'])) ? esc_html(stripslashes($_POST['tagname'])) : '');
    $name = $this->bwg_get_unique_name($name, 0);
    $slug = ((isset($_POST['slug']) && (esc_html($_POST['slug']) != '')) ? esc_html(stripslashes($_POST['slug'])) : $name);
    $slug = $this->bwg_get_unique_slug($slug, 0);
	  $slug = sanitize_title($slug);
    if ($name) {
      $save = wp_insert_term($name, 'bwg_tag', array(
        'description'=> '',
        'slug' => $slug,
        'parent' => 0
        )
      );
      if (isset($save->errors)) {
        echo WDWLibrary::message('A term with the name provided already exists.', 'error');
      }
      else {
        echo WDWLibrary::message('Item Succesfully Saved.', 'updated');
      }
    }
    else {
      echo WDWLibrary::message('Name field is required.', 'error');
    }
  }
  
  function edit_tag() {
    global $wpdb;
    $flag = FALSE; 
    $id = ((isset($_REQUEST['tag_id'])) ? esc_html(stripslashes($_REQUEST['tag_id'])) : '');
    $query = "SELECT count FROM " . $wpdb->prefix . "term_taxonomy WHERE term_id=" . $id;
    $count = $wpdb->get_var($query);
    $name = ((isset($_REQUEST['tagname'])) ? esc_html(stripslashes($_REQUEST['tagname'])) : '');
    $name = $this->bwg_get_unique_name($name, $id);
    if ($name) {
      $slug = ((isset($_REQUEST['slug']) && (esc_html($_REQUEST['slug']) != '')) ? esc_html(stripslashes($_REQUEST['slug'])) : $name);
      $slug = $this->bwg_get_unique_slug($slug, $id);
      $save = wp_update_term($id, 'bwg_tag', array(
        'name' => $name,
        'slug' => $slug
      ));
      if (isset($save->errors)) {
        echo 'The slug must be unique.';
      }
      else {
        $flag = TRUE;
      }
    }
    if ($flag) {
      echo $name . '.' . $slug . '.' . $count;
    }
    die();
  }

  public function edit_tags() {
    $flag = FALSE;
    $rows = get_terms('bwg_tag', array('orderby' => 'count', 'hide_empty' => 0));
    $name = ((isset($_POST['tagname'])) ? esc_html(stripslashes($_POST['tagname'])) : '');
    $name = $this->bwg_get_unique_name($name, 0);
    $slug = ((isset($_POST['slug']) && (esc_html($_POST['slug']) != '')) ? esc_html(stripslashes($_POST['slug'])) : $name);
    $slug = $this->bwg_get_unique_slug($slug, 0);
    if ($name) {
      $save = wp_insert_term($name, 'bwg_tag', array(
        'description'=> '',
        'slug' => $slug,
        'parent' => 0
        )
      );
      if (isset($save->errors)) {
        echo WDWLibrary::message('A term with the name provided already exists.', 'error');
      }
      else {
        echo WDWLibrary::message('Item Succesfully Saved.', 'updated');
      }
    }
    foreach ($rows as $row) {
      $id = $row->term_id;
      $name = ((isset($_POST['tagname' . $row->term_id])) ? esc_html(stripslashes($_POST['tagname' . $id])) : '');
      $name = $this->bwg_get_unique_name($name,  $id);
      if ($name) {
        $slug = ((isset($_POST['slug' . $row->term_id]) && (esc_html($_POST['slug' . $id]) != '')) ? esc_html(stripslashes($_POST['slug' . $id])) : $name);
        $slug = $this->bwg_get_unique_slug($slug, $id);
        $save = wp_update_term($id, 'bwg_tag', array(
          'name' => $name,
          'slug' => $slug
        ));
        if (isset($save->errors)) {
          echo WDWLibrary::message('The slug must be unique.', 'error');
        }
        else {
          $flag = TRUE;
        }
      }
    }
    if ($flag) {
      echo WDWLibrary::message('Item(s) Succesfully Saved.', 'updated');
    }
    $this->display();
  }

  public function delete($id) {
    global $wpdb;
    wp_delete_term($id, 'bwg_tag');
    $query = $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'bwg_image_tag WHERE tag_id="%d"', $id);
	$flag = $wpdb->query($query);
    if ($flag !== FALSE) {
      echo WDWLibrary::message('Item Succesfully Deleted.', 'updated');
    }
    else {
      echo WDWLibrary::message('Error. Please install plugin again.', 'error');
    }
    $this->display();
  }
  
  public function delete_all() {
    global $wpdb;
    $flag = FALSE;
    $tag_ids_col = $wpdb->get_col("SELECT term_id FROM " . $wpdb->prefix . "terms");
    foreach ($tag_ids_col as $tag_id) {
      if (isset($_POST['check_' . $tag_id])) {
        wp_delete_term($tag_id, 'bwg_tag');
        $wpdb->query($wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'bwg_image_tag WHERE tag_id="%d"', $tag_id));
        $flag = TRUE;
      }
    }
    if ($flag) {
      echo WDWLibrary::message('Items Succesfully Deleted.', 'updated');
    }
    else {
      echo WDWLibrary::message('You must select at least one item.', 'error');
    }  
    $this->display();
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