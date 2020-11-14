<?= $this->extend('layout/backend_template'); ?>

<?= $this->section('backend_content'); ?>

<div class="row-fluid">
    <div class="span12">
        <div class="widget no-margin">
            <div class="widget-header">
                <div class="title">
                    <span class="fs1" aria-hidden="true" data-icon="&#xe075;"></span> Edit Profil
                </div>
            </div>
            <div class="widget-body">
                <div class="row-fluid">
                    <form action="<?= base_url('profile/update') ?>" method="post" class="form-horizontal no-margin" enctype="multipart/form-data">
                        <div class="span2">
                            <div class="thumbnail">
                                <img alt="300x200" src="<?= 'images/' . $data->image; ?>" class="img-thumbnail img-preview">
                                <div class="caption">
                                    <a href="#" data-type="text" data-pk="1" data-original-title="Edit your Nick Name" class="editable editable-click inputText" style="margin-bottom: 10px;">
                                        <?= session()->get('name'); ?>
                                    </a>
                                    <p class="no-margin">
                                        <input class="input-group" type="file" name="image" id="image" onchange="previewImage();">
                                        <!-- <a href="#" class="btn btn-info">
                                            Edit
                                        </a>

                                        <a href="#" class="btn">
                                            Cancel
                                        </a> -->
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="span10">
                            <h5>
                                Login Information
                            </h5>
                            <?php if (session()->getFlashData('messages_error')) : ?>
                                <div class="alert alert-danger" role="alert">
                                    <?= session()->getFlashData('messages_error') ?>
                                </div>
                            <?php endif; ?>
                            <?php if (session()->getFlashData('messages_success')) : ?>
                                <div class="alert alert-success" role="alert">
                                    <?= session()->getFlashData('messages_success') ?>
                                </div>
                            <?php endif; ?>
                            <hr>
                            <?= csrf_field(); ?>
                            <?= input_hidden('old_image', $data->image); ?>
                            <?= input_text('username', 'Username', $data->username, true, true) ?>
                            <?= input_email('email', 'Email', $data->email, true, false) ?>
                            <?= input_password('password', 'New Password', false) ?>
                            <?= input_password('repeat_password', 'Repeate Password', 'password', false) ?>

                            <br />
                            <h5>
                                Personal Information
                            </h5>
                            <hr>
                            <?php input_text('name', 'Nama Lengkap', $data->name, true, false) ?>

                            <div class="form-actions no-margin">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="button" class="btn" onclick="window.history.back();">Cancel</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage() {
        const image = document.querySelector('#image');
        // const imageLabel = document.querySelector('.custom-file-label');
        const imgPreview = document.querySelector('.img-preview');

        // imageLabel.textContent = image.files[0].name;

        const fileImage = new FileReader();
        fileImage.readAsDataURL(image.files[0]);

        fileImage.onload = function(e) {
            imgPreview.src = e.target.result;
        }
    }
</script>

<?= $this->endSection(); ?>