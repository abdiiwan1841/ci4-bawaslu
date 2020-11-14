<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title><?= $title; ?></title>
    <meta name="author" content="Tarkiman, S.Kom.">
    <meta name="author_phone" content="0852-2224-1987">
    <meta name="author_email" content="tarkiman.zone@gmail.com">
    <meta name="author_linkedin" content="https://www.linkedin.com/in/tarkiman">
    <meta content="width=device-width, initial-scale=1.0, user-scalable=no" name="viewport">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <link href="<?= '/assets/icomoon/style.css'; ?>" rel="stylesheet">
    <link href="<?= '/assets/css/main.css'; ?>" rel="stylesheet">
</head>

<body>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">
                <div class="error-container">
                    <h1>&nbsp;</h1>
                    <img class="mb-4" src="/assets/images/reset_password.svg" alt="" width="72" height="72">
                    <h2>Request Reset Password</h2>
                    <div class="error-details">
                        <span>Your token for reset password has been expired. To get a new password reset link, visit link <a href="<?= base_url('/forgot-password'); ?>">Forgot Password</a></span>
                    </div>
                    <div class="error-actions">
                        <a href="<?= base_url('login'); ?>" class="btn btn-info">
                            Try Login
                        </a>
                        <a href="https://instagram.com/trexmen" role="button" class="btn btn-success" data-toggle="modal">
                            Contact Support
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= '/assets/js/jquery.min.js'; ?>"></script>
    <script src="<?= '/assets/js/bootstrap.js'; ?>"></script>
</body>

</html>