<?php
session_start();
include('common_functions.php');

$username = isset($_SESSION['username']) ? $_SESSION['username'] : "";

if (checkUserLDAP($username)['count'] < 1 || !checkLogin()) {
  header("location:./login.php");
}
$username = ucfirst($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Welcome, <?= $username;?></title>
    <link rel="stylesheet" type="text/css" href="style.css" />
  </head>
  <body>
    <div class="index-container">
      <p class="title-index">Signed In.</p>
      <hr />
      <p class="sub-title">
        You are now signed in as <?= $username;?>, you need to sign out</br>before signing in as different user.
      </p>
      <form action="auth.php" method="post">
          <input type="hidden" name="action" value="logout" />
          <input class="signout" type="submit" name="signout" value="Sign out" />
      </form>
    </div>
    <?php if(isset($_SESSION['devRegistered']) && $_SESSION['devRegistered']) : ?>
      <div class="warning"><label> <?php echo $_SESSION['devRegistered'] ?> </label></div>
    <?php endif; ?>
    <form action="auth.php" method="post">
          <input type="hidden" name="action" value="regDevice" />
          <input class="signin" type="submit" name="regDevice" value="Register Device" />
    </form>
    <div class="footer">&copy; <?= date('Y'); ?> by Dimas Patriananda</div>
  </body>
</html>
