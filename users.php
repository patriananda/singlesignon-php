<?php
session_start();
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
            <?php foreach ($_SESSION['users'] as $username): ?>
                <tr>
                    <td class="user">
                        <label for="#login"><?= $username;?></label>
                    </td>
                    <td><label for="#logout" class="so">Delete</label></td>
                </tr>
                <form action="auth.php" method="post">
                    <input type="hidden" name="username" value="<?= $username?>" />
                    <input type="hidden" name="action" value="login" />
                    <input type="submit" id="login" style="display:none;"/>
                </form>
                <form action="auth.php" method="post">
                    <input type="hidden" name="action" value="logout" />
                    <input type="submit" id="logout" style="display:none;"/>
                </form>
            <?php endforeach;?>
        </table>
    </div>
</body>
</html>