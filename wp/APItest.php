<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
</head>

<body>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script type="module">
        import { Post, dataType } from "./js/accessor.js";
        console.log(await Post(dataType.userlist, 1, 1));
        // userlist.forEach(element => {
        //     console.log(element);
        // });
    </script>
</body>

</html>