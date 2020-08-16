<!DOCTYPE html>
<html>
    <head lang="ja">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta proparty="og:locale" content="ja_JP">
        <meta name="twitter:card" content="summary_large_image"/>
        <meta name="twitter:site" content=""/>
        <title>ご注文の一覧</title>
        <!-- 必要なメタタグ -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    </head>
    <body>
        <div class="container">
        <?php
         $dbconn=pg_connect("dbname='u296163' user='u296163'");
         if(!$dbconn){
             exit("DB connection failed!");
         }
         if($_POST[del]){
            $delid=(int)$_POST[del];
            $del=pg_query($dbconn,"DELETE FROM slip where orderid=$delid");
         }
         $order_slip=pg_query($dbconn,"SELECT * FROM slip");
         for($i=0; $i<pg_num_rows($order_slip);$i++) {
            $row=pg_fetch_row($order_slip, $i);
            print "<table class=\"table\">
            <thead>
            <tr>
            <th scope=\"col\">名前</th>
            <th scope=\"col\">電話番号</th>
            <th scope=\"col\">受け取り日時</th>
            </tr>
            </thead>
            <tbody>
            <tr>
            <th>".$row[1]."</th>
            <th>".$row[2]."</th>
            <th>".$row[3]."</th>
            </tr>";
                print "<tr>
                <th scope=\"col\">メニュー</th>
                <th scope=\"col\">個数</th>
                </tr>";
            $order_food=pg_query($dbconn,"SELECT * FROM food_order where orderid=$row[0]");
            for($j=0; $j<pg_num_rows($order_food); $j++) {
                $food=pg_fetch_row($order_food, $j);
                print_r($j);
                print "<tr>";
                print_r($food);
                $food_info=pg_query($dbconn,"SELECT * FROM menu WHERE foodid=$food[1]");
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
                print "<td>".$food[2]."</td></tr>";
            }
            print "</tbody></table>";
            print "<form method=\"POST\" action=\"http://db.cse.ce.nihon-u.ac.jp/~u296163/manage.php\">
                <input type=\"hidden\" name=\"del\" value=\"".$row[0]."\">
                <button class=\"btn btn-success\" type=\"submit\">決済完了</button>
                </form>";
         }
        ?>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    </body>
</html>