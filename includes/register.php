<?php if (count(get_included_files()) == 1) exit(http_response_code(403)); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="icon" href="https://getbootstrap.com/docs/4.0/assets/img/favicons/favicon.ico">

        <title>Encryption Login System</title>

        <link href="https://getbootstrap.com/docs/4.0/examples/starter-template/starter-template.css" rel="stylesheet">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    </head>

    <body>

        <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
            <a class="navbar-brand" href="<?= Basic::baseUrl() ?>">Home</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarsExampleDefault">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="<?= Basic::baseUrl() ?>register">Register <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= Basic::baseUrl() ?>private">Private</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= Basic::baseUrl() ?>login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= Basic::baseUrl() ?>logout" onclick="return confirm('Do you want to logout?')">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>

        <main role="main" class="container">

            <div class="row">
                <div class="col-sm-6">
                <?php
                if (! empty($_GET['token'])) {
                    require __DIR__ . '/../phpqrcode/lib/merged/phpqrcode.php';

                    $link = Basic::baseUrl() . 'login?token=' . $_GET['token'];

                    echo QRcode::svg($link); // QR code
                    echo '<br />';
                    echo "<a href=\"$link\" target=\"_blank\"><button type=\"button\" class=\"btn btn-primary\">Login to App</button></a>";
                    exit;
                }

                if (isset($_POST['register'])) {
                    $email = htmlspecialchars($_POST['email']);
                    $pass = htmlspecialchars($_POST['pass']);

                    $plaintext = json_encode(['email' => $email, 'pass' => $pass]);
                    $encrypted = Basic::encrypt($plaintext, PASS_PHRASE . $email . $pass);
                    $data = base64_encode($encrypted);
                    $link = Basic::baseUrl() . 'register?token=' . $data;

                    /* Place script here to call mail API to send link to login page. */

                    header('Location: ' . $link); // Display QR code & login link
                    exit;
                }
                ?>
                <?php if (empty($_GET['token'])) : ?>
                <h2>Registration</h2>
                <form class="form-horizontal" method="post">
                    <label for="email">Email</label>
                    <input class="form-control" type="email" placeholder="Email" name="email" required><br />
                    <label for="pass">Password</label>
                    <input class="form-control" type="password" placeholder="Password" name="pass" required><br />                
                    <button class="btn btn-outline-success" type="submit" name="register">Register</button>
                    <button class="btn btn-outline-primary" type="reset">Reset</button>
                </form>
                <?php endif; ?>
                </div>
            </div>

        </main>
  
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </body>
</html>