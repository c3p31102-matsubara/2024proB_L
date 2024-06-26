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
        header('Content-Type: text/plain; charset=UTF-8', true, 500);
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
    protected $columns;
    protected $table_name;
    public function Update(PDO $dbh): void
    {
        $this->datalist = array();
        $dsn_query = $dbh->query($this->sql_Select());
        $dsn_query->setFetchMode(PDO::FETCH_ASSOC);
        $this->AddContents($dsn_query->fetchAll());
    }
    public function sql_Select(): string
    {
        return "SELECT " . $this->columns . " FROM " . $this->table_name;
    }
    public function sql_insert(): string
    {
        return "INSERT INTO " . $this->table_name . " (" . implode(',', array_slice(explode(',', $this->columns), 1)) . ") VALUES (" . str_repeat("?,", substr_count($this->columns, ",") - 1) . "?);";
    }
    abstract function AddContents(array $contents): void;
    public function GetContents(): array
    {
        return $this->datalist;
    }
    public function GetContent_by_id(int $target): mixed
    {
        foreach ($this->datalist as $content)
            if ($content->ID == $target)
                return $content;
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
        $this->columns = "ID, attribute, number, affiliationID, EmailAddress, telephone, name";
        $this->table_name = "user";
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
        $this->columns = "ID, userID, color, features, category, datetime, place";
        $this->table_name = "lost";
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
        $this->columns = "ID, userID, color, features, category, datetime, place";
        $this->table_name = "discovery";
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
        $this->columns = "ID, lostID, discoveryID, changedate, changedetail";
        $this->table_name = "management";
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
class affiliation_list extends sqlTable
{
    public function __construct(PDO $dbh)
    {
        $this->columns = "ID, Faculty, department";
        $this->table_name = "affiliation";
        $this->Update($dbh);
    }
    public function AddContents(array $contents): void
    {
        foreach ($contents as $content)
            $this->datalist[] = new affiliation($content);
    }
    public function GetContent_recursive(): array
    {
        return $this->GetContents();
    }
}
abstract class item implements JsonSerializable
{
    var int $ID;
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
    public function Get_affiliation(): affiliation
    {
        return $GLOBALS["affiliationlist"]->GetContent_by_id($this->affiliationID);
    }
    public function JsonSerialize(): array
    {
        return array(
            "ID" => $this->ID,
            "attribute" => $this->attribute,
            "number" => $this->number,
            "affiliationID" => $this->affiliationID,
            "emailAddress" => $this->EmailAddress,
            "telephone" => $this->telephone,
            "name" => $this->name
        );
    }
    public function GetContent_recursive(): array
    {
        $result = $this->JsonSerialize();
        $result["affiliation"] = $this->Get_affiliation();
        unset($result["affiliationID"]);
        return $result;
    }
}
class lostitem extends item
{
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
        return $GLOBALS["userlist"]->GetContent_by_id($this->userID);
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
        unset($result["userID"]);
        $result["user"] = $this->Get_user()->GetContent_recursive();
        return $result;
    }
}
class discovery extends item
{
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
        return $GLOBALS["userlist"]->GetContent_by_id($this->userID);
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
        unset($result["userID"]);
        $result["user"] = $this->Get_user()->GetContent_recursive();
        return $result;
    }
}
class management extends item
{
    var int $lostID;
    var int $discoveryID;
    var string $changedate;
    var string $changedetail;
    public function get_lostitem(): ?lostitem
    {
        return $GLOBALS["lostitemlist"]->GetContent_by_id($this->lostID);
    }
    public function get_Discovery(): ?discovery
    {
        return $GLOBALS["discoverylist"]->GetContent_by_id($this->discoveryID);
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
        unset($result["lostID"]);
        unset($result["discoveryID"]);
        $result["lostitem"] = $this->get_lostitem()->GetContent_recursive();
        $result["discovery"] = $this->get_Discovery()->GetContent_recursive();
        return $result;
    }
}
class affiliation extends item
{
    var $Faculty;
    var $department;
    public function JsonSerialize(): array
    {
        return array(
            "ID" => $this->ID,
            "faculty" => $this->Faculty,
            "department" => $this->department
        );
    }
    public function GetContent_recursive(): array
    {
        $result = $this->JsonSerialize();
        return $result;
    }
}