<?php
class sqlClass
{
    private $sql;
    var $dlist = array();
    function setSQL($arg): void
    {
        $this->sql = $arg;
    }
    function getList($dsn): PDOStatement
    {
        $dsn_query = $dsn->query($this->sql);
        $dsn_query->setFetchMode(PDO::FETCH_ASSOC);
        return $dsn_query;
    }
    function getText($dsn): string
    {
        $result = "";
        foreach ($this->getList($dsn) as $content) {
            foreach ($content as $data)
                $result .= $data . " ";
        }
        return $result;
    }
}

class user_list extends sqlClass
{
    function __construct($dsn)
    {
        $sql = "SELECT ユーザ.ユーザID, ユーザ.属性, ユーザ.学籍番号 FROM ユーザ;";
        $this->setSQL($sql);
        $arr = $this->getList($dsn);
        foreach ($arr as $row)
            $this->dlist[] = new user($row);
    }
}
class user
{
    var $ユーザID;
    var $属性;
    var $学籍番号;
    function __construct($arg)
    {
        foreach ($arg as $i => $v)
            if (!is_numeric($i))
                $this->$i = $v;
    }
    function describe(): string
    {
        return $this->学籍番号;
    }
}