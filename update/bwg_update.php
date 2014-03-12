<?php

function bwg_update_100() {
  global $wpdb;
  // Add image title option.
  $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_option ADD `image_title_show_hover` varchar(20) NOT NULL DEFAULT 'none' AFTER `image_enable_page`");
  // Add image title theme options.
  $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_theme ADD `thumb_title_shadow` varchar(64) NOT NULL DEFAULT '0px 0px 0px #888888' AFTER `thumb_transition`");
  $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_theme ADD `thumb_title_margin` varchar(64) NOT NULL DEFAULT '2px' AFTER `thumb_transition`");
  $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_theme ADD `thumb_title_font_weight` varchar(64) NOT NULL DEFAULT 'bold' AFTER `thumb_transition`");
  $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_theme ADD `thumb_title_font_size` int(4) NOT NULL DEFAULT 16 AFTER `thumb_transition`");
  $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_theme ADD `thumb_title_font_style` varchar(64) NOT NULL DEFAULT 'segoe ui' AFTER `thumb_transition`");
  $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_theme ADD `thumb_title_font_color` varchar(64) NOT NULL DEFAULT 'CCCCCC' AFTER `thumb_transition`");
  // Add thumb upload dimensions.
  $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_option ADD `upload_thumb_height` int(4) NOT NULL DEFAULT 300 AFTER `thumb_height`");
  $wpdb->query("ALTER TABLE " . $wpdb->prefix . "bwg_option ADD `upload_thumb_width` int(4) NOT NULL DEFAULT 300 AFTER `thumb_height`");
  return;
}
