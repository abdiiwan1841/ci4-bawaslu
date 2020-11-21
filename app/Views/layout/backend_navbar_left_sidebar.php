<style>
    .accordion-heading .accordion-toggle {
        display: block;
        padding: 6px 12px;
        color: #ffffff;
        background-color: #3a86c8;
        /* background-color: #000; */
        background-image: unset;
        font-size: 13px;
    }

    .left-sidebar .content .accordion .accordion-group {
        margin-bottom: 0px;
        border: unset;
        border-bottom: 1px solid #5697d0;
        border-color: #fff;
    }

    .accordion-toggle {
        cursor: default;
    }

    .left-sidebar .signups li:last-child,
    .left-sidebar .clients li:last-child,
    .left-sidebar .chats li:last-child,
    .left-sidebar .inbox li:last-child,
    .left-sidebar .payments li:last-child,
    .left-sidebar .staff li:last-child,
    .left-sidebar .contents li:last-child {
        color: #3a86c8;
    }
</style>
<div class="left-sidebar hidden-tablet hidden-phone">
    <div class="user-details">
        <div class="user-img">
            <img src="<?= '/images/' . session()->get('image'); ?>" class="avatar" alt="Avatar">
        </div>
        <div class="welcome-text">
            <span><?= session()->get('group_name'); ?></span>
            <p class="name"><?= session()->get('name'); ?></p>
        </div>
    </div>
    <div class="content">
        <div id="accordion1" class="accordion no-margin">
            <?php if (
                in_array('dashboard', session()->get('user_permissions')) ||
                in_array('home', session()->get('user_permissions'))
            ) : ?>
                <div class="accordion-group">
                    <div class="accordion-heading">
                        <span data-parent="#accordion1" data-toggle="collapse" class="accordion-toggle">
                            <span class="fs1" aria-hidden="true" data-icon="&#xe0a0;"></span>&nbsp; Home
                        </span>
                    </div>
                    <div class="accordion-body in collapse" id="collapseTwo" style="height: auto;">
                        <div class="accordion-inner">
                            <ul class="inbox">
                                <?php if (in_array('dashboard', session()->get('user_permissions'))) : ?>
                                    <li>
                                        <a href="<?= base_url('/dashboard'); ?>">
                                            Dashboard
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <?php if (in_array('home', session()->get('user_permissions'))) : ?>
                                    <li>
                                        <a href="<?= base_url('/home'); ?>">Home</a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (
                in_array('laporan', session()->get('user_permissions')) ||
                in_array('laporanauditee', session()->get('user_permissions'))
            ) : ?>
                <div class="accordion-group">
                    <div class="accordion-heading">
                        <span data-parent="#accordion1" data-toggle="collapse" class="accordion-toggle">
                            <span class="fs1" aria-hidden="true" data-icon="&#xe0b3;"></span>&nbsp; Laporan
                        </span>
                    </div>
                    <div class="accordion-body in collapse" id="collapseTwo" style="height: auto;">
                        <div class="accordion-inner">
                            <ul class="inbox">
                                <?php if (in_array('laporan', session()->get('user_permissions'))) : ?>
                                    <li>
                                        <a href="<?= base_url('/laporan'); ?>">Laporan</a>
                                    </li>
                                <?php endif; ?>
                                <?php if (in_array('laporanauditee', session()->get('user_permissions'))) : ?>
                                    <li>
                                        <a href="<?= base_url('/laporanauditee'); ?>">Laporan Auditee</a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (
                in_array('auditor', session()->get('user_permissions')) ||
                in_array('auditi', session()->get('user_permissions')) ||
                in_array('satuankerja', session()->get('user_permissions'))
            ) : ?>
                <div class="accordion-group">
                    <div class="accordion-heading">
                        <span data-parent="#accordion1" data-toggle="collapse" class="accordion-toggle">
                            <span class="fs1" aria-hidden="true" data-icon="&#xe020;"></span>&nbsp; Master Data
                        </span>
                    </div>
                    <div class="accordion-body in collapse" id="collapseTwo" style="height: auto;">
                        <div class="accordion-inner">
                            <ul class="inbox">
                                <?php if (in_array('auditor', session()->get('user_permissions'))) : ?>
                                    <li>
                                        <a href="<?= base_url('/auditor'); ?>">Auditor</a>
                                    </li>
                                <?php endif; ?>
                                <?php if (in_array('auditi', session()->get('user_permissions'))) : ?>
                                    <li>
                                        <a href="<?= base_url('/auditi'); ?>">Auditi</a>
                                    </li>
                                <?php endif; ?>
                                <?php if (in_array('satuankerja', session()->get('user_permissions'))) : ?>
                                    <li>
                                        <a href="<?= base_url('/satuankerja'); ?>">Satuan Kerja</a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (in_array('user', session()->get('user_permissions')) || in_array('group', session()->get('user_permissions')) || in_array('permission', session()->get('user_permissions'))) : ?>
                <div class="accordion-group">
                    <div class="accordion-heading">
                        <span data-parent="#accordion1" data-toggle="collapse" class="accordion-toggle">
                            <span class="fs1" aria-hidden="true" data-icon="&#xe075;"></span>&nbsp; Users Management
                        </span>
                    </div>
                    <div class="accordion-body in collapse" id="collapseSix" style="height: auto;">
                        <div class="accordion-inner">
                            <ul class="contents">
                                <?php if (in_array('user', session()->get('user_permissions'))) : ?>
                                    <li>
                                        <a href="<?= base_url('/user'); ?>">Users</a>
                                    </li>
                                <?php endif; ?>
                                <?php if (in_array('group', session()->get('user_permissions'))) : ?>
                                    <li>
                                        <a href="<?= base_url('/group'); ?>">Groups</a>
                                    </li>
                                <?php endif; ?>
                                <?php if (in_array('permission', session()->get('user_permissions'))) : ?>
                                    <li>
                                        <a href="<?= base_url('/permission'); ?>">Permissions</a>
                                    </li>
                                <?php endif; ?>
                            </ul>

                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>