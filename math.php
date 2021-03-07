<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>四則演算</title>
    </head>
    <body>
        <?php
            print "<body bgcolor=\"#FFFFFF\" text=\"#000000\">";
            print "<h1> 四則演算</h1>";

            print "<form action=\"http://localhost/kadai/main.php\" method=\"post\"> \n";
            print "<input type=\"submit\" value=\"メニューへ戻る\"　size=\"100\"/>\n";
            print "</form>\n";
            print "<br>\n";

            $sinsu[0] = "2進数";
            $sinsu[1] = "10進数";
            $sinsu[2] = "16進数";

            $fugou[0] = "+";
            $fugou[1] = "-";
            $fugou[2] = "×";
            $fugou[3] = "÷";
            print "<form action=\"http://localhost/kadai/math.php\" method=\"post\">\n";
                print"<select size=\"1\" name=\"sinsu1\">";
                foreach($sinsu as $value){
                    print "<option value={$value}>{$value}</option>\n";
                }
                print"</select>";
                print "<input type=\"text\" name=\"num1\" size = \"10\"/>\n";
                print "<br><br>\n";

                foreach($fugou as $id => $value){
                    print "<input type=\"radio\" name=\"fugou\" value=\"{$value}\" ";
                    if($id == 0){
                        print "checked";
                    }
                    print "/>";
                    print "{$value} \n";
                }
                print "<br><br>\n";

                print"<select size=\"1\" name=\"sinsu2\">";
                foreach($sinsu as $value){
                    print "<option value={$value}>{$value}</option>\n";
                }
                print"</select>";
                print "<input type=\"text\" name=\"num2\" size = \"10\"/>\n";
                print "<br><br>\n";

                print "<input type=\"submit\" value=\"計算\"/>";
            print "</form>";

            try{
                $db1 = sqlite_open("db_ans");
            }finally{
                return;
            }
            if($db1){
                if(($_POST["sinsu1"]&&$_POST["num1"]) != ""){
                    $sinsu1 = $_POST["sinsu1"];
                    $num1 = $_POST["num1"];
                    $sinsu2 = $_POST["sinsu2"];
                    $num2 = $_POST["num2"];
                    $fugou = $_POST["fugou"];

                    $res1 = check($sinsu1,$num1);
                    $res2 = check($sinsu2,$num2);
                    //print "{$res1}{$res2}";
                    if(($res1&&$res2) == True){
                        if($fugou == "+"){
                            $sum = convert($sinsu1,"10進数",$num1) + convert($sinsu2,"10進数",$num2);
                        }else if($fugou == "-"){
                            $sum = convert($sinsu1,"10進数",$num1) - convert($sinsu2,"10進数",$num2);
                        }else if($fugou == "×"){
                            $sum = convert($sinsu1,"10進数",$num1) * convert($sinsu2,"10進数",$num2);
                        }else if($fugou == "÷"){
                            $sum = convert($sinsu1,"10進数",$num1) / convert($sinsu2,"10進数",$num2);
                        }else{
                            print "error";
                        }
    
                        $sinsu2 = convert("10進数","2進数",$sum);
                        $sinsu10 = convert("10進数","10進数",$sum);
                        $sinsu16 = convert("10進数","16進数",$sum);
    
                        $query = "INSERT INTO tbl_ans(id2, id10, id16) 
                                    VALUES('{$sinsu2}','{$sinsu10}','{$sinsu16}')";
                        $result = sqlite_query($db1,$query);
    
                        $query = "SELECT * FROM tbl_ans";
    
                        show_recs($db1,$query);
                    }else{
                        print "入力された値が選択された進数とマッチしませんでした";
                    }
                    sqlite_close($db1);
                }

            }else{
                die("データベースとの接続がうまくいきませんでした。");
            }


            function check($sinsu,$num){
                if($sinsu == "2進数"){
                    $pre = preg_match("/[0-1]+/", $num);
                    if($pre){
                        $res = True;
                    }else{
                        $res = False;
                    }
                }else if($sinsu == "10進数"){
                    $pre = preg_match("/[0-9]+/", $num);
                    if($pre){
                        $res = True;
                    }else{
                        $res = False;
                    }
                }else if($sinsu == "16進数"){
                    $pre = preg_match("/[0-9a-fA-F]+/", $num);
                    if($pre){
                        $res = True;
                    }else{
                        $res = False;
                    }
                }
                return($res);
            }

            function convert($sinsuu1,$sinsuu2,$num){
                if($sinsuu1 == "2進数"){
                    if($sinsuu2 == "2進数"){
                        $convert = $num;
                    }else if($sinsuu2 == "10進数"){
                        $convert = bindec($num);
                    }else if($sinsuu2 == "16進数"){
                        $convert = dechex(bindec($num));
                    }
                }else if($sinsuu1 == "10進数"){
                    if($sinsuu2 == "2進数"){
                        $convert = decbin($num);
                    }else if($sinsuu2 == "10進数"){
                        $convert = $num;
                    }else if($sinsuu2 == "16進数"){
                        $convert = dechex($num);
                    }
                }else if($sinsuu1 == "16進数"){
                    if($sinsuu2 == "2進数"){
                        $convert = decbin(hexdec($num));
                    }else if($sinsuu2 == "10進数"){
                        $convert = hexdec($num);
                    }else if($sinsuu2 == "16進数"){
                        $convert = $num;
                    }
                }
                return $convert;
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