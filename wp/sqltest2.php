<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
</head>

<body>
    <?php
    include "sqltest.php";
    include "classes.php";
    $dbh = connect();
    $test = new user_list($dbh);
    foreach ($test->getList($dbh)->fetchAll() as $user)
        foreach ($user as $content)
            echo $content. " ";
                ?>
        </body>

        </html>