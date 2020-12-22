<?php
 /**
 * Trigger this file on Plugin Deactivate
 *
 * @package CustomForm_Plugin
 */

 class mwCustomFormPluginDeactivate
 {
   public static function deactivate(){
     flush_rewrite_rules();
   }
 }
