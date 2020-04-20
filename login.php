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
  </head>
  <body>
    <div class="main-container" style="text-align:center;">
      <div class="sub-container-left">
          <p class="title-left">Manage Accounts.</p>
          <p class="sub-title">You can only signed in to one user at a time.</p>
          <div>
          <?php foreach ($_SESSION['users'] as $index => $username): ?>
            <div class="user-list" onclick="submitLogin('formlogin-<?= $index;?>')">
            <?= ucfirst($username);?>
              <form action="auth.php" method="post" id="formlogin-<?= $index;?>">
                  <input type="hidden" name="username" value="<?= $username;?>" />
                  <input type="hidden" name="action" value="login" />
              </form>
            </div>
            <div>
              <label class="so" onclick="submitLogout('formlogout-<?= $index;?>')">Delete</label>
              <form action="auth.php" method="post" id="formlogout-<?= $index;?>">
                  <input type="hidden" name="username" value="<?= $username;?>" />
                  <input type="hidden" name="action" value="logoutLDAP" />
              </form>
            </div>
          <?php endforeach;?>
          </div>
          <!-- <table cellspacing="0" cellpadding="0" class="clickable" >
              <?php foreach ($_SESSION['users'] as $index => $username): ?>
                  <tr class="clickable" onclick="submitLogin('formlogin-<?= $index;?>')">
                      <td class="user">
                          <?= ucfirst($username);?>
                          <form action="auth.php" method="post" id="formlogin-<?= $index;?>">
                              <input type="hidden" name="username" value="<?= $username;?>" />
                              <input type="hidden" name="action" value="login" />
                          </form>
                      </td>
                      <td class="so">
                          <label class="so" onclick="submitLogout('formlogout-<?= $index;?>')">
                              Delete
                          </label>
                          <form action="auth.php" method="post" id="formlogout-<?= $index;?>">
                              <input type="hidden" name="username" value="<?= $username;?>" />
                              <input type="hidden" name="action" value="logoutLDAP" />
                          </form>
                      </td>
                  </tr>
              <?php endforeach;?>
          </table> -->
      </div>
      <script>
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
    <div class="snackbar"><?php echo ("IP : ".getIP())?></div>
  </body>
</html>
