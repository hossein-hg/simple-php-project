<?php
require_once '../../functions/helpers.php';
require_once '../../functions/pdo_connection.php';
session_start();
if (!$_SESSION['user'])
{
    redirect('auth/login.php');
}
global $pdo;
if (isset($_GET['category_id']))
{
    $sql = "SELECT * FROM categories WHERE id = ?";
    $statement = $pdo->prepare($sql);
    $statement->execute([$_GET['category_id']]);
    $category = $statement->fetch();
    if (!$category)
    {
        redirect('panel/category');
    }
    if (isset($_POST['name']) and $_POST['name'] !== '' )
    {
        $sql = "UPDATE categories SET name = ? WHERE id = ?";
        $statement = $pdo->prepare($sql);
        $statement->execute([$_POST['name'],$category->id]);
        redirect('panel/category');
    }
    else{
        if (!empty($_POST)){
            $error = true;
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
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css" media="all" type="text/css">
    <link rel="stylesheet" href="../../assets/css/style.css" media="all" type="text/css">
</head>
<body>
<section id="app">
    <?php require_once '../layouts/top-nav.php'?>
    <section class="container-fluid">
        <section class="row">
            <section class="col-md-2 p-0">
                <?php require_once '../layouts/sidebar.php'?>
            </section>
            <section class="col-md-10 pt-3">

                <form action="<?= url('panel/category/edit.php?category_id='.$_GET['category_id']) ?>" method="post">
                    <section class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="name ..." value="<?= $category->name ?>">
                        <?php if (isset($error)){ ?>
                            <span class="text-danger">فیلد نام الزامی است</span>
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