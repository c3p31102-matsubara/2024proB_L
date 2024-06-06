<?php
class sqlArray
{
    private $sql;
    private $datalist = array();
    function setSQL($arg): void
    {
        $this->sql = $arg;
    }
    function getList($dbh): PDOStatement
    {
        $dsn_query = $dbh->query($this->sql);
        $dsn_query->setFetchMode(PDO::FETCH_ASSOC);
        return $dsn_query;
    }
    function getText($dbh): string
    {
        $result = "";
        foreach ($this->getList($dbh) as $content) {
            foreach ($content as $data)
                $result .= $data . " ";
        }
        return $result;
    }
    function AddContent(...$contents): void
    {
        foreach ($contents as $content)
            $this->datalist[] = $content;
    }
    function GetContent(): array
    {
        return $this->datalist;
    }
    function getJson(): string
    {
        $result = json_encode($this->datalist);
        return $result;
    }
}

class user_list extends sqlArray
{
    function __construct($dbh)
    {
        // $sql = "SELECT user.ID, attribute, affiliationID, EmailAddress, number, telephone FROM user;";
        $sql = "SELECT user.ID, attribute, faculty, EmailAddress, number, telephone FROM user JOIN affiliation ON user.affiliationID=affiliation.ID;";
        $this->setSQL($sql);
        $this->AddContent($this->getList($dbh));
    }
    function get_user_by_id($dbh, $id): user | null
    {
        foreach($this->getList($dbh) as $user)
        {
            if ($user->ID == $id)
            return $user;
        };
    }
}
class user
{
    var $ID;
    var $attribute;
    var $number;
    var $affiliationID;
    var $faculty;
    var $EmailAddress;
    var $telephone;
    var $name;
    function __construct($arg)
    {
        foreach ($arg as $key => $value)
            if (!is_numeric($key))
                $this->$key = $value;
    }
}
class lostitem_list extends sqlArray
{
    function __construct($dbh)
    {
        // $sql = "SELECT lost.ID, color, features, category, datetime, place, name FROM lost JOIN user ON lost.userID=user.ID;";
        $sql = "SELECT lost.ID, color, features, category, datetime, place, userID FROM lost";
        $this->setSQL($sql);
        $this->AddContent($this->getList($dbh));
    }
}
class lostitem
{
    var $id;
    var $color;
    var $features;
    var $category;
    var $datetime;
    var $place;
    var $userID;
    var $user;
    function __construct($arg, $user)
    {
        foreach ($arg as $key => $value)
            if (!is_numeric($key))
                $this->$key = $value;
        if (get_class($user) == "user")
            $this->$user = $user;
    }
    function set_user($user): void
    {
        $this->user = $user;
    }
    function set_user2($users): void
    {
        foreach($users as $user)
            if ($this->userID == $user->ID)
                $this->user = $user;
    }
}