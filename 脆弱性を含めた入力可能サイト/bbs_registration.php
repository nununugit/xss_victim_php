<?php
        //MySQLサーバへの接続とデータベースの選択
        $dsn='mysql:dbname=kadai;host=localhost;charset=utf8';
        $user='root';
        $password= '';
        try{
            $dbh =new PDO($dsn,$user,$password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql =  "SELECT * FROM users_datas";
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
        if(isset($_POST['regist_name'])){
            $name = @$_POST['regist_name'];
            $pass1 = @$_POST['user_pass1'];
            $pass2 = @$_POST['user_pass2'];
            if (empty($name)||empty($pass1)||empty($pass2)){
                echo "<br>";
                echo '<strong>文字を入力してください</strong>';
                
            }
            if($pass1 != $pass2){
                echo '設定したパスワードが再入力と違います';
            }else if($pass1==$pass2){
                $stmt = $dbh->prepare("SELECT * FROM users_datas WHERE user_name =:name;");
                $stmt->execute([':name' => $name]);
                $row = $stmt->fetch();
                if($row){
                    echo "ユーザは既に存在します。";
                }else{
                    $sql = "INSERT INTO users_datas VALUES('', '$name', '$pass1');";
                    $result = $dbh ->query($sql);
                    echo $userid;
                    date_default_timezone_set('Asia/Tokyo');
                    $timestamp = time() ;
                    $stmt = $dbh->prepare("SELECT * FROM users_datas WHERE user_name =:name;");
                    $stmt->execute([':name' => $name]);
                    $row = $stmt->fetch();
                    $userid = $row['uid'];
                    $now= date( "Y/m/d H:i:s", $timestamp );
                    $sql = "INSERT INTO todolist VALUES('', 'firstcomment','Hello World!!' ,'$userid',0,'$now' ,0);";
                    $result = $dbh ->query($sql);
                    header('Location: ./bbs_login.php');
                }
            }
        }?>
    
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>ひとこと掲示板login</title>
    </head>
        <body>
        <div class="jumbotron mt-4">
            <h2><b>ログイン</b></h2>
          <br>
          <form action="bbs_registration.php" method="post">
            <div class="form-group">
              <label for="exampleInputName"><b>ユーザ名</b></label>
              <input type="text" name="regist_name" class="form-control" id="exampleInputEmail1">
            </div>

            <div class="form-group">
              <label for="exampleInputPass1"><b>パスワード</b></label>
              <input type="password" name="user_pass1" class="form-control" id="exampleInputPassword1">
            </div>

            <div class="form-group">
              <label for="exampleInputPass2"><b>パスワード(再入力)</b></label>
              <input type="password" name="user_pass2" class="form-control" id="exampleInputPassword2">
            </div>

            <div class="form-group form-check"></div>
            <input type="submit" value="登録" class="btn btn-outline-dark bg-warning btn-block">
            <small class="text-muted">
            続行することで、当社の利用規約および<span class='bluetext'>プライバシー</span>規約に同意するものとみなされます。
            </small>
          </form>
          </div>
    <a href="./bbs_login.php">ログインはこちら</a>    
 <!-- Bootstrap Javascript(jQuery含む) -->
 <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
 <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
 <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        </body>
    </html>