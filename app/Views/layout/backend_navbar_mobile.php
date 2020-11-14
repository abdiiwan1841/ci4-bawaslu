<!-- <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="<?= base_url('/admin'); ?>">Administrator Codeigniter 4 Starter Kit</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="float-right">
                <a class="nav-link d-inline" href="<?= base_url('/profile'); ?>">Profile <span class="sr-only">(current)</span></a> |
                <a class="nav-link d-inline" href="<?= base_url('/logout'); ?>" onclick="return confirm('Sure to logout ?');">Logout (<?= session()->get('name') ?>) <span class="sr-only">(current)</span></a>
            </div>
        </div>
    </div>
</nav> -->

<div class="navbar hidden-desktop">
    <div class="navbar-inner">
        <div class="container">
            <a data-target=".navbar-responsive-collapse" data-toggle="collapse" class="btn btn-navbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <div class="nav-collapse collapse navbar-responsive-collapse">
                <ul class="nav">
                    <?php if (in_array('dashboard', session()->get('user_permissions'))) : ?>
                        <li>
                            <a href="<?= base_url('/dashboard'); ?>" <?= (($active == 'dashboard')) ? 'class="selected"' : ''; ?>>
                                Dashboard
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (in_array('home', session()->get('user_permissions'))) : ?>
                        <li>
                            <a href="<?= base_url('/home'); ?>" <?= (($active == 'home')) ? 'class="selected"' : ''; ?>>
                                Home
                            </a>
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
                    <?php endif; ?>
                    <?php if (
                        in_array('product', session()->get('user_permissions')) ||
                        in_array('color', session()->get('user_permissions')) ||
                        in_array('unit', session()->get('user_permissions')) ||
                        in_array('category', session()->get('user_permissions')) ||
                        in_array('supplier', session()->get('user_permissions'))
                    ) : ?>

                        <?php if (in_array('product', session()->get('user_permissions'))) : ?>
                            <li>
                                <a href="<?= base_url('/product'); ?>">Products</a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if (in_array('user', session()->get('user_permissions')) || in_array('group', session()->get('user_permissions')) || in_array('permission', session()->get('user_permissions'))) : ?>

                        <?php if (in_array('user', session()->get('user_permissions'))) : ?>
                            <li>
                                <a href="<?= base_url('/user'); ?>">Users</a>
                            </li>
                        <?php endif; ?>

                    <?php endif; ?>

                </ul>
            </div>
        </div>
    </div>
</div>