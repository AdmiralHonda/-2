<?php
            $dbconn=pg_connect("dbname='u296163' user='u296163'");
            
            if(!$dbconn){
                exit("DB connection failed!");
            }
            $query = pg_query($dbconn,"SELECT * FROM menu");
            if($_POST[reset]){
                $reset_id=(int)$_POST[reset];
                $reset = pg_query($dbconn,"DELETE FROM slip WHERE orderid=$reset_id");
            }
            $date=date("Y-m-d\TH:i");
?> 
<!DOCTYPE html>
<html>
    <head lang="ja">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta proparty="og:locale" content="ja_JP">
        <meta name="twitter:card" content="summary_large_image"/>
        <meta name="twitter:site" content=""/>
        <!-- 必要なメタタグ -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <style>
        select {
            -webkit-appearance: none;/* ベンダープレフィックス(Google Chrome、Safari用) */
            -moz-appearance: none; /* ベンダープレフィックス(Firefox用) */
            appearance: none; /* 標準のスタイルを無効にする */
            }
        </style>
    </head>
    <body>
        <div class="container">
        <h2>中村園<h4>テイクアウト始めました。</h4></h2>
        <h5>個数を入力してください</h5>
        
            <form class="form-group" id="form" method="POST" action="http://db.cse.ce.nihon-u.ac.jp/~u296163/confirm.php">
            <table class="table" style="font-size:0.75em;">
                <thead>
                    <tr>
                    <th scope="col">メニュー</th>
                    <th scope="col">値段(税別)</th>
                    <th scope="col">注文数</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    for($i=0; $i<pg_num_rows($query);$i++) {
                        print "<tr>";
                        $row=pg_fetch_row($query, $i);
                        switch($row[3]){
                            case "box":
                                print "<td><span class=\"badge badge-primary\">弁当</span>".$row[1]."</td>";
                                break;
                            case "plate":
                                print "<td><span class=\"badge badge-warning\">プレート</span>".$row[1]."</td>";
                                break;
                            case "appetizer":
                                print "<td><span class=\"badge badge-success\">前菜</span>".$row[1]."</td>";
                                break;
                            case "onedish":
                                print "<td><span class=\"badge badge-danger\">一品料理</span>".$row[1]."</td>";
                                break;
                            case "ricenoodle":
                                print "<td><span class=\"badge badge-info\">飯・麺</span>".$row[1]."</td>";
                                break;
                            case "rice":
                                print "<td><span class=\"badge badge-secondary\">ライス</span>".$row[1]."</td>";
                                break;
                            default:
                                print "<td>".$row[1]."</td>";
                        }
                        
                        print " <td>".$row[2]."円</td>";
                        print "<td><select class=\"form-control\" name=\"food[]\">";
                        print "<option value=\"0\" selected>0</option>";
                        for($k=1 ;$k<21; $k++){
                            print "<option value=".$k.">".$k."</option>";
                        }
                        print "</select></td>";
                        print "</tr>";
                    }
                    ?>
                </tbody>
            </table>
            
            <label>電話番号（必須）</label>
            <input type="tel" class="form-control" name="phone" placeholder="例)08043237536" required/>
            <label>名前（必須）</label>
            <input type="text" class="form-control" name="user" placeholder="例)日大太郎" maxlength="32" required/>
            <label>受け取り日時(必須)</label>
            <input type="datetime-local" form="form" class="form-control" name="date" value="<?php print_r($date); ?>" required/>
            <br>
            <br>
            <center>
            <input type="submit" class="btn btn-danger btn-lg" value="ご注文の確認">
            </center>
            </form>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    </body>
</html>