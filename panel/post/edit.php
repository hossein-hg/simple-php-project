<?php
require_once '../../functions/helpers.php';
require_once '../../functions/pdo_connection.php';
session_start();
if (!$_SESSION['user'])
{
    redirect('auth/login.php');
}
global $pdo;
$sql = "SELECT * FROM categories";
$statement = $pdo->prepare($sql);
$statement->execute();
$categories = $statement->fetchAll();
if (isset($_GET['post_id'])) {
    $sql = "SELECT * FROM posts WHERE id = ?";
    $statement = $pdo->prepare($sql);
    $statement->execute([$_GET['post_id']]);
    $post = $statement->fetch();

    if (!$post) {
        redirect('panel/post');
    }


    if (isset($_POST['title']) && $_POST['title'] !== '' &&
        isset($_POST['body']) && $_POST['body'] !== '' &&
        isset($_POST['category_id']) && $_POST['category_id'] !== '') {
        if ($_FILES['image']['name']) {
            $baseURL = dirname(dirname(__DIR__));
            $imageMime = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $allowedMimes = ['jpeg', 'png', 'jpg'];
            if (!in_array($imageMime, $allowedMimes)) {
                redirect('panel/post');
            }

            $imageName = '/assets/images/posts/' . date('Y-m-d-h-i-s') . "." . $imageMime;
            move_uploaded_file($_FILES['image']['tmp_name'], $baseURL . $imageName);
            if (file_exists($baseURL . $post->image)) {
                unlink($baseURL . $post->image);
            }
            $sql = "UPDATE posts SET title = ? , body = ? , category_id = ? ,image = ? , updated_at = NOW() WHERE id = ?";
            $statement = $pdo->prepare($sql);
            $statement->execute([$_POST['title'],$_POST['body'],$_POST['category_id'],$imageName,$_GET['post_id']]);

        }
        else{
            $sql = "UPDATE posts SET title = ? , body = ? , category_id = ? , updated_at = NOW() WHERE id = ?";
            $statement = $pdo->prepare($sql);
            $statement->execute([$_POST['title'],$_POST['body'],$_POST['category_id'],$_GET['post_id']]);
        }
        redirect('panel/post');
    }
    else{

        if (!empty($_POST) and $_POST['title'] === '')
        {

            $errorTitle = 'فیلد عنوان الزامی است';
        }
        if (!empty($_POST) and $_POST['body'] === '')
        {
            $errorBody = 'فیلد توضیحات الزامی است';
        }

    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP panel</title>
    <link rel="stylesheet" href="<?= asset('assets/css/bootstrap.min.css') ?>" media="all" type="text/css">
    <link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>" media="all" type="text/css">
</head>

<body>
<section id="app">

    <?php require_once '../layouts/top-nav.php' ?>
    <section class="container-fluid">
        <section class="row">
            <section class="col-md-2 p-0">
                <?php require_once '../layouts/sidebar.php' ?>
            </section>
            <section class="col-md-10 pt-3">

                <form action="<?= url('panel/post/edit.php?post_id='.$post->id) ?>" method="post" enctype="multipart/form-data">
                    <section class="form-group">
                        <label for="title">Title</label>
                        <input   type="text" class="form-control" name="title" id="title" placeholder="title ..."
                               value="<?= $post->title ?>">
                        <?php if (isset($errorTitle)){ ?>
                            <span class="text-danger"><?= $errorTitle ?></span>
                        <?php } ?>
                    </section>
                    <section class="form-group">
                        <label for="image">Image</label>
                        <input  type="file" class="form-control" name="image" id="image">
                    </section>
                    <section class="form-group">
                        <label for="category_id">Category</label>
                        <select class="form-control" name="category_id" id="category_id">
                            <?php foreach ($categories as $category){ ?>
                            <option <?php if ($category->id === $post->category_id) echo "selected";?> value="<?= $category->id ?>"><?= $category->name ?></option>
                            <?php } ?>
                        </select>
                    </section>
                    <section class="form-group">
                        <label for="body">Body</label>
                        <textarea class="form-control" name="body" id="body" rows="5"
                                  placeholder="body ..."><?= $post->body ?></textarea>
                        <?php if (isset($errorBody)){ ?>
                            <span class="text-danger"><?= $errorBody ?></span>
                        <?php } ?>
                    </section>
                    <section class="form-group">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </section>
                </form>

            </section>
        </section>
    </section>

</section>

<script src="<?= asset('assets/js/jquery.min.js') ?>"></script>
<script src="<?= asset('assets/js/bootstrap.min.js') ?>"></script>
</body>

</html>