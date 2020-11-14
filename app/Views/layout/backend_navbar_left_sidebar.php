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
            <div class="accordion-group">
                <div class="accordion-heading">
                    <a href="#collapseTwo" data-parent="#accordion1" data-toggle="collapse" class="accordion-toggle">
                        Master Data <span class="label info-label label-warning">&nbsp;</span>
                    </a>
                </div>
                <div class="accordion-body collapse" id="collapseTwo" style="height: 0px;">
                    <div class="accordion-inner">
                        <ul class="inbox">
                            <li>
                                <a href="<?= base_url('/product'); ?>" class="designation">Products</a>
                            </li>
                            <li>
                                <a href="<?= base_url('/color'); ?>" class="designation">Fabric Colors</a>
                            </li>
                            <li>
                                <a href="<?= base_url('/category'); ?>" class="designation">Categories</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="accordion-group">
                <div class="accordion-heading">
                    <a href="#collapseSix" data-parent="#accordion1" data-toggle="collapse" class="accordion-toggle">
                        Users Management <span class="label info-label">&nbsp;</span>
                    </a>
                </div>
                <div class="accordion-body collapse" id="collapseSix" style="height: 0px;">
                    <div class="accordion-inner">
                        <ul class="contents">
                            <li>
                                <a href="<?= base_url('/user'); ?>">Users</a>
                            </li>
                            <li>
                                <a href="<?= base_url('/group'); ?>">Groups</a>
                            </li>
                            <li>
                                <a href="<?= base_url('/permission'); ?>">Permissions</a>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>