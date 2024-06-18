<?php

include_once "utility.php";
$dbh = connect();
$userlist = new user_list($dbh);
$lostitemlist = new lostitem_list($dbh);
$discoverylist = new discovery_list($dbh);
$managementlist = new management_list($dbh);
$affiliationlist = new affiliation_list($dbh);
function output(string $status, $body = null)
{
    echo json_encode(array("status" => $status, "body" => $body));
}
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
        output("fail", "data wasn't specified");
    if (isset($_REQUEST["id"]))
        $req_id = $_REQUEST["id"];
    else
        $req_id = 1;
    if (isset($_REQUEST["recursive"]))
        $req_recr = $_REQUEST["recursive"] == 'true';
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
        output("success", $obj->GetContent_recursive());
    else
        output("success", $obj->GetContents());
    exit;
} elseif ($req_type == "insert") {
    if (!isset($_REQUEST["target"])) {
        output("fail", "target wasn't specified");
        exit;
    }
    $req_target = match (dataType::from($_REQUEST["target"])) {
        dataType::userlist => $obj = $userlist,
        dataType::lostitemlist => $obj = $lostitemlist,
        dataType::discoverylist => $obj = $discoverylist,
        dataType::managementlist => $obj = $managementlist,
        dataType::affiliationlist => $obj = $affiliationlist,
    };
    if (!isset($_REQUEST["data"])) {
        output("fail", "data was empty");
        exit;
    }
    $req_data = $_REQUEST["data"];
    try {
        $dbh->beginTransaction();
        $sql = $req_target->sql_insert();
        $stmt = $dbh->prepare($sql);
        $stmt->execute($req_data);
        $dbh->commit();
        $dbh->beginTransaction();
        $sql = "SELECT LAST_INSERT_ID()";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        output("success", array("id" => $stmt));
    } catch (PDOException $e) {
        file_put_contents("./log.txt", $e, FILE_APPEND);
        $dbh->rollBack();
        output("fail", $e);
    }
    exit;
}