<!DOCTYPE html>
<html lang="ja">

<head>
    <?php

    ?>
</head>

<body>
    <?php
    define("db_host", "localhost");
    define("db_name", "probc2024");
    define("db_user", "2024probl");
    define("db_password", "");

    $option = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET CHARACTER SET UTF8");
    error_reporting(E_ALL & ~E_NOTICE);
    try {
        $dbh = new PDO('mysql:host=' . db_host . ";dbname=" . db_name, db_user, db_password, $option);
    } catch (PDOException $e) {
        echo $e->getMessage();
        exit;
    }
    $users_query = $dbh->query("SELECT * FROM ユーザ");
    $users_query->setFetchMode(PDO::FETCH_ASSOC);
    foreach ($users_query->fetchAll() as $user) {
        foreach ($user as $content)
            echo $content . " ";
        echo "<br>";
    }
    ?>
</body>

</html>