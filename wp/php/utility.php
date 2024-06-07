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
class sqlConnecter implements JsonSerializable
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
    public function JsonSerialize(): array
    {
        return $this->GetContents();
    }
    public function serialize(): string
    {
        return json_encode($this, JSON_UNESCAPED_UNICODE);
    }
}
class user_list extends sqlConnecter
{
    public function __construct(PDO $dbh)
    {
        // $sql = "SELECT user.ID, attribute, affiliationID, EmailAddress, number, telephone FROM user;";
        $this->sql = "SELECT user.ID, attribute, affiliationID, EmailAddress, number, telephone, name FROM user;";
        $array = $this->getList($dbh);
        $this->AddContents($array->fetchAll());
    }
    public function AddContents(array $contents): void
    {
        foreach ($contents as $content)
            $this->datalist[] = new user($content);
    }
    public function Get_user_by_id(int $target): user|null
    {
        foreach ($this->datalist as $user)
            if ($user->ID == $target)
                return $user;
        return null;
    }
}
class lostitem_list extends sqlConnecter
{
    public function __construct(PDO $dbh)
    {
        $this->sql = "SELECT ID, userID, color, features, category, datetime, place FROM lost";
        $array = $this->getList($dbh);
        $this->Addcontents($array->fetchAll());
    }
    public function AddContents(array $contents): void
    {
        foreach ($contents as $content)
            $this->datalist[] = new lostitem($content);
    }
    public function Get_lostitem_by_id(int $target): lostitem|null
    {
        foreach ($this->datalist as $lostitem)
            if ($lostitem->ID == $target)
                return $lostitem;
        return null;
    }
}
class discovery_list extends sqlConnecter
{
    public function __construct(PDO $dbh)
    {
        $this->sql = "SELECT ID, userID, color, features, category, datetime, place FROM discovery";
        $array = $this->getList(($dbh));
        $this->AddContents($array->fetchAll());
    }
    public function AddContents(array $contents): void
    {
        foreach ($contents as $content)
            $this->datalist[] = new discovery($content);
    }
    public function Get_discovery_by_id(int $target): discovery|null
    {
        foreach ($this->datalist as $discovery)
            if ($discovery->ID == $target)
                return $discovery;
        return null;
    }
}
class management_list extends sqlConnecter
{
    public function __construct(PDO $dbh)
    {
        $this->sql = "SELECT ID, lostID, discoveryID, changedate, changedetail FROM management";
        $array = $this->getList($dbh);
        $this->AddContents($array->fetchAll());
    }
    public function AddContents(array $contents): void
    {
        foreach ($contents as $content)
            $this->datalist[] = new management($content);
    }
    public function Get_management_by_id(int $target): management|null
    {
        foreach ($this->datalist as $management)
            if ($management->ID == $target)
                return $management;
        return null;
    }
}
abstract class item implements JsonSerializable
{
    public function __construct(array $args)
    {
        foreach ($args as $key => $value)
            if (!is_numeric($key))
                $this->$key = $value;
    }
    abstract function JsonSerialize(): array;
    public function serialize(): string
    {
        return json_encode($this, JSON_UNESCAPED_UNICODE);
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
    var string $EmailAddress;
    var string $telephone;
    var string $name;
    public function __construct(array $args)
    {
        $this->attribute = userType::from($args["attribute"]);
        unset($args["attribute"]);
        parent::__construct($args);
    }
    public function describe(): void
    {
        echo "my name is " . $this->name;
    }
    public function JsonSerialize(): array
    {
        return array(
            "ID" => $this->ID,
            "attribute" => $this->attribute,
            "number" => $this->attribute,
            "affiliationID" => $this->affiliationID,
            "emailAddress" => $this->EmailAddress,
            "telephone" => $this->telephone,
            "name" => $this->name
        );
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
    public function __construct(array $args)
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
    public function JsonSerialize(): array
    {
        return array(
            "ID" => $this->ID
        );
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
    public function __construct(array $args)
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
    public function JsonSerialize(): array
    {
        return array(
            "ID" => $this->ID
        );
    }
}
class management extends item
{
    var int $ID;
    var int $lostID;
    var int $discoveryID;
    var string $changedate;
    var string $changedetail;
    public function get_lostitem(): lostitem|null
    {
        return $GLOBALS["lostitemlist"]->Get_lostitem_by_id($this->lostID);
    }
    public function get_Discovery(): lostitem|null
    {
        return $GLOBALS["discoverylist"]->Get_discovery_by_id($this->discoveryID);
    }
    public function describe(): void
    {
        echo "this case's ID is " . $this->ID . "<br>";
        echo "this item's lostitem's color is " . $this->get_lostitem()->color . "<br>";
    }
    public function JsonSerialize(): array
    {
        return array(
            "ID" => $this->ID
        );
    }
}