<?php
 /**
 * Trigger this file on Plugin uninstall
 *
 * @package CustomForm_Plugin
 */

 if (!defined('WP_UNINSTALL_PLUGIN')) {
     die;
 }

global $wpdb;

//$wpdb->query( "DELETE FROM {$wpdb->prefix}mwcustomform);;