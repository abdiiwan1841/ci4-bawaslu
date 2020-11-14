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
            <div class="span4 offset4">
                <div class="signin">
                    <h4 class="center-align-text" style="color: #fff;"><?php //echo $data->system_name; 
                                                                        ?></h4>
                    <center><img src="<?= '/images/logo.png'; ?>" style="max-width: 200px;" /></center>
                    <div class="message failure">

                    </div>
                    <form action="<?= base_url('forgot-password-submit'); ?>" class="signin-wrapper" method="post">
                        <?php if (session()->getFlashData('messages')) : ?>
                            <div class="alert alert-danger" role="alert">
                                <?= session()->getFlashData('messages') ?>
                            </div>
                        <?php endif; ?>
                        <div class="content">
                            <?= csrf_field(); ?>
                            You can reset your password here.
                            <input class="input input-block-level" name="email" placeholder="Email Address" type="text">
                            <?= $validation->getError('email'); ?>
                        </div>
                        <div class="actions">
                            <input class="btn btn-info pull-right" type="submit" value="Request Reset Password">
                            <span class="checkbox-wrapper">
                                <a href="<?= base_url('login'); ?>" class="pull-left">Try Login</a>
                            </span>
                            <div class="clearfix"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= '/assets/js/jquery.min.js'; ?>"></script>
    <script src="<?= '/assets/js/bootstrap.js'; ?>"></script>
</body>

</html>