<?php

include_once "utility.php";
enum dataType: string
{
    case userlist = "user_l";
    case lostitemlist = "lost_l";
    case discoverylist = "discovery_l";
    case managementlist = "management_l";
    case affiliationlist = "affiliation_l";
    case user = "user";
    case lostitem = "lost";
    case discovery = "discovery";
    case management = "management";
    case affiliation = "affiliation";
}
if (!isset($_REQUEST["type"])) {
    file_put_contents("./log.txt", "error1");
    exit;
}
$req_type = $_REQUEST["type"];
if ($req_type == "json") {
    if (isset($_REQUEST["data"]))
        $req_target = dataType::from($_REQUEST["data"]);
    else
        echo [];
    if (isset($_REQUEST["id"]))
        $req_id = $_REQUEST["id"];
    else
        $req_id = 1;
    if (isset($_REQUEST["recursive"]))
        $req_recr = $_REQUEST["recursive"];
    else
        $req_recr = false;
    $dbh = connect();
    $userlist = new user_list($dbh);
    $lostitemlist = new lostitem_list($dbh);
    $discoverylist = new discovery_list($dbh);
    $managementlist = new management_list($dbh);
    $affiliationlist = new affiliation_list($dbh);
    match ($req_target) {
        dataType::userlist => $obj = $userlist,
        dataType::lostitemlist => $obj = $lostitemlist,
        dataType::discoverylist => $obj = $discoverylist,
        dataType::managementlist => $obj = $managementlist,
        dataType::affiliationlist => $obj = $affiliationlist,
        dataType::user => $obj = $userlist->GetContent_by_id($req_id),
        dataType::lostitem => $obj = $lostitemlist->GetContent_by_id($req_id),
        dataType::discovery => $obj = $discoverylist->GetContent_by_id($req_id),
        dataType::management => $obj = $managementlist->GetContent_by_id($req_id),
        dataType::affiliation => $obj = $affiliationlist->GetContent_by_id($req_id),
    };
    header("Content-Type: application/json; charset=utf-8");
    if ($req_recr)
        echo $obj->Serialize_recursive();
    else
        echo $obj->Serialize();
}