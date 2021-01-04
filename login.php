<?php
session_start();
include('common_functions.php');

if (checkLogin()) {
  header("location:./index.php");
}

checkLDAP();

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage Account or Login</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  </head>
  <body>
    <div class="main-container" style="text-align:center;">
      <div class="sub-container-left">
          <p class="title-left">Manage Accounts.</p>
          <p class="sub-title">You can only signed in to one user at a time.</p>
          <div>
          <!-- repeat based on connected users to LDAP Server-->
          <?php foreach ($_SESSION['users'] as $index => $username): ?>
            <!-- ketika div di klik, yg disubmit adalah form dengan id yang sama. -> jalankan javascript -->
            <div class="user-list" onclick="submitLogin('formlogin-<?= $index;?>')">
            <!-- display list connected users -->
            <?= ucfirst($username);?>
              <!-- form buat bawa inputan (post) ketika div diklik -->
              <form action="auth.php" method="post" id="formlogin-<?= $index;?>">
                  <input type="hidden" name="username" value="<?= $username;?>" />
                  <input type="hidden" name="action" value="login" />
              </form>
            </div>
            <div>
              <label class="so" onclick="submitLogout('formlogout-<?= $index;?>')"><i class="material-icons">close</i></label>
              <form action="auth.php" method="post" id="formlogout-<?= $index;?>">
                  <input type="hidden" name="username" value="<?= $username;?>" />
                  <input type="hidden" name="action" value="logoutLDAP" />
              </form>
            </div>
          <?php endforeach;?>
          </div>
      </div>
      <script>
          // submit form yang ada di document/halaman ini dengan id formlogin-index line 30 untuk disubmit with javascript
          function submitLogin(formName) {
              document.getElementById(formName).submit();
          }
          function submitLogout(formName) { 
              if (!event) var event = window.event;                // Get the window event
              event.cancelBubble = true;                           // IE Stop propagation
              if (event.stopPropagation) event.stopPropagation();  // Other Broswers
              // console.log('td clicked');
              document.getElementById(formName).submit();
          };
      </script>
      <div class="sub-container-right">
        <form action="auth.php" method="post">
          <p class="title-right">Welcome.</p>
          <p class="sub-title">
            To test single sign-on technology,<br />please sign in with your personal information.
          </p>
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
          <!-- jika session login error dan session tidak kosong, maka tampil errornya -->
          <?php if (isset($_SESSION['loginerror']) && $_SESSION['loginerror'] != ""): ?>
            <div class="warning"><label> <?php echo $_SESSION['loginerror'] ?> </label></div>
          <?php endif; ?>
          <?php $_SESSION['loginerror'] = ""; ?>
          <div>
            <input type="hidden" name="action" value="loginLDAP" />
            <input class="signin" type="submit" name="signin" value="Sign me in" />
          </div>
        </form>
      </div>
    </div>
    <a href="./register.php" class="createLink">Create an Account</a>
    <div class="snackbar"><?php echo ("IP : ".getIP())?></div>
    <div class="footer">&copy; <?= date('Y'); ?> by Dimas Patriananda</div>
  </body>
</html>
