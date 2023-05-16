<?php
    include './include/login.php';
    $msg = null;
    $alert = null;

    if (isset($_FILES['image']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
        $old_name = $_FILES['image']['tmp_name'];
        // $new_name = $_FILES['image']['name'];
        $new_name = date("YmdHis");
        $new_name .= mt_rand();
        $size = getimagesize($_FILES['image']['tmp_name']);
        switch ($size[2]) {
            case IMAGETYPE_JPEG:
                $new_name .= '.jpg';
                break;
            case IMAGETYPE_GIF:
                $new_name .= '.gif';
            case IMAGETYPE_PNG:
                $new_name .= '.png';
            default:
                header('Location: upload.php');
                exit();
        }
        if (move_uploaded_file($old_name, 'img/' . $new_name)) {
            $msg = 'アップロードしました';
            $alert = 'success';
        }else {
            $msg = 'アップロードできませんでした';
            $alert = 'danger';
        }
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>画像アップロード</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>
<body>
    <?php include('navbar.php'); ?>
    <main role="main" class="container">
        <div>
            <h3>画像アップロード</h3>
            <?php 
                if ($msg) {
                    echo '<div class="alert alert-' . $alert . '" role="alert">' . $msg . '</div>';
                }
            ?>
            <form action="upload.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label>アップロードファイル</label><br>
                    <input type="file" name="image" class="form-control-file file">
                </div>
                <input type="submit" value="アップロードする" class="btn btn-outline-primary">
            </form>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</body>
</html>