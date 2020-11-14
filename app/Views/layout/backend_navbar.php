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
                    <span class="fs1" aria-hidden="true" data-icon="&#xe1c7;"></span> Laporan
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
        <?php if (in_array('returin', session()->get('user_permissions')) || in_array('returout', session()->get('user_permissions'))) : ?>
            <li>
                <a href="#" <?= (($active == 'returin') || ($active == 'retur_out')) ? 'class="selected"' : ''; ?>>
                    <span class="fs1" aria-hidden="true" data-icon="&#xe12c;"></span> Retur Product
                </a>
                <ul>
                    <?php if (in_array('returin', session()->get('user_permissions'))) : ?>
                        <li>
                            <a href="<?= base_url('/returin'); ?>">Retur IN</a>
                        </li>
                    <?php endif; ?>
                    <?php if (in_array('returout', session()->get('user_permissions'))) : ?>
                        <li>
                            <a href="<?= base_url('/returout'); ?>">Retur OUT</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endif; ?>
        <?php if (
            in_array('stock', session()->get('user_permissions')) ||
            in_array('stock/in', session()->get('user_permissions')) ||
            in_array('stock/out', session()->get('user_permissions')) ||
            in_array('stock/tamim', session()->get('user_permissions')) ||
            in_array('stock/returin', session()->get('user_permissions')) ||
            in_array('stock/returout', session()->get('user_permissions'))
        ) : ?>
            <li>
                <a href="#" <?= (
                                ($active == 'stock') ||
                                ($active == 'stock/in') ||
                                ($active == 'stock/out') ||
                                ($active == 'stock/tamim') ||
                                ($active == 'stock/returin') ||
                                ($active == 'stock/returout')) ? 'class="selected"' : ''; ?>>
                    <span class="fs1" aria-hidden="true" data-icon="&#xe0b3;"></span> Stock
                </a>
                <ul>
                    <?php if (in_array('stock/in', session()->get('user_permissions'))) : ?>
                        <li>
                            <a href="<?= base_url('/stock/in'); ?>">Stock In</a>
                        </li>
                    <?php endif; ?>
                    <?php if (in_array('stock/tamim', session()->get('user_permissions'))) : ?>
                        <li>
                            <a href="<?= base_url('/stock/tamim'); ?>">Stock Tamim</a>
                        </li>
                    <?php endif; ?>
                    <?php if (in_array('stock/out', session()->get('user_permissions'))) : ?>
                        <li>
                            <a href="<?= base_url('/stock/out'); ?>">Stock Out</a>
                        </li>
                    <?php endif; ?>
                    <?php if (in_array('stock/returin', session()->get('user_permissions'))) : ?>
                        <li>
                            <a href="<?= base_url('/stock/returin'); ?>">Retur In</a>
                        </li>
                    <?php endif; ?>
                    <?php if (in_array('stock/returout', session()->get('user_permissions'))) : ?>
                        <li>
                            <a href="<?= base_url('/stock/returout'); ?>">Retur Out</a>
                        </li>
                    <?php endif; ?>
                    <?php if (in_array('stock', session()->get('user_permissions'))) : ?>
                        <li>
                            <a href="<?= base_url('/stock'); ?>">All Stock</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </li>
        <?php endif; ?>
        <?php if (
            in_array('product', session()->get('user_permissions')) ||
            in_array('color', session()->get('user_permissions')) ||
            in_array('unit', session()->get('user_permissions')) ||
            in_array('category', session()->get('user_permissions')) ||
            in_array('supplier', session()->get('user_permissions'))
        ) : ?>
            <li>
                <a href="#" <?= (($active == 'product') || ($active == 'color') || ($active == 'unit') || ($active == 'category') || ($active == 'supplier')) ? 'class="selected"' : ''; ?>>
                    <span class="fs1" aria-hidden="true" data-icon="&#xe020;"></span> Master Data
                </a>
                <ul>
                    <?php if (in_array('product', session()->get('user_permissions'))) : ?>
                        <li>
                            <a href="<?= base_url('/product'); ?>">Products</a>
                        </li>
                    <?php endif; ?>
                    <?php if (in_array('color', session()->get('user_permissions'))) : ?>
                        <li>
                            <a href="<?= base_url('/color'); ?>">Fabric Colors</a>
                        </li>
                    <?php endif; ?>
                    <?php if (in_array('unit', session()->get('user_permissions'))) : ?>
                        <li>
                            <a href="<?= base_url('/unit'); ?>">Unit</a>
                        </li>
                    <?php endif; ?>
                    <?php if (in_array('category', session()->get('user_permissions'))) : ?>
                        <li>
                            <a href="<?= base_url('/category'); ?>">Categories</a>
                        </li>
                    <?php endif; ?>
                    <?php if (in_array('supplier', session()->get('user_permissions'))) : ?>
                        <li>
                            <a href="<?= base_url('/supplier'); ?>">Suppliers</a>
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