<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>拾得物管理システム</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  <link rel="stylesheet" href="css/modisear_style.css">
  <link rel="stylesheet" href="css/ProB_style.css">
</head>

<body>
  <header>
  </header>
  <div class="container">
    <div class="header">
      登録完了
    </div>
    <div class="message">
      ボタンを押してtop画面に戻ってください
    </div>
    <div>
      <a href="top.html">
        <button type="submit" class="btn">戻る</button>
      </a>
    </div>
    <div class="footer">
      &copy; 2024,Team J,All rights reserved.　
    </div>
  </div>
  <footer>
    文教大学 情報学部 情報システム学科 プロジェクト演習BC
    <p>
      Copyright &copy; 2024,Team J,All rights reserved.
    </p>
  </footer>
  <script>
    const form_name = '<?php echo $_POST["name"]?>'
    const form_affiliation = '<?php echo $_POST["affiliation"]?>'
    const form_email = '<?php echo $_POST["email"]?>'
    const form_telephone = '<?php echo $_POST["telephone"]?>'
  </script>
  <script src="js/headerLoader.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
    crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script type="module" src="js/register-insert.js"></script>
</body>

</html>