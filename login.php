<?php
session_start();
include('common_functions.php');

// if (checkLogin() || checkLDAP()) {
//   header("location:./index.php");
// }

if (checkLogin()) {
  header("location:./index.php");
}

if (checkLDAP()) {
  header("location:./users.php");
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Form</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
  </head>
  <body>
    <div class="container">
      <form action="auth.php" method="post">
        <p class="title">Welcome.</p>
        <p class="sub">
          To test single sign-on technology,<br />please sign in with your personal
          information
        </p>
        <input
          type="hidden"
          value="<?php echo getIP(); ?>"
          name="ip"
        />
        <div>
          <input
            class="text"
            type="text"
            name="username"
            placeholder="Username"
          />
        </div>
        <div>
          <input
            class="text"
            type="password"
            name="password"
            placeholder="Password"
          />
        </div>
        <?php
        if (isset($_SESSION['loginerror']) && $_SESSION['loginerror'] != "") {
          ?>
          <div class="warning"><label> <?php echo $_SESSION['loginerror'] ?> </label></div>
          <?php
        }
        ?>
        <div>
          <input type="hidden" name="action" value="loginLDAP" />
          <input type="submit" name="signin" value="Sign me in" />
        </div>
      </form>
    </div>
    <div class="snackbar"><?php echo ("IP : ".getIP())?></div>
  
  </body>
</html>
