<?php 
session_start();
$session_user = $_SESSION['profile']['user_name'];
$session_uid = $_SESSION['profile']['user_id'];
if($session_uid){

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
    }else{
        header('Location: ./sns_login.php');
    }
        ?>

        <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>ひとこと掲示板</title>
        <link rel="stylesheet" type="text/css" href = "./css/sns.css" >
    
    </head>
    <body style="padding-bottom:4.5rem;">                
            <nav>
  <div class="nav navbar navbar-light bg-light fixed-bottom bg-dark p-4" id="nav-tab" role="tablist" >
    <a class="nav-item text-white navbar-brand nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">ホーム</a>
    <a class="nav-item text-white navbar-brand nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">プロフィール</a>
    <a class="nav-item text-white navbar-brand nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">コンタクト</a>
  </div>
</nav>
</body>
<body>

<button onclick="location01()" class="btn btn-primary rounded-circle p-0 fixed_btn" style="width:4rem;height:4rem;">＋</button>

<div class="tab-content mt-3" id="nav-tabContent">
  <div class="tab-pane active posiCenter" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
        <article>
        <?php foreach($data as $row){ ?>
        <?php //echo htmlentities( $row['todo_title'], ENT_QUOTES, 'UTF-8'); エスケープよう?>
            
        <div class='card posiCenterIn'>
        <h5 class='card-header'><?php echo $row['todo_title'];?> </h5> 
        <div class='card-body'> <?php echo $row['todo_value'] ;?></div>
        <div class='card-footer'> <?php echo $row['user_name'];?></div>
        </div>
        <?php }?>
        </article>
        </div>
  
  <div class="tab-pane" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
    <?php echo $_SESSION['profile']['user_name'] ; ?>
  
  
  </div>


    <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab"><nav class="navbar navbar-light bg-light">
  <form class="form-inline">
    <input class="form-control mr-sm-2" type="search" placeholder="検索..." aria-label="検索...">
    <button type="submit" class="btn btn-outline-success my-2 my-sm-0">検索</button>
  </form>
</nav>
</div>
</div>
<script type="text/javascript">
function location01() {
  location.href="http://localhost/myapp/xss_demo_php/vulnerable_sns/sns_mypage.php"
}
</script>
        </div>
 <!-- Bootstrap Javascript(jQuery含む) -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
 <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
 <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        </body>

        </html>
