<?php
require_once '../functions/helpers.php';
require_once '../functions/pdo_connection.php';
global $pdo;
if (isset($_POST['password']) && $_POST['password'] !=='' &&
isset($_POST['first_name']) && $_POST['first_name'] !=='' &&
isset($_POST['email']) && $_POST['email'] !=='' &&
isset($_POST['last_name']) && $_POST['last_name'] !=='' &&
isset($_POST['confirm']) && $_POST['confirm'] !=='')
{
   if ($_POST['password'] == $_POST['confirm'])
   {
      if (strlen($_POST['password']) >= 5)
      {
          $sql = "SELECT * FROM users WHERE email = ?";
          $statement = $pdo->prepare($sql);
          $statement->execute([$_POST['email']]);
          $user = $statement->fetch();
          if (!$user)
          {
              $sql = "INSERT INTO users SET email = ? , first_name = ? , last_name = ? , password = ? , created_at = NOW()";
              $password = password_hash($_POST['password'],PASSWORD_DEFAULT);
              $statement = $pdo->prepare($sql);
              $statement->execute([$_POST['email'],$_POST['first_name'],$_POST['last_name'],$password]);
              redirect('auth/login.php');
          }
          else{
              $errorEmail = "ایمیل تکراری است";
          }
      }
      else{
          $errorPass = "رمز عبور باید شامل حداقل 5 کاراکتر باشد";
      }

//       redirect('auth/register.php');
   }
   else{
       $errorPass = "رمز عبور و تکرار آن با هم برابر نیستند!";
   }
}
else{
    if (!empty($_POST) and $_POST['email'] === '')
    {
        $errorEmail = 'فیلد ایمیل اجباری است';
    }
    if (!empty($_POST) and $_POST['first_name'] === '')
    {
        $errorFirstName = 'فیلد نام اجباری است';
    }
    if (!empty($_POST) and $_POST['last_name'] === '')
    {
        $errorLastName = 'فیلد نام خانوادگی اجباری است';
    }
    if (!empty($_POST) and $_POST['password'] === '')
    {
        $errorPass = 'فیلد رمز عبور اجباری است';
    }
    if (!empty($_POST) and $_POST['confirm'] === '')
    {
        $errorConfirm = 'فیلد تکرار رمز عبور اجباری است';
    }
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

        <section style="height: 100vh; background-color: #138496;" class="d-flex justify-content-center align-items-center">
            <section style="width: 20rem;">
                <h1 class="bg-warning rounded-top px-2 mb-0 py-3 h5">PHP Tutorial login</h1>
                <section class="bg-light my-0 px-2"><small class="text-danger"></small></section>
                <form class="pt-3 pb-1 px-2 bg-light rounded-bottom" action="" method="post">
                    <section class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="email ...">
                        <?php if (isset($errorEmail)){ ?>
                            <span class="text-danger"><?= $errorEmail ?></span>
                        <?php }?>
                    </section>
                    <section class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" class="form-control" name="first_name" id="first_name" placeholder="first_name ...">
                        <?php if (isset($errorFirstName)){ ?>
                            <span class="text-danger"><?= $errorFirstName ?></span>
                        <?php }?>
                    </section>
                    <section class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" class="form-control" name="last_name" id="last_name" placeholder="last_name ...">
                        <?php if (isset($errorLastName)){ ?>
                            <span class="text-danger"><?= $errorLastName ?></span>
                        <?php }?>
                    </section>
                    <section class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="password ...">
                        <?php if (isset($errorPass)){ ?>
                            <span class="text-danger"><?= $errorPass ?></span>
                        <?php }?>
                    </section>
                    <section class="form-group">
                        <label for="confirm">Confirm</label>
                        <input type="password" class="form-control" name="confirm" id="confirm" placeholder="confirm ...">
                        <?php if (isset($errorConfirm)){ ?>
                            <span class="text-danger"><?= $errorConfirm ?></span>
                        <?php }?>
                    </section>
                    <section class="mt-4 mb-2 d-flex justify-content-between">
                        <input type="submit" class="btn btn-success btn-sm" value="register">
                        <a class="" href="<?= url('auth/login.php') ?>">login</a>
                    </section>
                </form>
            </section>
        </section>

    </section>
    <script src="<?= asset('assets/js/jquery.min.js') ?>"></script>
    <script src="<?= asset('assets/js/bootstrap.min.js') ?>"></script>
</body>

</html>