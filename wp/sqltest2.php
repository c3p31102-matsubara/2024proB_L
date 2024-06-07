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
    foreach ($userlist->GetContents() as $user) {
        $user->describe();
    }
    echo "<hr>";
    $lostitemlist = new lostitem_list($dbh);
    foreach ($lostitemlist->GetContents() as $lostitem) {
        $lostitem->describe();
    }
    ?>
</body>

</html>