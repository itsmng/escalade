<?php
include ("../../../inc/includes.php");

//change mimetype
header("Content-type: application/javascript");

//not executed in self-service interface & right verification
if ($_SESSION['glpiactiveprofile']['interface'] == "central"
   && (Session::haveRight("ticket", CREATE)
       || Session::haveRight("ticket", UPDATE))) {

   $locale_actor = __('Actor');

   $JS = <<<JAVASCRIPT

   var plugin_url = CFG_GLPI.root_doc+"/"+GLPI_PLUGINS_PATH.escalade;

   var ticketEscalation = function() {
      var tickets_id = getUrlParameter('id');
      var assignPanel = pluginEscaladeGetActorPanel('assign');

      //only in edit form
      if (tickets_id == undefined) {
         return;
      }

      if (!assignPanel.length) {
         return;
      }

      // if escalade block already inserted
      if (assignPanel.find(".plugin-escalade-ticket-history").get(0)) {
         return;
      }

      var historyDiv = $('<div class="plugin-escalade-ticket-history"></div>');

      if (pluginEscaladeIsModernActorPanel(assignPanel)) {
         var groupRows = assignPanel.find("[data-actor-entry][data-entry-type='group']");
         if (groupRows.length) {
            groupRows.last().addClass('escalade_active');
            historyDiv.insertAfter(groupRows.last());
         } else {
            historyDiv.appendTo(assignPanel.find("[data-role='actor-list']").first());
         }
      } else {
         var target = assignPanel
            .find("a[href*=group]")
            .addClass('escalade_active')
            .last();

         if (!target.length) {
            return;
         }

         historyDiv.appendTo(target);
      }

      $.ajax({
         url: plugin_url+'/ajax/history.php',
         type: 'POST',
         data: {'tickets_id': tickets_id},
         global: false,
         success: function(data) {
            historyDiv.html(data);
         }
      });
   }

   $(document).ready(function() {
      // only in ticket form
      if (location.pathname.indexOf('ticket.form.php') != 0) {
         ticketEscalation();

         // V1: jQuery UI tabs
         $("#tabspanel + div.ui-tabs").on("tabsload", function() {
            setTimeout(function() {
               ticketEscalation();
            }, 300);
         });

         // V2: re-run after AJAX-loaded tab content
         $(document).ajaxComplete(function() {
            setTimeout(function() {
               ticketEscalation();
            }, 300);
         });
      }
   });

JAVASCRIPT;
      echo $JS;
}
