<?php
function connect(): PDO
{
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
    return $dbh;
}
class sqlConnecter
{
    var $datalist = array();
    var $sql;
    public function getList(PDO $dbh): PDOStatement
    {
        $dsn_query = $dbh->query($this->sql);
        $dsn_query->setFetchMode(PDO::FETCH_ASSOC);
        return $dsn_query;
    }
    public function GetContents(): array
    {
        return $this->datalist;
    }
}
class user_list extends sqlConnecter
{
    public function __construct($dbh)
    {
        // $sql = "SELECT user.ID, attribute, affiliationID, EmailAddress, number, telephone FROM user;";
        $this->sql = "SELECT user.ID, attribute, affiliationID, EmailAddress, number, telephone, name FROM user;";
        $array = $this->getList($dbh);
        $this->AddContents($array->fetchAll());
    }
    public function AddContents($contents): void
    {
        foreach ($contents as $content)
            $this->datalist[] = new user($content);
    }
    public function Get_user_by_id($target): user|null
    {
        foreach ($this->datalist as $user)
            if ($user->ID == $target)
                return $user;
        return null;
    }
}
class lostitem_list extends sqlConnecter
{
    public function __construct($dbh)
    {
        $this->sql = "SELECT ID, userID, color, features, category, datetime, place FROM lost";
        $array = $this->getList($dbh);
        $this->Addcontents($array->fetchAll());
    }
    public function AddContents($contents): void
    {
        foreach ($contents as $content)
            $this->datalist[] = new lostitem($content);
    }
}
class discovery_list extends sqlConnecter
{
    public function __construct($dbh)
    {
        $this->sql = "SELECT ID, userID, color, features, category, datetime, place FROM discovery";
        $array = $this->getList(($dbh));
        $this->AddContents($array->fetchAll());
    }
    public function AddContents($contents): void
    {
        foreach ($contents as $content)
            $this->datalist[] = new discovery($content);
    }
}
class item
{
    public function __construct($args)
    {
        foreach ($args as $key => $value)
            if (!is_numeric($key))
                $this->$key = $value;
    }
}
enum userType: string
{
    case student = "student";
    case teacher = "teacher";
}
class user extends item
{
    var int $ID;
    var userType $attribute;
    var string $number;
    var int $affiliationID;
    var int $faculty;
    var string $EmailAddress;
    var string $telephone;
    var string $name;
    public function __construct($args)
    {
        $this->attribute = userType::from($args["attribute"]);
        unset($args["attribute"]);
        parent::__construct($args);
    }
    public function describe(): void
    {
        echo "my name is " . $this->name;
    }
}
class lostitem extends item
{
    var int $ID;
    var int $userID;
    var string $color;
    var string $features;
    var string $category;
    var string $datetime;
    var string $place;
    public function __construct($args)
    {
        parent::__construct($args);
    }
    public function Get_user(): user
    {
        return $GLOBALS["userlist"]->Get_user_by_id($this->userID);
    }
    public function describe(): void
    {
        echo "this item's color is " . $this->color . "<br>";
        echo "this item's owners name is " . $this->Get_user()->name;
    }
}
class discovery extends item
{
    var int $ID;
    var int $userID;
    var string $color;
    var string $features;
    var string $category;
    var string $datetime;
    var string $place;
    public function __construct($args)
    {
        parent::__construct($args);
    }
    public function Get_user(): user
    {
        return $GLOBALS["userlist"]->Get_user_by_id($this->userID);
    }
    public function describe(): void
    {
        echo "this item's color is " . $this->color . "<br>";
        echo "this item's owners name is " . $this->Get_user()->name;
    }
}