var getUrlParameter = function(val) {
   var result = undefined,
       tmp = [];

   location.search
      .substr(1) // remove '?'
      .split("&")
      .forEach(function (item) {
         tmp = item.split("=");
         if (tmp[0] === val) {
            result = decodeURIComponent(tmp[1]);
         }
      });
   return result;
};


var checkDOMChange = function (selector, handler) {
   if ($(selector).get(0)) {
      return handler();
   }
   setTimeout( function() {
      checkDOMChange(selector, handler);
   }, 100 );
};


var pluginEscaladeActorPanelIndexes = {
   requester: 0,
   observer: 1,
   assign: 2
};


var pluginEscaladeGetActorPanel = function(role) {
   var $modernPanel = $(".itil-actor-card[data-actor-role='" + role + "']").first();
   if ($modernPanel.length) {
      return $modernPanel;
   }

   var panelIndex = pluginEscaladeActorPanelIndexes[role];
   if (panelIndex === undefined) {
      return $();
   }

   return $(".tab_actors .actor-bloc").eq(panelIndex);
};


var pluginEscaladeIsModernActorPanel = function($panel) {
   return $panel.is(".itil-actor-card");
};
