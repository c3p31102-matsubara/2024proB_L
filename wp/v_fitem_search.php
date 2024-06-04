<!doctype html>
<html lang="ja">
<head>
<?php include("./html/meta.htm"); ?>
  <title>拾得物管理システム</title>
</head>

<body>
  <?php include("./html/header.htm"); ?>
  
  <div class="container">
    <div class="row">
      <div class="col mt-3">
        <p class="h2 p-1  bg-success w-50 rounded text-center text-white">拾得物検索</p>
        <p class="bg-light p-1 border m-0">拾得物検索機能では，文教大学湘南キャンパスの拾得物について，届けられているものを検索する機能です．検索キーワードから物品名を入力して検索してください．検索結果の件数と一覧が表形式で一覧表示されます．詳細を確認するには，詳細を選択してください．</p>
        <hr>
        物品名
        <input type="text" name="key" id="key">
        <button class="btn btn-success" onclick="get_fitem('r','key')">検索</button>
      </div>
    </div>

    <div class="row mt-3">
      <div class="col">
        <span id="r"></span>
      </div>
    </div>

  </div>
  <?php include("./html/footer.htm"); ?>
  <script src="./js/main.js"></script>
</body>
</html>
