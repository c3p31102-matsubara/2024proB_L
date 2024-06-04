<?php

//継承用のデータリストを保持するクラス
class data_list
{
  private $sql;
  var $dlist = array();
  function setSQL($arg)
  {
    $this->sql = $arg;
  }
  function getList()
  {
    $dsn = "mysql:dbname=probc2024;host=localhost";
    $my = new PDO($dsn, 'probc2024', 'probc2024');
    $arr = $my->query($this->sql);
    return ($arr);
  }
  function getJson()
  {
    $j = json_encode($this->dlist);
    return ($j);
  }
}

//拾得物リストの管理用クラス
class find_item_list extends data_list
{
  function __construct()
  {
    $sql = "SELECT 拾得物分類.大分類, 拾得物分類.物品名, 拾得物分類.貴重品, ";
    $sql .= "拾得物.ID AS 拾得物ID, 拾得物.拾得場所, 拾得物.色, 拾得物.特徴, 拾得物.画像, ";
    $sql .= "拾得物管理状況.ID AS 拾得物管理状況ID, 拾得物管理状況.変更日時, 拾得物管理状況.変更内容 ";
    $sql .= "FROM 拾得物管理状況 INNER JOIN ";
    $sql .= "(SELECT 拾得物ID,MAX(変更日時) AS 日時 FROM 拾得物管理状況 GROUP BY 拾得物ID) AS 最終更新 ";
    $sql .= "ON 拾得物管理状況.拾得物ID=最終更新.拾得物ID AND 拾得物管理状況.変更日時=最終更新.日時 ";
    $sql .= "INNER JOIN ユーザ ON 拾得物管理状況.ユーザID=ユーザ.ID ";
    $sql .= "INNER JOIN 所属 ON ユーザ.所属ID=所属.ID ";
    $sql .= "INNER JOIN 拾得物 ON 拾得物管理状況.拾得物ID=拾得物.ID ";
    $sql .= "INNER JOIN 拾得物分類 ON 拾得物.拾得物分類ID=拾得物分類.ID ";
    $sql .= "ORDER BY 拾得物管理状況.変更日時 ASC;";
    $this->setSQL($sql);
    $arr = $this->getList();
    foreach ($arr as $row)
      $this->dlist[] = new item($row);
  }
}

//拾得物の詳細情報保持用クラス
class find_item_detail extends data_list
{
  var $拾得物 = array();
  var $変更履歴 = array();
  function __construct($fid)
  {
    $sql = "SELECT 拾得物分類.大分類, 拾得物分類.物品名, ";
    $sql .= "拾得物.ID, 拾得物.拾得場所, 拾得物.色, 拾得物.特徴, 拾得物.画像  ";
    $sql .= "FROM 拾得物 ";
    $sql .= "INNER JOIN 拾得物分類 ON 拾得物.拾得物分類ID=拾得物分類.ID ";
    $sql .= "WHERE 拾得物.ID={$fid};";
    $this->setSQL($sql);
    $arr = $this->getList();
    foreach ($arr as $row)
      $this->拾得物[] = new item($row);
    $sql = "SELECT 拾得物管理状況.変更日時, 拾得物管理状況.変更内容, ユーザ.氏名 ";
    $sql .= "FROM 拾得物管理状況 ";
    $sql .= "INNER JOIN ユーザ ON 拾得物管理状況.ユーザID=ユーザ.ID ";
    $sql .= "WHERE 拾得物管理状況.拾得物ID={$fid} ";
    $sql .= "ORDER BY 拾得物管理状況.変更日時 ASC;";
    $this->setSQL($sql);
    $arr = $this->getList();
    foreach ($arr as $row)
      $this->変更履歴[] = new item($row);
    unset($this->dlist);
  }
  function getJson()
  {
    $j = json_encode($this);
    return ($j);
  }
}


class user_list extends data_list
{
  function __construct()
  {
    $sql = "SELECT ユーザ.ID AS ユーザID, ユーザ.氏名, ユーザ.電話番号, ユーザ.メールアドレス, ";
    $sql .= "所属.ID AS 所属ID, 所属.所属分類, 所属.所属名 ";
    $sql .= "FROM ユーザ INNER JOIN 所属 ON ユーザ.所属ID=所属.ID ";
    $sql .= "ORDER BY ユーザID ASC;";
    $this->setSQL($sql);
    $arr = $this->getList();
    foreach ($arr as $row)
      $this->dlist[] = new item($row);
  }
}

class category_list extends data_list
{
  function __construct()
  {
    $sql = "SELECT 拾得物分類.ID AS 拾得物分類ID, 大分類, 物品名, 五十音, 貴重品 ";
    $sql .= "FROM 拾得物分類 ";
    $sql .= "ORDER BY 拾得物分類ID;";
    $this->setSQL($sql);
    $arr = $this->getList();
    foreach ($arr as $row)
      $this->dlist[] = new item($row);
  }
}

class item
{
  function __construct($arg)
  {
    foreach ($arg as $i => $v) {
      if (!is_numeric($i))
        $this->$i = $v;
    }
  }
}
?>