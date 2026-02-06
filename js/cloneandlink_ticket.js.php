<?php
include ("../../../inc/includes.php");

//change mimetype
header("Content-type: application/javascript");

//not executed in self-service interface & right verification
if ($_SESSION['glpiactiveprofile']['interface'] == "central"
   && Session::haveRight("ticket", CREATE)
   && Session::haveRight("ticket", UPDATE)
   ) {

   $locale_cloneandlink  = __("Clone and link", "escalade");
   $locale_linkedtickets = _n('Linked ticket', 'Linked tickets', 2);

   $JS = <<<JAVASCRIPT
   var plugin_url = CFG_GLPI.root_doc+"/"+GLPI_PLUGINS_PATH.escalade;

   addCloneLink = function() {

      //only in edit form
      if (getUrlParameter('id') == undefined) {
         return;
      }

      //delay the execution (ajax requestcomplete event fired before dom loading)
      setTimeout( function () {
         if ($("#cloneandlink_ticket").length > 0) { return; }
         var duplicate_html = "&nbsp;<img src='"+plugin_url+"/pics/cloneandlink_ticket.png' "+
            "alt='$locale_cloneandlink' " +
            "title='$locale_cloneandlink' class='pointer' id='cloneandlink_ticket'>";

         // V1: old table-based layout
         var target = $("th:contains('$locale_linkedtickets')>span.fa");
         if (target.length > 0) {
            target.after(duplicate_html);
            addOnclick();
            return;
         }

         // V2: find label for linked tickets section
         var label = $("label.form-label:contains('$locale_linkedtickets')").first();
         if (label.length > 0) {
            var textNode = label.contents().filter(function() {
               return this.nodeType === 3 && this.textContent.trim().length > 0;
            }).first();
            if (textNode.length > 0) {
               textNode.after(duplicate_html);
            } else {
               label.prepend(duplicate_html);
            }
            addOnclick();
         }

      }, 100);
   }

   addOnclick = function() {
      //onclick event on new buttons
      $('#cloneandlink_ticket').on('click', function() {

         var tickets_id = getUrlParameter('id');

         $.ajax({
            url:     plugin_url+'/ajax/cloneandlink_ticket.php',
            data:    { 'tickets_id': tickets_id },
            success: function(response, opts) {
               var res = JSON.parse(response);

               if (res.success == false) {
                  return false;
               }
               var url_newticket = 'ticket.form.php?id='+res.newID;

               //change to on new ticket created
               window.location.href = url_newticket;
            }
         });
      });
   }

   $(document).ajaxStop(function() {
      addCloneLink();
   });

   $(document).ready(function() {
      var targetNode = document.getElementById('main-accordion-view');
      if (targetNode) {
         var debounceTimer;
         var observer = new MutationObserver(function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(addCloneLink, 300);
         });
         observer.observe(targetNode, { childList: true, subtree: true });
      }
   });

JAVASCRIPT;
   echo $JS;
}