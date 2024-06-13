<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
</head>

<body>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script type="module">
        import { getjson, insert, dataType } from "./js/APIaccessor.js";
        let userlist = await getjson(dataType.managementlist, true, 1);
        console.log(userlist);
        let inserttest = await insert(dataType.userlist, [
            "3", "student", "c3p31102", "1", "c3p31102@bunkyo.ac.jp", "08013206297", "yuki"
        ]);
        console.log(inserttest);
    </script>
</body>

</html>