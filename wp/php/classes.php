<?php
class user_list
{
    private $datalist = array();
    var $sql;
    public function __construct($dbh)
    {
        // $sql = "SELECT user.ID, attribute, affiliationID, EmailAddress, number, telephone FROM user;";
        $sql = "SELECT user.ID, attribute, affiliationID, EmailAddress, number, telephone, name FROM user;";
        $this->sql = $sql;
        $array = $this->getList($dbh);
        $this->AddContent($array->fetchAll());
    }
    public function getList($dbh): PDOStatement
    {
        $dsn_query = $dbh->query($this->sql);
        $dsn_query->setFetchMode(PDO::FETCH_ASSOC);
        return $dsn_query;
    }
    public function AddContent($contents): void
    {
        foreach ($contents as $content)
            $this->datalist[] = new user($content);
    }
    public function GetContents(): array
    {
        return $this->datalist;
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
    public function __construct($args)
    {
        foreach ($args as $key => $value)
            if (!is_numeric($key))
                $this->$key = $value;
    }
    public function sayhello(): void
    {
        echo "my name is " . $this->name;
    }
}