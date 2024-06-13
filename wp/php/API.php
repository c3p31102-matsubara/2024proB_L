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
$dbh = connect();
$userlist = new user_list($dbh);
$lostitemlist = new lostitem_list($dbh);
$discoverylist = new discovery_list($dbh);
$managementlist = new management_list($dbh);
$affiliationlist = new affiliation_list($dbh);
$notfound = "{'detail': '404 not found'}";
$req_type = $_REQUEST["type"];
file_put_contents("./log.txt", $req_type);
if ($req_type == "json") {
    $result = "";
    if (isset($_REQUEST["data"]))
        $req_type = dataType::from($_REQUEST["data"]);
    else
        echo $notfound;
    exit;
    if (isset($_REQUEST["id"]))
        $req_id = $_REQUEST["id"];
    else
        $req_id = 1;
    if (isset($_REQUEST["recursive"]))
        $req_recursive = $_REQUEST["recursive"];
    else
        $req_recursive = false;
    match ($req_type) {
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
    if (is_null($obj)) {
        $result = $notfound;
    } elseif ($req_recursive)
        $result = $obj->Serialize_recursive();
    else
        $result = $obj->Serialize();
    header("Content-Type: application/json; charset=utf-8");
    echo $result;
} elseif ($req_type == "insert") {
    if (!isset($_POST["table"], $_POST["data"])) {
        header("Content-Type: application/json; charset=utf-8");
        echo $notfound;
        exit;
    }
    $req_table = $_REQUEST["table"];
    $target = match ($req_table) {
        dataType::userlist => $userlist,
        dataType::lostitemlist => $lostitemlist,
        dataType::discoverylist => $discoverylist,
        dataType::managementlist => $managementlist,
        default => false,
    };
    if (!$target) {
        header("Content-Type: application/json; charset=utf-8");
        echo $notfound;
        exit;
    }
    try{
        $dbh->beginTransaction();
        $sql = $target->sql_insert();
    }
}