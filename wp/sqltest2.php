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
    foreach ($userlist->GetContents() as $user) {
        $user->describe();
    }
    echo "<hr>";
    foreach ($lostitemlist->GetContents() as $lostitem) {
        $lostitem->describe();
    }
    echo "<hr>";
    foreach ($managementlist->GetContents() as $management) {
        $management->describe();
    }
    echo "<hr>";
    echo $userlist->serialize() . "<br>";
    echo $lostitemlist->serialize() . "<br>";
    ?>
</body>

</html>