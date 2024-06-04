<?php

include_once("m_class.php");

if(isset($_REQUEST["type"],$_REQUEST["data"])){
  if($_REQUEST["type"] == "json" && $_REQUEST["data"] == "find_item") $obj = new find_item_list();
  if($_REQUEST["type"] == "json" && $_REQUEST["data"] == "user") $obj = new user_list();
  if($_REQUEST["type"] == "json" && $_REQUEST["data"] == "category") $obj = new category_list();
  $j = $obj->getJson();
  header("Content-Type: application/json; charset=utf-8");
  echo($j);
  exit;
}

?>
