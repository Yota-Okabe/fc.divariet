<?php 
    include './include/login.php';

// クッキーを読み込み、フォームに名前をセットする
    if (isset($_COOKIE['name'])) {
        $name = $_COOKIE['name'];
    }else {
        $name = "";
    }

    $num = 5;
    
    $dsn = 'mysql:dbname=fc.divariet;host=localhost';
    $user = 'root';
    $password='';

    $page = 1;
    if (isset($_GET['page']) && $_GET['page'] > 1) {
        $page = intval($_GET['page']);
    }

    try {
        $db = new PDO($dsn, $user, $password);
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $stmt = $db->prepare("SELECT * FROM bbs ORDER BY date DESC LIMIT :page, :num");
        $page = ($page-1) * $num;
        $stmt->bindParam(':page', $page, PDO::PARAM_INT);
        $stmt->bindParam(':num', $num, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        exit("エラー：" . $e->getMessage());
    }
?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/login-user.css">
    <title>FC.Divariet</title>
</head>
<body>
    <?php include('navbar.php'); ?>
    <main role="main" class="container">
        <div>
            <div class="login-user">
                <h3>試合レポート</h3>
                <a>（ログインユーザ：<?php echo $_SESSION['name'] ?>）</a>
            </div>
            <form action="write.php" method="post">
                <div class="form-group">
                    <label>タイトル</label>
                    <input type="text" name="title" class="form-control">
                </div>
                <div class="form-group">
                    <label>名前</label>
                    <input type="text" name="name" class="form-control" value="<?php echo $name ?>">
                </div>
                <div class="form-group">
                    <textarea name="body" cols="30" rows="10" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <label>削除パスワード（数字4桁）</label>
                    <input type="text" name="pass" class="form-control">
                </div>
                <input type="submit" value="投稿" class="btn btn-outline-primary">
                <input type="hidden" name="token" value="<?php echo hash("sha256", session_id()) ?>">
            </form>
            <hr>

            <?php while($row = $stmt->fetch()): ?>
                <div class="card">
                    <div class="card-header">
                        <?php echo $row['title']? $row['title']: '（無題）'; ?>
                    </div>
                    <div class="card-body">
                        <p class="card-text"><?php echo nl2br(htmlspecialchars($row['body'], ENT_QUOTES, 'UTF-8')) ?></p>
                    </div>
                    <div class="card-footer">
                        <form action="delete.php" method="post" class="form-inline">
                        <?php echo $row['name'] ?>
                        (<?php echo $row['date'] ?>)
                            <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
                            <input type="text" name="pass" placeholder="削除パスワード" class="form-control">
                            <input type="submit" value="削除" class="btn btn-secondary">
                            <input type="hidden" name="token" value="<?php echo hash("sha256", session_id()) ?>">
                        </form>
                    </div>
                </div>
                <hr>
            <?php endwhile; ?>

            <?php
                try {
                    $stmt = $db->prepare("SELECT COUNT(*) FROM bbs");
                    $stmt->execute();
                } catch (PDOException $e) {
                    exit("エラー" . $e->getMessage());
                }

                $comments = $stmt->fetchColumn();
                $max_page = ceil($comments / $num);
                if ($max_page >= 1) {
                    echo '<nav><ul class="pagination">';
                    for ($i=1; $i<=$max_page; $i++) { 
                        echo '<li class="page-item"><a class="page-link" href="bbs.php?page='.$i.'">'.$i.'</a></li>';
                    }
                    echo '</ul></nav>';
                }
            ?>

        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>
</html>