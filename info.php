<?php
    include './include/login.php';
    $fp = fopen("info.txt", "r");
    $line = array();
    $body = '';

    if ($fp) {
        while (!feof($fp)) {
            $line[] = trim(fgets($fp));
        }
        fclose($fp);
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
    <title>FC.Dvariet</title>
</head>
<body>
    <?php include('navbar.php'); ?>
    <main role="main" class="container">
        <div>
            <div class="login-user">
                <h3>連絡事項 詳細</h3>
                <a>（ログインユーザ：<?php echo $_SESSION['name'] ?>）</a>
            </div>
            <?php
                foreach ($line as $i => $text) {
                    if ($i==0) {
                        echo '<h5>' . $text . '</h5>';
                    }else {
                        $body .= $text . '<br>';
                    }
                }
                echo '<p>' . $body . '</p>';
            ?>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>
</html>