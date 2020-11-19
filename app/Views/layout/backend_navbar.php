<div id="main-nav" class="hidden-phone hidden-tablet">
    <ul>
        <?php if (in_array('dashboard', session()->get('user_permissions'))) : ?>
            <li>
                <a href="<?= base_url('/dashboard'); ?>" <?= (($active == 'dashboard')) ? 'class="selected"' : ''; ?>>
                    <span class="fs1" aria-hidden="true" data-icon="&#xe0a0;"></span> Dashboard
                </a>
            </li>
        <?php endif; ?>
        <?php if (in_array('home', session()->get('user_permissions'))) : ?>
            <li>
                <a href="<?= base_url('/home'); ?>" <?= (($active == 'home')) ? 'class="selected"' : ''; ?>>
                    <span class="fs1" aria-hidden="true" data-icon="&#xe0a0;"></span> Home
                </a>
            </li>
        <?php endif; ?>
        <?php if (in_array('laporan', session()->get('user_permissions'))) : ?>
            <li>
                <a href="#" <?= (($active == 'laporan')) ? 'class="selected"' : ''; ?>>
                    <span class="fs1" aria-hidden="true" data-icon="&#xe0b3;"></span> Laporan
                </a>
                <ul>
                    <?php if (in_array('laporan', session()->get('user_permissions'))) : ?>
                        <li>
                            <a href="<?= base_url('/laporan'); ?>">Laporan</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endif; ?>
        <?php if (
            in_array('auditor', session()->get('user_permissions')) ||
            in_array('auditi', session()->get('user_permissions')) ||
            in_array('satuankerja', session()->get('user_permissions'))
        ) : ?>
            <li>
                <a href="#" <?= (($active == 'auditor') || ($active == 'auditi') || ($active == 'satuankerja')) ? 'class="selected"' : ''; ?>>
                    <span class="fs1" aria-hidden="true" data-icon="&#xe020;"></span> Master Data
                </a>
                <ul>
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
            </li>
        <?php endif; ?>
        <?php if (in_array('user', session()->get('user_permissions')) || in_array('group', session()->get('user_permissions')) || in_array('permission', session()->get('user_permissions'))) : ?>
            <li>
                <a href="#" <?= (($active == 'user') || ($active == 'group') || ($active == 'permission')) ? 'class="selected"' : ''; ?>>
                    <span class="fs1" aria-hidden="true" data-icon="&#xe075;"></span> Users Management
                </a>
                <ul>
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
            </li>
        <?php endif; ?>
        <?php if (in_array('org', session()->get('user_permissions')) || in_array('employee', session()->get('user_permissions')) || in_array('position', session()->get('user_permissions')) || in_array('section', session()->get('user_permissions')) || in_array('department', session()->get('user_permissions'))) : ?>
            <li>
                <a href="#" <?= (($active == 'org') || ($active == 'employee') || ($active == 'position') || ($active == 'section') || ($active == 'department')) ? 'class="selected"' : ''; ?>>
                    <span class="fs1" aria-hidden="true" data-icon="&#xe0b9;"></span> Organization Structure
                </a>
                <ul>
                    <?php if (in_array('directorate', session()->get('user_permissions'))) : ?>
                        <li>
                            <a href="<?= base_url('/directorate'); ?>">Directorate</a>
                        </li>
                    <?php endif; ?>
                    <?php if (in_array('division', session()->get('user_permissions'))) : ?>
                        <li>
                            <a href="<?= base_url('/division'); ?>">Division</a>
                        </li>
                    <?php endif; ?>
                    <?php if (in_array('department', session()->get('user_permissions'))) : ?>
                        <li>
                            <a href="<?= base_url('/department'); ?>">Department</a>
                        </li>
                    <?php endif; ?>
                    <?php if (in_array('section', session()->get('user_permissions'))) : ?>
                        <li>
                            <a href="<?= base_url('/section'); ?>">Section</a>
                        </li>
                    <?php endif; ?>
                    <?php if (in_array('position', session()->get('user_permissions'))) : ?>
                        <li>
                            <a href="<?= base_url('/position'); ?>">Position</a>
                        </li>
                    <?php endif; ?>
                    <?php if (in_array('employee', session()->get('user_permissions'))) : ?>
                        <li>
                            <a href="<?= base_url('/employee'); ?>">Employee</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endif; ?>
        <li style="float: right;background: #fff;color: #fff;width: 150px;">
            <a href="<?= base_url('profile'); ?>" data-original-title="" style="color: #000;">
                <span class="fs1">
                    <img src="<?= '/images/' . session()->get('image'); ?>" class="avatar hoverZoomLink" alt="Avatar" style="height: 20px;border: 2px solid #428bca;">
                </span> <?= session()->get('name'); ?>
            </a>
        </li>
    </ul>
    <div class="clearfix"></div>
</div>