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
    foreach($userlist->GetContents() as $user)
    {
        $user->sayhello();
    }
    echo "<hr>";
    ?>
</body>

</html>