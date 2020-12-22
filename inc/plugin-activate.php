<?php
 /**
 * Trigger this file on Plugin Activate
 *
 * @package CustomForm_Plugin
 */

 class mwCustomFormPluginActivate
 {
   public static function activate(){
     flush_rewrite_rules();
   }
 }
