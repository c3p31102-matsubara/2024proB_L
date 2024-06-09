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
function printp(string $text): void
{
    $result = "<p>";
    $result .= $text;
    $result .= "</p>";
    echo $result;
}
abstract class sqlTable implements JsonSerializable
{
    protected $datalist = array();
    protected $sql;
    public function Update(PDO $dbh): void
    {
        $this->datalist = array();
        $dsn_query = $dbh->query($this->sql);
        $dsn_query->setFetchMode(PDO::FETCH_ASSOC);
        $this->AddContents($dsn_query->fetchAll());
    }
    abstract function AddContents(array $contents): void;
    public function GetContents(): array
    {
        return $this->datalist;
    }
    public function Getcontent_by_id(int $target): mixed
    {
        foreach ($this->datalist as $user)
            if ($user->ID == $target)
                return $user;
        return null;
    }
    public function JsonSerialize(): array
    {
        return $this->GetContents();
    }
    public function Serialize(): ?string
    {
        return json_encode($this, JSON_UNESCAPED_UNICODE);
    }
    abstract function GetContent_recursive(): array;
    public function Serialize_recursive(): ?string
    {
        return json_encode($this->GetContent_recursive(), JSON_UNESCAPED_UNICODE);
    }
}
class user_list extends sqlTable
{
    public function __construct(PDO $dbh)
    {
        // $sql = "SELECT user.ID, attribute, affiliationID, EmailAddress, number, telephone FROM user;";
        $this->sql = "SELECT user.ID, attribute, affiliationID, EmailAddress, number, telephone, name FROM user;";
        $this->Update($dbh);
    }
    public function AddContents(array $contents): void
    {
        foreach ($contents as $content)
            $this->datalist[] = new user($content);
    }
    public function GetContent_recursive(): array
    {
        $result = array();
        foreach ($this->GetContents() as $content)
            $result[] = $content->GetContent_recursive();
        return $result;
    }
}
class lostitem_list extends sqlTable
{
    public function __construct(PDO $dbh)
    {
        $this->sql = "SELECT ID, userID, color, features, category, datetime, place FROM lost";
        $this->Update($dbh);
    }
    public function AddContents(array $contents): void
    {
        foreach ($contents as $content)
            $this->datalist[] = new lostitem($content);
    }
    public function GetContent_recursive(): array
    {
        $result = array();
        foreach ($this->GetContents() as $content)
            $result[] = $content->GetContent_recursive();
        return $result;
    }
}
class discovery_list extends sqlTable
{
    public function __construct(PDO $dbh)
    {
        $this->sql = "SELECT ID, userID, color, features, category, datetime, place FROM discovery";
        $this->Update($dbh);
    }
    public function AddContents(array $contents): void
    {
        foreach ($contents as $content)
            $this->datalist[] = new discovery($content);
    }
    public function GetContent_recursive(): array
    {
        $result = array();
        foreach ($this->GetContents() as $content)
            $result[] = $content->GetContent_recursive();
        return $result;
    }
}
class management_list extends sqlTable
{
    public function __construct(PDO $dbh)
    {
        $this->sql = "SELECT ID, lostID, discoveryID, changedate, changedetail FROM management";
        $this->Update($dbh);
    }
    public function AddContents(array $contents): void
    {
        foreach ($contents as $content)
            $this->datalist[] = new management($content);
    }
    public function GetContent_recursive(): array
    {
        $result = array();
        foreach ($this->GetContents() as $content)
            $result[] = $content->GetContent_recursive();
        return $result;
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
    public function Serialize(): ?string
    {
        return json_encode($this, JSON_UNESCAPED_UNICODE);
    }
    abstract function GetContent_recursive(): array;
    public function Serialize_recursive(): ?string
    {
        return json_encode($this->GetContent_recursive(), JSON_UNESCAPED_UNICODE);
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
    public function Describe(): void
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
    public function GetContent_recursive(): array
    {
        $result = $this->JsonSerialize();
        //TODO: affiliationと接続
        return $result;
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
    public function Get_user(): ?user
    {
        return $GLOBALS["userlist"]->Getcontent_by_id($this->userID);
    }
    public function Describe(): void
    {
        echo "this item's color is " . $this->color . "<br>";
        echo "this item's owners name is " . $this->Get_user()->name;
    }
    public function JsonSerialize(): array
    {
        return array(
            "ID" => $this->ID,
            "userID" => $this->userID,
            "color" => $this->color,
            "features" => $this->features,
            "category" => $this->category,
            "datetime" => $this->datetime,
            "place" => $this->place,
        );
    }
    public function GetContent_recursive(): array
    {
        $result = $this->JsonSerialize();
        $result["user"] = $this->Get_user()->GetContent_recursive();
        return $result;
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
    public function Get_user(): ?user
    {
        return $GLOBALS["userlist"]->Getcontent_by_id($this->userID);
    }
    public function Describe(): void
    {
        echo "this item's color is " . $this->color . "<br>";
        echo "this item's owners name is " . $this->Get_user()->name;
    }
    public function JsonSerialize(): array
    {
        return array(
            "ID" => $this->ID,
            "userID" => $this->userID,
            "color" => $this->color,
            "features" => $this->features,
            "category" => $this->category,
            "datetime" => $this->datetime,
            "place" => $this->place,
        );
    }
    public function GetContent_recursive(): array
    {
        $result = $this->JsonSerialize();
        $result["user"] = $this->Get_user()->GetContent_recursive();
        return $result;
    }
}
class management extends item
{
    var int $ID;
    var int $lostID;
    var int $discoveryID;
    var string $changedate;
    var string $changedetail;
    public function get_lostitem(): ?lostitem
    {
        return $GLOBALS["lostitemlist"]->Getcontent_by_id($this->lostID);
    }
    public function get_Discovery(): ?discovery
    {
        return $GLOBALS["discoverylist"]->Getcontent_by_id($this->discoveryID);
    }
    public function Describe(): void
    {
        echo "this case's ID is " . $this->ID . "<br>";
        echo "this item's lostitem's color is " . $this->get_lostitem()->color . "<br>";
    }
    public function JsonSerialize(): array
    {
        return array(
            "ID" => $this->ID,
            "lostID" => $this->lostID,
            "discoveryID" => $this->discoveryID,
            "changedate" => $this->changedate,
            "changedetail" => $this->changedetail,
        );
    }
    public function GetContent_recursive(): array
    {
        $result = $this->JsonSerialize();
        $result["lostitem"] = $this->get_lostitem()->GetContent_recursive();
        $result["discovery"] = $this->get_Discovery()->GetContent_recursive();
        return $result;
    }
}