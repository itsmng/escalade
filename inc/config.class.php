<?php

class PluginEscaladeConfig extends CommonDBTM {
   static $rightname  = 'config';

   static function getTypeName($nb = 0) {
      return __("Configuration Escalade plugin", "escalade");
   }

   function showForm($ID, $options = []) {
      global $CFG_GLPI;

      $this->initForm($ID, $options);
      $this->check($ID, READ);

      echo "<div class='escalade_config'>";
      $this->showFormHeader($options);

      $rand = mt_rand();
      echo "<tr class='tab_bg_1'>";
      echo "<td><label for='dropdown_remove_group$rand'>";
      echo __("Remove old assign group on new group assign", "escalade") . "</label></td>";
      echo "<td>";
      Dropdown::showYesNo("remove_group", $this->fields["remove_group"], -1, [
            'on_change' => 'hide_show_history(this.value)',
            'width' => '25%',
            'rand' => $rand,
      ]);
      echo Html::scriptBlock("
         function hide_show_history(val) {
            var display = (val == 0) ? 'none' : '';
            document.getElementById('show_history_td1').style.display = display;
            document.getElementById('show_history_td2').style.display = display;
            document.getElementById('show_solve_return_group_td1').style.display = display;
            document.getElementById('show_solve_return_group_td2').style.display = display;
         }
      ");
      echo "</td>";

      $style = ($this->fields["remove_group"]) ? "" : "style='display: none !important;'";

      $rand = mt_rand();
      echo "<td id='show_history_td1' $style><label for='dropdown_show_history$rand'>";
      echo __("show group assign history visually", "escalade");
      echo "</label></td>";
      echo "<td id='show_history_td2' $style>";
      Dropdown::showYesNo("show_history", $this->fields["show_history"], -1, [
         'width' => '100%',
         'rand' => $rand,
      ]);
      echo "</td>";
      echo "</tr>";

      $rand = mt_rand();
      echo "<tr class='tab_bg_1'>";
      echo "<td><label for='dropdown_task_history$rand'>".__("Escalation history in tasks", "escalade")."</label></td>";
      echo "<td>";
      Dropdown::showYesNo("task_history", $this->fields["task_history"], -1, [
         'width' => '25%',
         'rand' => $rand,
      ]);
      echo "</td>";

      $rand = mt_rand();
      echo "<td><label for='dropdown_remove_tech$rand'>".__("Remove technician(s) on escalation", "escalade"). "</label></td>";
      echo "<td>";
      Dropdown::showYesNo("remove_tech", $this->fields["remove_tech"], -1, [
         'width' => '100%',
         'rand' => $rand,
      ]);
      echo "</td>";
      echo "</tr>";

      $rand = mt_rand();
      echo "<tr class='tab_bg_1'>";
      echo "<td><label for='dropdown_ticket_last_status$rand'>";
      echo __("Ticket status after an escalation", "escalade")."</label></td>";
      echo "<td>";
      self::dropdownGenericStatus("Ticket", "ticket_last_status",
                                  $rand, $this->fields["ticket_last_status"]);
      echo "</td>";

      $rand = mt_rand();
      echo "<td id='show_solve_return_group_td1' $style><label for='dropdown_solve_return_group$rand'>";
      echo __("Assign ticket to initial group on solve ticket", "escalade");
      echo "</td>";
      echo "<td id='show_solve_return_group_td2' $style>";
      Dropdown::showYesNo("solve_return_group", $this->fields["solve_return_group"], -1, [
         'width' => '100%',
         'rand' => $rand,
      ]);
      echo "</td>";
      echo "</tr>";

      $rand = mt_rand();
      echo "<tr class='tab_bg_1'>";
      echo "<td><label for='dropdown_reassign_tech_from_cat$rand'>";
      echo __("Assign the technical manager on ticket category change", "escalade");
      echo "</label></td>";
      echo "<td>";
      Dropdown::showYesNo("reassign_tech_from_cat", $this->fields["reassign_tech_from_cat"], -1, [
         'width' => '25%',
         'rand' => $rand,
      ]);
      echo "</td>";

      $rand = mt_rand();
      echo "<td><label for='dropdown_reassign_group_from_cat$rand'>";
      echo __("Assign the technical group on ticket category change", "escalade");
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("reassign_group_from_cat", $this->fields["reassign_group_from_cat"], -1, [
         'width' => '100%',
         'rand' => $rand,
      ]);
      echo "</td>";
      echo "</tr>";

      $rand = mt_rand();
      echo "<tr class='tab_bg_1'>";
      echo "<td><label for='dropdown_cloneandlink_ticket$rand'>".__("Clone tickets", "escalade")."</label></td>";
      echo "<td>";
      Dropdown::showYesNo("cloneandlink_ticket", $this->fields["cloneandlink_ticket"], -1, [
         'width' => '25%',
         'rand' => $rand,
      ]);
      echo "</td>";

      $rand = mt_rand();
      echo "<td><label for='dropdown_close_linkedtickets$rand'>";
      echo __("Close cloned tickets at the same time", "escalade");
      echo "</label></td>";
      echo "<td>";
      Dropdown::showYesNo("close_linkedtickets", $this->fields["close_linkedtickets"], -1, [
         'width' => '100%',
         'rand' => $rand,
      ]);
      echo "</td>";
      echo "</tr>";

      $yesnoall = [
            0 => __("No"),
            1 => __('First'),
            2 => __('Last'),
      ];

      $rand = mt_rand();
      echo "<tr class='tab_bg_1'>";
      echo "<td><label for='dropdown_use_assign_user_group$rand'>".__("Use the technician's group", "escalade")."</label></td>";
      echo "<td>";
      Dropdown::showFromArray('use_assign_user_group', $yesnoall, [
         'value'     => $this->fields['use_assign_user_group'],
         'width'     => '120px',
         'rand'      => $rand,
         'on_change' => 'hide_technician_group(this.value)',
      ]);
      echo "</td>";
      echo "<td colspan='2'>";
      $style = "width: 100%;";
      $style.= $this->fields["use_assign_user_group"]
                  ? ""
                  : "display: none !important;";
      echo "<table style='$style' id='use_technican_group_details'>";
      echo "<tr>";
      echo "<td></td>";
      echo "<td><label for='dropdown_use_assign_user_group_creation$rand'>";
      echo __("a time of creation", "escalade")."</label></td>";
      echo "<td><label for='dropdown_use_assign_user_group_modification$rand'>";
      echo __("a time of modification", "escalade")."</label></td>";
      echo "</tr>";
      echo "<tr><td>";
      echo Html::scriptBlock("
         function hide_technician_group(val) {
            var display = (val == 0) ? 'none' : '';
            document.getElementById('use_technican_group_details').style.display = display;
         }
      ");
      echo "</td>";

      $rand = mt_rand();
      echo "<td>";
      Dropdown::showYesNo("use_assign_user_group_creation",
                          $this->fields["use_assign_user_group_creation"], -1, ['rand' => $rand]);
      echo "</td>";

      $rand = mt_rand();
      echo "<td style='padding:0px'>";
      Dropdown::showYesNo("use_assign_user_group_modification",
                          $this->fields["use_assign_user_group_modification"], -1, ['rand' => $rand]);
      echo "</td>";
      echo "</tr></table>";
      $plugin = new Plugin();
      if ($plugin->isInstalled('behaviors') && $plugin->isActivated('behaviors')) {
         echo "<i>".str_replace('##link##',
            $CFG_GLPI["root_doc"]."/front/config.form.php?forcetab=PluginBehaviorsConfig%241",
            __("Nota: This feature (creation part) is duplicate with the <a href='##link##'>Behavior</a>plugin. This last has priority.",
            "escalade"))."</i>";
      }
      echo "</td>";
      echo "</tr>";

      $rand = mt_rand();
      echo "<tr class='tab_bg_1'>";
      echo "<td><label for='dropdown_remove_requester$rand'>".__("Remove requester(s) on escalation", "escalade"). "</label></td>";
      echo "<td>";
      Dropdown::showYesNo("remove_requester", $this->fields["remove_requester"], -1, [
         'width' => '100%',
         'rand' => $rand,
      ]);
      echo "</td>";

      $rand = mt_rand();
      echo "<td id='assign_group_td1' $style><label for='dropdown_assign_group$rand'>";
      echo __("Assign a group quickly", "escalade");
      echo "</label></td>";
      echo "<td id='assign_group_td2' $style>";
      Dropdown::showYesNo("assign_group", $this->fields["assign_group"], -1, [
         'width' => '100%',
         'rand' => $rand,
      ]);
      echo "</td>";
      echo "</tr>";

      $rand = mt_rand();
      echo "<tr class='tab_bg_1'>";
      echo "<td><label for='dropdown_remove_delete_group_btn$rand'>";
      echo __("Display delete button", "escalade") . "</td>";
      echo "<td colspan='3'>";

      echo "<table style='width: 100%'>";
      echo "<tr>";
      echo "<th></th>";
      echo "<th>".__("Requester")."</th>";
      echo "<th>".__("Watcher")."</th>";
      echo "<th>".__("Assigned to")."</th>";
      echo "</tr>";
      echo "<tr>";
      echo "<th>".__("User")."</th>";
      echo "<td>";
      Dropdown::showYesNo("remove_delete_requester_user_btn",
                          $this->fields["remove_delete_requester_user_btn"]);
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("remove_delete_watcher_user_btn",
                          $this->fields["remove_delete_watcher_user_btn"]);
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("remove_delete_assign_user_btn",
                          $this->fields["remove_delete_assign_user_btn"]);
      echo "</td>";
      echo "</tr>";
      echo "<tr>";
      echo "<th>".__("Group")."</th>";
      echo "<td>";
      Dropdown::showYesNo("remove_delete_requester_group_btn",
                          $this->fields["remove_delete_requester_group_btn"]);
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("remove_delete_watcher_group_btn",
                          $this->fields["remove_delete_watcher_group_btn"]);
      echo "</td>";
      echo "<td>";
      Dropdown::showYesNo("remove_delete_assign_group_btn",
                          $this->fields["remove_delete_assign_group_btn"]);
      echo "</td>";
      echo "</tr>";
      echo "<tr>";
      echo "<th>".__("Supplier")."</th>";
      echo "<td colspan='2'></td>";
      echo "<td>";
      Dropdown::showYesNo("remove_delete_assign_supplier_btn",
                          $this->fields["remove_delete_assign_supplier_btn"]);
      echo "</td>";
      echo "</tr>";
      echo "</table>";
      echo "</td>";
      echo "</tr>";

      $rand = mt_rand();
      echo "<tr class='tab_bg_1'>";
      echo "<td><label for='dropdown_use_filter_assign_group$rand'>";
      echo __("Enable filtering on the groups assignment", "escalade") . "</td>";
      echo "<td>";
      Dropdown::showYesNo("use_filter_assign_group", $this->fields["use_filter_assign_group"], -1, [
         'width' => '100%',
         'rand' => $rand,
      ]);
      echo "</td>";

      $rand = mt_rand();
      echo "<td><label for='dropdown_bypass_categories$rand'>";
      echo __("Ticket categories that bypass escalation", "escalade") . "</td>";
      echo "<td>";

      $selected_categories = [];
      if (!empty($this->fields['bypass_categories'])) {
         $selected_categories = json_decode($this->fields['bypass_categories'], true);
         if (!is_array($selected_categories)) {
            $selected_categories = [];
         }
      }

      $category = new ITILCategory();
      $categories = $category->find([
         'is_helpdeskvisible' => 1
      ] + getEntitiesRestrictCriteria(ITILCategory::getTable()),
      ['completename']);

      echo "<select name='_bypass_categories[]' id='dropdown_bypass_categories$rand' multiple size='8'>";

      foreach ($categories as $cat) {
         $name = isset($cat['completename']) ? $cat['completename'] : $cat['name'];
         $selected = in_array($cat['id'], $selected_categories) ? ' selected' : '';
         echo "<option value='{$cat['id']}'$selected>" . htmlspecialchars($name) . "</option>";
      }
      echo "</select>";

      echo "<input type='hidden' name='bypass_categories' id='bypass_categories_json_$rand' value='" . json_encode($selected_categories) . "'>";

      echo Html::scriptBlock("
         $(document).ready(function() {
            var initSelect2$rand = function() {
               $('#dropdown_bypass_categories$rand').select2({
                  theme: 'bootstrap-5',
                  width: '100%',
                  closeOnSelect: false,
                  placeholder: '" . __('Select categories', 'escalade') . "'
               }).on('change', function() {
                  var selected = $(this).val() || [];
                  $('#bypass_categories_json_$rand').val(JSON.stringify(selected));
               });
            };

            if (typeof $.fn.select2 !== 'undefined') {
               initSelect2$rand();
            } else {
               $.getScript('" . $CFG_GLPI['root_doc'] . "/node_modules/select2/dist/js/select2.min.js', function() {
                  initSelect2$rand();
               });
            }
         });
      ");

      echo "</td>";
      echo "</tr>";

      $options['candel']       = false;
      $this->showFormButtons($options);
      echo "</div>";
   }

   static function loadInSession() {
      $config = new self();
      $config->getFromDB(1);
      unset($config->fields['id']);

      if (isset($_SESSION['glpiID'])
          && isset($config->fields['use_filter_assign_group'])
          && $config->fields['use_filter_assign_group']) {
         $user = new PluginEscaladeUser();
         if ($user->getFromDBByCrit(['users_id' => $_SESSION['glpiID']])) {
            //if a bypass is defined for user
            if ($user->fields['use_filter_assign_group']) {
               $config->fields['use_filter_assign_group'] = 0;
            }
         }
      }

      $_SESSION['plugins']['escalade']['config'] = $config->fields;

   }

    static function canBypassEscalation($categories_id) {
       $config = $_SESSION['plugins']['escalade']['config'] ?? [];

       if (empty($config['bypass_categories'])) {
          return false;
       }

       $bypass_categories = json_decode($config['bypass_categories'], true);
       if (!is_array($bypass_categories)) {
          return false;
       }

       return in_array($categories_id, $bypass_categories);
    }

    static function dropdownGenericStatus($itemtype, $name, $rand, $value = CommonITILObject::INCOMING) {
      $item = new $itemtype();

      $tab[-1] = __("Don't change", "escalade");

      $i = 1;
      foreach ($item->getAllStatusArray(false) as $status) {
         $tab[$i] = $status;
         $i++;
      }

      Dropdown::showFromArray($name, $tab, [
         'value' => $value,
         'width' => '80%',
         'rand' => $rand,
      ]);
   }
}
