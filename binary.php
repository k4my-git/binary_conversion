<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>バイナリ</title>
    </head>
    <body>
        <?php
            print "<body bgcolor=\"#FFFFFF\" text=\"#000000\">";
            print "<h1> バイナリ変換</h1>";

            print "<form action=\"http://localhost/kadai/main.php\" method=\"post\"> \n";
            print "<input type=\"submit\" value=\"メニューへ戻る\"　size=\"100\"/>\n";
            print "</form>\n";
            print "<br>\n";

            $sinsu[0] = "2進数";
            $sinsu[1] = "10進数";
            $sinsu[2] = "16進数";
            print "変換したい数字とそれに合った進数を選択してください<br>\n";
            print "<form action=\"http://localhost/kadai/binary.php\" method=\"post\">\n";
                print"<select size=\"1\" name=\"sin\">";
                foreach($sinsu as $value){
                    print "<option value={$value}>{$value}</option>\n";
                }
                print"</select>";
                print "<input type=\"text\" name=\"num\" size = \"10\"/>\n";
                print "<br>\n";
                print "<input type=\"submit\" value=\"変換\"/>";
            print "</form>";

            try{
                $db1 = sqlite_open("db_add");
            }finally{
                return;
            }
            if($db1){
                if(($_POST["sin"]&&$_POST["num"]) != ""){
                    $sinsuu = $_POST["sin"];
                    $num = $_POST["num"];

                    //$type=gettype($num);
                    //print "{$type}";
                    if($sinsuu == "2進数"){
                        $pre = preg_match("/[0-1]+/", $num);
                        if($pre){
                            $res = True;
                        }else{
                            $res = False;
                        }
                        $sinsu2 = $num;
                        $sinsu10 = bindec($num);
                        $sinsu16 = dechex(bindec($num));
                    }else if($sinsuu == "10進数"){
                        $pre = preg_match("/[0-9]+/", $num);
                        if($pre){
                            $res = True;
                        }else{
                            $res = False;
                        }
                        $sinsu2 = decbin($num);
                        $sinsu10 = $num;
                        $sinsu16 = dechex($num);
                    }else if($sinsuu == "16進数"){
                        $pre = preg_match("/[0-9a-fA-F]+/", $num);
                        if($pre){
                            $res = True;
                        }else{
                            $res = False;
                        }
                        $sinsu2 = decbin(hexdec($num));
                        $sinsu10 = hexdec($num);
                        $sinsu16 = $num;
                    }

                    if($res == True){
                        $query = "INSERT INTO tbl_add(id2, id10, id16) 
                                VALUES({$sinsu2},{$sinsu10},'{$sinsu16}')";
                        $result = sqlite_query($db1,$query);

                        $query = "SELECT * FROM tbl_add";

                        show_recs($db1,$query);
                    }else{
                        print "入力された値が選択された進数とマッチしませんでした";
                    }

                    sqlite_close($db1);
                }

            }else{
                die("データベースとの接続がうまくいきませんでした。");
            }

            function show_recs($db,$que){
                $result = sqlite_query($db,$que);
                $num1 = sqlite_num_rows($result);
                $num2 = sqlite_num_fields($result);

                //テーブルの見出し
                print "<table border=\"2\">\n";
                print "<tr bgcolor=\"#BBBBBB\">";
                for($i=0; $i<$num2; $i++){
                    $num3[$i] = sqlite_field_name($result,$i);
                    print "<th>{$num3[$i]}</th>";
                }
                print "</tr>\n";

                //テーブルのデータセル
                while($info = sqlite_fetch_array($result)){
                    print "<tr>";
                    for($i=0; $i<$num2; $i++){
                        print "<td>{$info[$num3[$i]]}</td>";
                    }
                    print "</tr>\n";
                }
                print "</table>\n";
            }
        ?>
    </body>
</html>