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
    <link rel="shortcut icon" type="image/png" href="<?= '/images/icon.ico'; ?>" id="favicon" data-original-href="<?= '/images/icon.ico'; ?>" />
    <link href="<?= '/assets/icomoon/style.css'; ?>" rel="stylesheet">
    <link href="<?= '/assets/css/main.css'; ?>" rel="stylesheet">
    <script type="text/javascript" src="<?= '/assets/jquery/js/jquery-1.12.4.js' ?>"></script>
    <script type="text/javascript" src="<?= '/assets/jquery/js/jquery-ui.js' ?>"></script>
    <link rel="stylesheet" href="<?= '/assets/jquery/css/jquery-ui.css' ?>">

    <!-- Notifikasi -->
    <link rel="stylesheet" type="text/css" href="<?= '/assets/tarkiman/tarkiman.min.css' ?>" />
    <!-- Notifikasi -->
    <script src="<?= '/assets/js/bootstrap.js'; ?>"></script>
    <link rel="stylesheet" type="text/css" href="<?= '/assets/css/bootstrap-editable.css' ?>">
</head>

<body>
    <header>
        <a href="<?= base_url('/'); ?>" class="logo">BAWASLU - SISTEM INFORMASI PEMANTAUAN TINDAK LANJUT</a>
        <div id="mini-nav">
            <ul class="hidden-phone">
                <li>
                    <a href="#documentation" data-toggle="modal" data-original-title="">
                        About
                    </a>
                    <div id="documentation" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                ×
                            </button>
                            <h4 id="myModalLabel1">
                                <center>
                                    Developed By Tarkiman, contact 0852-2224-1987
                                </center>
                            </h4>
                        </div>

                    </div>
                </li>
                <li>
                    <a href="<?= base_url('/faq') ?>"><span class="fs1" aria-hidden="true" data-icon="&#xe03b;"></span></a>
                </li>
                <li>
                    <a href="<?= base_url('/profile') ?>"><span class="fs1" aria-hidden="true" data-icon="&#xe090;"></span></a>
                </li>
                <li>
                    <a href="<?= base_url('/logout') ?>" onclick="return confirm('are you sure');"><span class="fs1" aria-hidden="true" data-icon="&#xe0b1;"></span></a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
    </header>
    <div class="container-fluid">

        <?php //$this->include('layout/backend_navbar_left_sidebar'); 
        ?>

        <div class="dashboard-wrapper">

            <?= $this->include('layout/backend_navbar'); ?>

            <div class="main-container">

                <?= $this->include('layout/backend_navbar_mobile'); ?>

                <div class="page-header">
                    <div class="pull-left">
                        <h2><?= $title; ?></h2>
                    </div>
                    <div class="pull-right">
                        <ul class="stats">
                            <!-- <li class="color-first hidden-phone">
                                <span class="fs1" aria-hidden="true" data-icon="&#xe0b3;"></span>
                                <div class="details">
                                    <span class="big">12</span>
                                    <span>New Tasks</span>
                                </div>
                            </li> -->
                            <li class="color-second">
                                <span class="fs1" aria-hidden="true" data-icon="&#xe052;"></span>
                                <div class="details" id="date-time">
                                    <span>Date </span>
                                    <span>Day, Time</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <?= $this->renderSection('backend_content'); ?>

            </div>
        </div><!-- dashboard-container -->
    </div><!-- container-fluid -->
    <script src="<?= '/assets/tarkiman/tarkiman.min.js' ?>"></script>

    <!-- Custom Js -->
    <script src="<?= '/assets/js/custom.js'; ?>"></script>
    <script>
        $(document).ready(function() {
            var currentDate = new Date();
            $(".date_picker").datepicker({
                dateFormat: 'yy-mm-dd'
            }).datepicker('setDate', new Date());
        });
    </script>

    <!-- Date Widget -->
    <script src="<?= '/assets/js/moment.js'; ?>"></script>
    <script>
        // Date Time
        setInterval(function() {
            date =
                '<span class="big">' +
                moment().format("MMMM Do YYYY") +
                "</span><span>" +
                moment().format("ddd hh:mm:ss a") +
                "</span>";
            $("#date-time").html(date);
        }, 1000);
    </script>

</body>

</html>