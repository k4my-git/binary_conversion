<!DOCTYPE>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>メニュー</title>
    </head>
    <body>
        <?php
            print "<body bgcolor=\"#FFFFFF\" text=\"#000000\">";
            print "<h1> バイナリコンバーター</h1>";

            print "<form action=\"http://localhost/kadai/binary.php\" method=\"post\"> \n";
            print "<input type=\"submit\" value=\"バイナリ変換\"　size=\"100\"/>\n";
            print "</form>\n";

            print "<form action=\"http://localhost/kadai/math.php\" method=\"post\"> \n";
            print "<input type=\"submit\" value=\"四則演算\"　size=\"100\" />\n";
            print "</form>\n";

            if($db1 = sqlite_open("db_add")){
                $query = "CREATE TABLE tbl_add(
                    id2 INTEGER, id10 INTEGER, id16 VARCHAR(20)
                )";
                $result = sqlite_query($db1,$query);
                sqlite_close($db1);
            }else{
                die("データベースとの接続がうまくいきませんでした。");
            }


            if($db2 = sqlite_open("db_ans")){
                $query = "CREATE TABLE tbl_ans(
                    id2 INTEGER, id10 INTEGER, id16 VARCHAR(20)
                )";
                $result = sqlite_query($db2,$query);
                sqlite_close($db2);
            }else{
                die("データベースとの接続がうまくいきませんでした。");
            }
        ?>
    </body>
</html>