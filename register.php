<?php
session_start();
include('common_functions.php');

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Create Account</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  </head>
  <body>
    <div class="main-container" style="text-align:center;">
      <div class="sub-container-right">
        <form action="auth.php" method="post">
          <p class="title-right">Create Account</p>
          <div>
            <input
              class="text"
              type="text"
              name="username"
              placeholder="Username"
              value="<?php echo isset($_SESSION['username']) && $_SESSION['username']; ?>"
              autofocus
            />
          </div>
          <div>
            <input
              class="text"
              type="password"
              name="password"
              placeholder="Password"
              value="<?php echo isset($_SESSION['password']) && $_SESSION['password']; ?>"
            />
          </div>
          <div>
            <input
              class="text"
              type="password"
              name="password2"
              placeholder="Confirm Password"
            />
          </div>
          <!-- jika array sessionnya itu ada dan sessionnya itu ada isinya, maka tampil errornya -->
          <?php if (isset($_SESSION['registererror']) && $_SESSION['registererror']): ?>
            <div class="warning"><label> <?php echo $_SESSION['registererror'] ?> </label></div>
          <?php endif; ?>
          <?php $_SESSION['registererror'] = ""; ?>
          <!-- cek apakah text user dan password tidak kosong, cek apakah nama user telah tersedia -->
          <div>
            <input type="hidden" name="action" value="registerLDAP" />
            <input class="signin" type="submit" name="signup" value="Sign me up" />
          </div>
        </form>
      </div>
    </div>
    <div class="snackbar"><?php echo ("IP : ".getIP())?></div>
    <div class="footer">&copy; <?= date('Y'); ?> by Dimas Patriananda</div>
  </body>
</html>
