<?php
require_once 'functions/helpers.php';
require_once 'functions/pdo_connection.php';
global $pdo;
if (isset($_GET['post_id']))
{
    $sql = "SELECT posts.* ,categories.name AS category_name FROM posts JOIN categories ON posts.category_id = categories.id WHERE  posts.id = ?";

    $statement = $pdo->prepare($sql);
    $statement->execute([$_GET['post_id']]);
    $post = $statement->fetch();

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP tutorial</title>
    <link rel="stylesheet" href="<?= asset('assets/css/bootstrap.min.css') ?>" media="all" type="text/css">
    <link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>" media="all" type="text/css">
</head>
<body>
<section id="app">
    <?php require_once "layouts/top-nav.php"?>
    <section class="container my-5">
        <!-- Example row of columns -->
        <section class="row">
            <section class="col-md-12">
                <?php if ($post){ ?>
                <h1><?= $post->title ?></h1>
                <h5 class="d-flex justify-content-between align-items-center">
                    <a href="<?= url('category.php?category_id='.$post->category_id) ?>"><?= $post->category_name ?></a>
                    <span class="date-time">22/22/33</span>
                </h5>
                <article class="bg-article p-3"><img class="float-right mb-2 ml-2" style="width: 18rem;" src="" alt="">body</article>

        <?php } else {?>
                    <section>post not found!</section>
                <?php }?>
             
            </section>
        </section>
    </section>

</section>
<script src="<?= asset('assets/js/jquery.min.js') ?>"></script>
<script src="<?= asset('assets/js/bootstrap.min.js') ?>"></script>
</body>
</html>