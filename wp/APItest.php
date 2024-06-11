<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
</head>

<body>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script type="module">
        import { Post, dataType } from "./js/APIaccessor.js";
        let userlist = await Post(dataType.managementlist, false, 1);
        console.log(userlist);
    </script>
</body>

</html>