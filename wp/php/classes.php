<?php
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
        $sql = "SELECT user.ID, attribute, affiliationID, EmailAddress, number, telephone, name FROM user;";
        $this->sql = $sql;
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
        $sql = "SELECT ID, userID, color, features, category, datetime, place FROM lost";
        $this->sql = $sql;
        $array = $this->getList($dbh);
        $this->Addcontents($array->fetchAll());
    }
    public function AddContents($contents): void
    {
        foreach ($contents as $content)
            $this->datalist[] = new lostitem($content);
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
enum userType
{
    case student;
    case teacher;
}
class user extends item
{
    var int $ID;
    var string $attribute;
    var string $number;
    var int $affiliationID;
    var int $faculty;
    var string $EmailAddress;
    var string $telephone;
    var string $name;
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
    public function Get_user_by_id(user_list $userlist): user
    {
        return $userlist->Get_user_by_id($this->userID);
    }
    public function describe(user_list $userlist): void
    {
        echo "this item's color is " . $this->color. "<br>";
        echo "this item's owners name is " . $this->Get_user_by_id($userlist)->name;
    }
}