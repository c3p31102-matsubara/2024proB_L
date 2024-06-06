<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
</head>

<body>
    <?php
    include "php/sqltest.php";
    include_once "php/classes.php";
    $dbh = connect();
    $userlist = new user_list($dbh);
    $userlistfetch = $userlist->getList($dbh)->fetchAll();
    foreach ($userlistfetch as $user) {
        foreach ($user as $content)
            echo $content . " ";
    }
    echo "<hr>";
    var_dump($userlist->GetContent());
    var_dump($userlist->get_user_by_id($dbh, 1));
    $lostitemlist = new lostitem_list($dbh);
    foreach ($lostitemlist->getList($dbh)->fetchAll() as $lostitem) {
        foreach ($lostitem as $content) {
            echo $content . " "; //TODO: 表示形式変更
        }
    }
    ?>
</body>

</html>