<!DOCTYPE html>
<html>
<style type="text/css" id="night-mode-pro-style">
    html {
        background-color: #FFFFFF !important;
    }

    body {
        background-color: #FFFFFF;
    }
</style>
<link type="text/css" rel="stylesheet" id="night-mode-pro-link">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="shortcut icon" type="image/png" href="<?= '/images/icon.png'; ?>" id="favicon" data-original-href="<?= '/images/icon.ico'; ?>" />

    <!-- Place this data between the <head> tags of your website -->
    <title><?= $title; ?></title>
    <meta name="description" content="">

    <!-- Google Fonts -->
    <link rel="stylesheet" href="<?= '/assets_login/all.css'; ?>" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
    <link href="<?= '/assets_login/style.css'; ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?= '/assets_login/all(1).css'; ?>" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= '/assets_login/dataTables.bootstrap4.min.css'; ?>">
    <!-- App Styles -->
    <link href="<?= '/assets_login/test.css'; ?>" rel="stylesheet">

    <style type="text/css">
        /* Chart.js */
        @-webkit-keyframes chartjs-render-animation {
            from {
                opacity: 0.99
            }

            to {
                opacity: 1
            }
        }

        @keyframes chartjs-render-animation {
            from {
                opacity: 0.99
            }

            to {
                opacity: 1
            }
        }

        .chartjs-render-monitor {
            -webkit-animation: chartjs-render-animation 0.001s;
            animation: chartjs-render-animation 0.001s;
        }
    </style>
    <style type="text/css">
        /* Chart.js */
        @-webkit-keyframes chartjs-render-animation {
            from {
                opacity: 0.99
            }

            to {
                opacity: 1
            }
        }

        @keyframes chartjs-render-animation {
            from {
                opacity: 0.99
            }

            to {
                opacity: 1
            }
        }

        .chartjs-render-monitor {
            -webkit-animation: chartjs-render-animation 0.001s;
            animation: chartjs-render-animation 0.001s;
        }
    </style>
    <style type="text/css">
        /* Chart.js */
        @-webkit-keyframes chartjs-render-animation {
            from {
                opacity: 0.99
            }

            to {
                opacity: 1
            }
        }

        @keyframes chartjs-render-animation {
            from {
                opacity: 0.99
            }

            to {
                opacity: 1
            }
        }

        .chartjs-render-monitor {
            -webkit-animation: chartjs-render-animation 0.001s;
            animation: chart js-render-animation 0.001s;
        }


        .home .cover-home {
            background-color: unset !important;
        }

        .navbar-brand {
            padding-top: unset !important;
            padding-bottom: unset !important;
        }

        .img-logo {
            width: 10rem !important;
        }
    </style>

</head>

<body class="">
    <header class="head-siptl">
        <nav class="navbar navbar-expand-lg navbar-light fixed-top bg-primary">
            <div class="container-fluid">
                <a class="navbar-brand" href="<?= base_url() ?>">
                    <img src="<?= '/images/logo.png'; ?>" class="img-logo" alt="">
                </a>
                <div class="" id="navbarSupportedContent">
                </div>
            </div>
        </nav>
    </header>
    <section class="home" style="background-image: url(<?= base_url('/assets_login/audit.jpg'); ?>)">
        <div class="cover-home" id="hg" style="">
            <div class="container">
                <div class="pt-4 text-white">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="content-text" style="background-color: #00000080;padding: 20px;">
                                <h1>Selamat Datang</h1>
                                <p>Sistem Informasi Pemantauan Tindak Lanjut (SIPTL) adalah aplikasi yang disediakan oleh BAWASLU dalam rangka mendukung pelaksanaan Tugas dan Wewenang BAWASLU sesuai dengan Undang-undang yang berlaku.</p>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <form method="POST" action="<?= base_url('/signin'); ?>" accept-charset="UTF-8" data-request="onSignin" class="login content"><input name="_session_key" type="hidden" value="2h5WtJg7GrsbebxS2lSOFnZ9Kugmob2nAYNm5o9d"><input name="_token" type="hidden" value="wCZFZLAejzArrqxk8bAJe3LEexLhYZbQMEM4BR6z">
                                <div class="card">
                                    <div class="card-body">
                                        <h2 class="text-dark py-3">Log in </h2>
                                        <?php if (session()->getFlashData('messages')) : ?>
                                            <div class="alert alert-danger" role="alert">
                                                <?= session()->getFlashData('messages') ?>
                                            </div>
                                        <?php endif; ?>
                                        <?= csrf_field(); ?>
                                        <div class="form-group form-email">
                                            <input type="text" name="username" class="form-control border-0" id="username" placeholder="Username">
                                            <?= $validation->getError('username'); ?>
                                            <label for="username">Username</label>
                                        </div>
                                        <div class="form-group form-pas">
                                            <input type="password" name="password" class="form-control border-0" id="pas" placeholder="Password">
                                            <?= $validation->getError('password'); ?>
                                            <label for="pas">Password</label>
                                        </div>
                                        <br>
                                        <!-- <div class="card p-2" id="captcha">
                                            <div class="row m-0 align-items-end">
                                                <div class="d-flex align-items-start">
                                                    
                                                </div>
                                                <div>

                                                </div>
                                            </div>
                                        </div> -->

                                        <div class="form-row pt-2">
                                            <div class="col-md-6">
                                                <!-- <div class="check text-blue">
                                                    <input class="checkbox-bpk" id="styled-checkbox-1" type="checkbox" name="" value="1">
                                                    <label for="styled-checkbox-1">Ingatkan Saya</label>
                                                </div> -->
                                            </div>
                                            <div class="col-md-6 text-right">
                                                <a href="<?= base_url('forgot-password'); ?>" class="text-blue"> Lupa Password?</a>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-3 py-2">
                                                <button type="submit" class="btn btn-primary btn-md"> Masuk </button>
                                            </div>
                                            <!-- <div class="col-md-3 py-2">
                                                <a type="button " class="btn btn-outline-primary btn-md" href="#">Daftar</a>
                                            </div> -->
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="background-color: transparent; height: 320px; display: block;"></div>
    </section>

    <div class="stripe-loading-indicator loaded">
        <div class="stripe"></div>
        <div class="stripe-loaded"></div>
    </div>
</body>

</html>