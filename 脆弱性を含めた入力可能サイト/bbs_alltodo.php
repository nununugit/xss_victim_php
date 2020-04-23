<?php 
session_start();
$session_user = $_SESSION['profile']['user_name'];
$session_uid = $_SESSION['profile']['user_id'];
echo "<h1>ようこそ".$_SESSION['profile']['user_name']."さん</h1>";
        //MySQLサーバへの接続とデータベースの選択
        $dsn='mysql:dbname=kadai;host=localhost;charset=utf8';
        $user='root';
        $password= '';
        try{
            $dbh =new PDO($dsn,$user,$password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT T.todo_title, T.todo_value ,U.user_name FROM todolist T JOIN users_datas U ON T.uid=U.uid where delete_flag=0 ;";
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
        ?>

        <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>ひとこと掲示板</title>
    </head>
            <body>                
    <div>
            <table>
        <thead>
        <tr><th>タイトル</th><th>コメント</th><th>投稿者</th></tr>
        <?php foreach($data as $row){ ?>
            <tr>
            <td><?php echo htmlentities( $row['todo_title'], ENT_QUOTES, 'UTF-8');?></td>
            <td><?php echo htmlentities( $row['todo_value'], ENT_QUOTES, 'UTF-8');;?></td>
            <td><?php echo $row['user_name'];?></td>
        </tr>
        <?php }?>        
            </table>
        </div>
        
 <!-- Bootstrap Javascript(jQuery含む) -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
 <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
 <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        </body>

        </html>
