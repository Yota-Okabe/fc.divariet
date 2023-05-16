<?php
    session_start();

    if (isset($_SESSION['id'])) {
        header('Location: index.php');
    }elseif (isset($_POST['name']) && isset($_POST['password'])) {
        $dsn = 'mysql:dbname=fc.divariet;host=localhost';
        $user = 'root';
        $password='';
    
        try {
            $db = new PDO($dsn, $user, $password);
            $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $stmt = $db->prepare("
                SELECT users.id, users.name AS login_name, profiles.name 
                FROM users, profiles
                WHERE users.id=profiles.id AND users.name=:name AND users.password=:pass");
            $stmt->bindParam(':name', $_POST['name'], PDO::PARAM_STR);
            $stmt->bindParam(':pass', hash("sha256", $_POST['password']), PDO::PARAM_STR);
            $stmt->execute();

            if ($row = $stmt->fetch()) {
                session_regenerate_id(true);
                $_SESSION['id'] = $row['id'];
                $_SESSION['name'] = $row['name'];
                header('Location: index.php');
                exit();
            }else {
                header('Location: login.php');
                exit();
            }
        }catch(PDOException $e){
            exit('エラー' . $e->getMessage());
        }
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>サークルサイト</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <style type="text/css">
        form{
            width: 100%;
            max-width: 330px;
            padding: 15px;
            margin: auto;
            text-align: center;
        }
        #name {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }
        #password {
            margin-bottom: 10px;
            border-top-right-radius: 0;
            border-top-left-radius: 0;
        }
    </style>
</head>
<body>
    <?php include('navbar.php'); ?>
    <main role="main" class="container">
        <div>
            <form action="login.php" method="post">
                <h3>ログイン</h3>
                <label class="sr-only"></label>
                <input type="text" name="name" id="name" class="form-control" placeholder="ユーザ名">
                <label class="sr-only"></label>
                <input type="password" name="password" id="password" class="form-control" placeholder="パスワード">
                <label class="sr-only"></label>
                <input type="submit" value="ログイン" class="btn btn-primary btn-block">
            </form>

        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>
</html>