<?php
session_start();
include('common_functions.php');

if (checkLogin()) {
    header("location:./index.php");
}

if (!checkLDAP()) {
    header("location:./login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage your accounts</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
    <div class="container" style="text-align:center;">
        <p class="title3">Manage Accounts</p>
        <p class="sub">
        you can only signed in to one user at a time.
        </p>
        <table cellspacing="0" cellpadding="0">
            <?php foreach ($_SESSION['users'] as $index => $username): ?>
                <tr onclick="submitLogin('formlogin-<?= $index;?>')">
                    <td class="user">
                        <?= $username;?>
                        <form action="auth.php" method="post" id="formlogin-<?= $index;?>">
                            <input type="hidden" name="username" value="<?= $username;?>" />
                            <input type="hidden" name="action" value="login" />
                        </form>
                    </td>
                    <td>
                        <label class="so" onclick="submitLogout('formlogout-<?= $index;?>')">
                            Delete
                        </label>
                        <!-- <input type="submit" class="so" onclick="(function logout(){document.getElementById('formlogout-<?= $index;?>').submit();})()" /> -->
                        <form action="auth.php" method="post" id="formlogout-<?= $index;?>">
                            <input type="hidden" name="username" value="<?= $username;?>" />
                            <input type="hidden" name="action" value="logoutLDAP" />
                        </form>
                    </td>
                </tr>
            <?php endforeach;?>
        </table>
    </div>
        <p><a href="login.php">login</a></p>
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
</body>
</html>