<?php
include ("../../../inc/includes.php");
header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkLoginUser();

if (!isset($_REQUEST['tickets_id'])) {
   exit;
}

$PluginEscaladeGroup_Group = new PluginEscaladeGroup_Group();

$PluginEscaladeGroup_Group->getAssignGroups($_REQUEST['tickets_id']);