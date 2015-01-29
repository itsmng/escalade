<?php
include ("../../../inc/includes.php");

//change mimetype
header("Content-type: application/javascript");

//not executed in self-service interface & right verification
if ($_SESSION['glpiactiveprofile']['interface'] == "central") {
   
   $locale_actor = __('Actor');
   
   $remove_delete_group_btn = "true";
   if (isset($_SESSION['plugins']['escalade']['config']['remove_delete_group_btn'])
         && $_SESSION['plugins']['escalade']['config']['remove_delete_group_btn']) {
            $remove_delete_group_btn = "false";
         }
   
   $remove_delete_user_btn = "true";
   if (isset($_SESSION['plugins']['escalade']['config']['remove_delete_user_btn'])
         && $_SESSION['plugins']['escalade']['config']['remove_delete_user_btn']) {
            $remove_delete_user_btn = "false";
   }

	$JS = <<<JAVASCRIPT
	function removeOnButtons(str) {
   	$("table:contains('$locale_actor') tr:last-child td:last-child a[onclick*="+str+"]").remove();
	}
	
   $(document).ready(function() {
      // only in ticket form
      if (location.pathname.indexOf('ticket.form.php') > 0) {
         //remove "delete" group buttons
         if ({$remove_delete_group_btn}) {
            removeOnButtons("delete_group");
         }

         //remove "delete" user buttons
         if ({$remove_delete_user_btn}) {
            removeOnButtons("delete_user");
         }

      }
   });
JAVASCRIPT;
   echo $JS;
}