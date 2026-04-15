<?php
include ("../../../inc/includes.php");

//change mimetype
header("Content-type: application/javascript");

//not executed in self-service interface & right verification
if ($_SESSION['glpiactiveprofile']['interface'] == "central") {

   $locale_actor = __('Actor');
   $esc_config = $_SESSION['plugins']['escalade']['config'];

   $remove_delete_requester_user_btn = "true";
   if (isset($esc_config['remove_delete_requester_user_btn'])
       && $esc_config['remove_delete_requester_user_btn']) {
      $remove_delete_requester_user_btn = "false";
   }

   $remove_delete_requester_group_btn = "true";
   if (isset($esc_config['remove_delete_requester_group_btn'])
       && $esc_config['remove_delete_requester_group_btn']) {
      $remove_delete_requester_group_btn = "false";
   }

   $remove_delete_watcher_user_btn = "true";
   if (isset($esc_config['remove_delete_watcher_user_btn'])
       && $esc_config['remove_delete_watcher_user_btn']) {
      $remove_delete_watcher_user_btn = "false";
   }

   $remove_delete_watcher_group_btn = "true";
   if (isset($esc_config['remove_delete_watcher_group_btn'])
       && $esc_config['remove_delete_watcher_group_btn']) {
      $remove_delete_watcher_group_btn = "false";
   }

   $remove_delete_assign_user_btn = "true";
   if (isset($esc_config['remove_delete_assign_user_btn'])
       && $esc_config['remove_delete_assign_user_btn']) {
      $remove_delete_assign_user_btn = "false";
   }

   $remove_delete_assign_group_btn = "true";
   if (isset($esc_config['remove_delete_assign_group_btn'])
       && $esc_config['remove_delete_assign_group_btn']) {
      $remove_delete_assign_group_btn = "false";
   }

   $remove_delete_assign_supplier_btn = "true";
   if (isset($esc_config['remove_delete_assign_supplier_btn'])
       && $esc_config['remove_delete_assign_supplier_btn']) {
      $remove_delete_assign_supplier_btn = "false";
   }

   $JS = <<<JAVASCRIPT
   var removeDeleteButtons = function(str, num, role, entryType) {
      $("table:contains('$locale_actor') td:last-child a[onclick*="+str+"], \
         .tab_actors .actor-bloc:eq("+num+") a[onclick*="+str+"]")
            .remove();

      var panel = pluginEscaladeGetActorPanel(role);
      if (pluginEscaladeIsModernActorPanel(panel)) {
         panel
            .find("[data-actor-entry][data-entry-type='" + entryType + "']")
            .find("[data-role='remove-actor']")
            .remove();
      }
   }

   var removeAllDeleteButtons = function() {

      // Get type from path to handle multiple ticket type
      var type = 'ticket';
      if (location.pathname.indexOf('problem.form.php') > 0) {
         type = 'problem';
      }
      if (location.pathname.indexOf('change.form.php') > 0) {
         type = 'change';
      }

      // ## REQUESTER
      //remove "delete" group buttons
      if ({$remove_delete_requester_group_btn}) {
         removeDeleteButtons("group_"+type, 0, 'requester', 'group');
      }
      //remove "delete" user buttons
      if ({$remove_delete_requester_user_btn}) {
         removeDeleteButtons(type+"_user", 0, 'requester', 'user');
      }

      // ## WATCHER
      //remove "delete" group buttons
      if ({$remove_delete_watcher_group_btn}) {
         removeDeleteButtons("group_"+type, 1, 'observer', 'group');
      }
      //remove "delete" user buttons
      if ({$remove_delete_watcher_user_btn}) {
         removeDeleteButtons(type+"_user", 1, 'observer', 'user');
      }

      // ## ASSIGN
      //remove "delete" group buttons
      if ({$remove_delete_assign_group_btn}) {
         removeDeleteButtons("group_"+type, 2, 'assign', 'group');
      }
      //remove "delete" user buttons
      if ({$remove_delete_assign_user_btn}) {
         removeDeleteButtons(type+"_user", 2, 'assign', 'user');
      }
      //remove "delete" supplier buttons
      if ({$remove_delete_assign_supplier_btn}) {
         removeDeleteButtons("supplier_"+type, 2, 'assign', 'supplier');
      }
   }

   $(document).ready(function() {

   // only in ticket / problem / change form
      if (location.pathname.indexOf('ticket.form.php') < 0 &&
         location.pathname.indexOf('problem.form.php') < 0 &&
         location.pathname.indexOf('change.form.php') < 0
      ) {
      return;
     }

      removeAllDeleteButtons();

      var targetNode = document.body;

      var observer = new MutationObserver(function (mutations) {
         mutations.forEach(function (mutation) {
            if (mutation.addedNodes.length > 0) {
               removeAllDeleteButtons();
           }
         });
     });

      observer.observe(targetNode, {
         childList: true,
         subtree: true
      });
   });
JAVASCRIPT;
   echo $JS;
}
