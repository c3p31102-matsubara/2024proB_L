<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
</head>

<body>
    <?php
    include_once "php/utility.php";
    $dbh = connect();
    $userlist = new user_list($dbh);
    $lostitemlist = new lostitem_list($dbh);
    $discoverylist = new discovery_list($dbh);
    $managementlist = new management_list($dbh);
    $affiliationlist = new affiliation_list($dbh);
    foreach ($userlist->GetContents() as $user) {
        $user->Describe();
    }
    echo "<hr>";
    foreach ($lostitemlist->GetContents() as $lostitem) {
        $lostitem->Describe();
    }
    echo "<hr>";
    foreach ($managementlist->GetContents() as $management) {
        $management->Describe();
    }
    echo "<hr>";
    printp($userlist->Serialize());
    echo "<hr>";
    printp($lostitemlist->Serialize());
    echo "<hr>";
    printp($managementlist->Serialize_recursive());
    printp($managementlist->sql_insert());
    ?>
</body>

</html>