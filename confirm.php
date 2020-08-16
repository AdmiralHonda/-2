<!DOCTYPE html>
<html>
    <head lang="ja">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta proparty="og:locale" content="ja_JP">
        <meta name="twitter:card" content="summary_large_image"/>
        <meta name="twitter:site" content=""/>
        <title>ご注文の確認</title>
        <!-- 必要なメタタグ -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    </head>
    <body>
        <div class="container">
        <h2>ご注文の確認</h2>
        <h3>注文内容</h3>
        <?php
         if($_POST[confirm]){
            print "<p>以下の注文内容で受け付けました。</p>";
         }
        ?>
        <table class="table">
                <thead>
                    <tr>
                    <th scope="col">メニュー</th>
                    <th scope="col">値段</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $dbconn=pg_connect("dbname='u296163' user='u296163'");
                if(!$dbconn){
                    exit("DB connection failed!");
                }
                if($_POST[confirm]){
                        $order=(int)$_POST[confirm];
                        $order_slip=pg_query($dbconn,"SELECT * FROM slip where orderid=$order;");
                        $order_user=pg_fetch_row($order_slip,0);
                        $tmp=0;
                        $order_food=pg_query($dbconn,"SELECT * FROM food_order where orderid=$order;");
                        for($i=0; $i<pg_num_rows($order_food);$i++) {
                            print "<tr>";
                            $row=pg_fetch_row($order_food, $i);
                            $food_info=pg_query($dbconn,"SELECT * FROM menu WHERE foodid=$row[1]");
                            $food_name=pg_fetch_row($food_info,0);
                            switch($food_name[3]){
                                case "box":
                                    print "<td><span class=\"badge badge-primary\">弁当</span>".$food_name[1]."</td>";
                                    break;
                                case "plate":
                                    print "<td><span class=\"badge badge-warning\">プレート</span>".$food_name[1]."</td>";
                                    break;
                                case "appetizer":
                                    print "<td><span class=\"badge badge-success\">前菜</span>".$food_name[1]."</td>";
                                    break;
                                case "onedish":
                                    print "<td><span class=\"badge badge-danger\">一品料理</span>".$food_name[1]."</td>";
                                    break;
                                case "ricenoodle":
                                    print "<td><span class=\"badge badge-info\">飯・麺</span>".$food_name[1]."</td>";
                                    break;
                                case "rice":
                                    print "<td><span class=\"badge badge-secondary\">ライス</span>".$food_name[1]."</td>";
                                    break;
                                default:
                                    print "<td>".$food_name[1]."</td>";
                            }
                            $fsum=$food_name[2]*$row[2];
                            $tmp+=$fsum;
                            print " <td>".$food_name[2]."×".$row[2]."=".$fsum."円</td>";
                            print "</tr>";
                        }
                        print "</tbody></table>";
                        $sum=$tmp*1.1;
                        print "<p>合計金額は".$tmp."×1.1=".$sum."円です。</p><br>";
                        print "<p>お名前:".$order_user[1]."<br>電話番号".$order_user[2]."<br>ご予約時間:".$order_user[3]."</p>";
                        print "念のためこのページのスクリーンショットを取ることをお勧めします。<br>";

                    }else{
                        $max_order=pg_query($dbconn,"SELECT max(orderid) FROM slip ;");
                        $max=pg_fetch_row($max_order, 0);
                        $max[0]++;
                        $today = $_POST[date];
                        $res = pg_query($dbconn,"INSERT INTO slip VALUES($max[0],'$_POST[user]','$_POST[phone]','$today')");
                        for($i=0;$i<20;$i++){
                            if($_POST[food][$i]!="0"){
                                $foodid=$i+1;
                                $num=(int)$_POST[food][$i];
                                $res = pg_query($dbconn,"INSERT INTO food_order VALUES($max[0],$foodid,$num)");
                            }
                        }
                        $query=pg_query($dbconn,"SELECT * FROM food_order WHERE orderid=$max[0]");
                        $tmp=0;
                        for($i=0; $i<pg_num_rows($query);$i++) {
                            print "<tr>";
                            $row=pg_fetch_row($query, $i);
                            $food_info=pg_query($dbconn,"SELECT * FROM menu WHERE foodid=$row[1]");
                            $food_name=pg_fetch_row($food_info,0);
                            switch($food_name[3]){
                                case "box":
                                    print "<td><span class=\"badge badge-primary\">弁当</span>".$food_name[1]."</td>";
                                    break;
                                case "plate":
                                    print "<td><span class=\"badge badge-warning\">プレート</span>".$food_name[1]."</td>";
                                    break;
                                case "appetizer":
                                    print "<td><span class=\"badge badge-success\">前菜</span>".$food_name[1]."</td>";
                                    break;
                                case "onedish":
                                    print "<td><span class=\"badge badge-danger\">一品料理</span>".$food_name[1]."</td>";
                                    break;
                                case "ricenoodle":
                                    print "<td><span class=\"badge badge-info\">飯・麺</span>".$food_name[1]."</td>";
                                    break;
                                case "rice":
                                    print "<td><span class=\"badge badge-secondary\">ライス</span>".$food_name[1]."</td>";
                                    break;
                                default:
                                    print "<td>".$food_name[1]."</td>";
                            }
                            $fsum=$food_name[2]*$row[2];
                            $tmp+=$fsum;
                            print " <td>".$food_name[2]."×".$row[2]."=".$fsum."円</td>";
                            print "</tr>";
                        }
                        print "</tbody></table>";
                        $sum=$tmp*1.1;
                        print "<p>合計金額は".$tmp."×1.1=".$sum."円です。</p><br>";
                        print "<form method=\"POST\" action=\"http://db.cse.ce.nihon-u.ac.jp/~u296163/index.php\">
                            <button class=\"btn btn-dark\" type=\"submit\" name=\"reset\" value=\"".$max[0]."\">注文内容の修正</button>
                        </form><br>
                        <form method=\"POST\" action=\"http://db.cse.ce.nihon-u.ac.jp/~u296163/confirm.php\">
                            <button class=\"btn btn-success\" type=\"submit\" name=\"confirm\" value=\"".$max[0]."\">注文の確認</button>
                        </form>";
                    }
                    ?>
                    
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    </body>
</html>