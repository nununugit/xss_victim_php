<?php
        //MySQLサーバへの接続とデータベースの選択
        $dsn='mysql:dbname=kadai;host=localhost;charset=utf8';
        $user='root';
        $password= '';
        try{
            $dbh =new PDO($dsn,$user,$password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT * FROM users_datas";
            $stmt=$dbh->prepare($sql);
            $stmt->execute();
            $count = $stmt->rowCount();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $data[]=$row;
            }
        }catch(PDOException $e){
            print ($e->getMessage());
            die();
        }
            //テーブルへの登録
        if(isset($_POST['user_name'])){
            $name = @$_POST['user_name'];
            $pass = @$_POST['user_pass'];
            if (empty($name)||empty($pass)){
                echo "<br>";
                echo '<strong>文字を入力してください</strong>';
            }else{
            $stmt = $dbh->prepare("SELECT * FROM users_datas WHERE user_name = :name AND user_pass=:pass;");
            $stmt->execute([':name' => $name,':pass'=> $pass]);
            $row = $stmt->fetch();
            if($row){
                session_start();
                $token_uid = sha1($row['uid']);
                $token_username = sha1($row['user_name']);
                $_SESSION['profile']=array('user_id'=>$row['uid'],'user_name'=>$row['user_name']);
                header('Location: ./sns_timeline.php');
            }else{
                echo "ログイン失敗";
            }
           
        }
    }
        ?>
    

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>ひとこと掲示板login</title>
    </head>
        <body>
            <div class = "container">
            <div class="jumbotron mt-4">
            <h2><b>ログイン</b></h2>
          <br>
          <form action="sns_login.php" method="post">
            <div class="form-group">
              <label for="exampleInputName"><b>ユーザ名</b></label>
              <input type="text" name="user_name" class="form-control" id="exampleInputEmail1">
            </div>

            <div class="form-group">
              <label for="exampleInputPass"><b>パスワード</b></label>
              <input type="password" name="user_pass" class="form-control" id="exampleInputPassword">
            </div>

            <div class="form-group form-check"></div>
            <input type="submit" name='send' value="ログイン" class="btn btn-outline-dark btn-primary btn-block">
    <a href="./sns_user_registration.php">登録はこちら</a>
          </form>
          </div>
            </div>

 <!-- Bootstrap Javascript(jQuery含む) -->
 <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
 <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
 <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

        </body>
    </html>