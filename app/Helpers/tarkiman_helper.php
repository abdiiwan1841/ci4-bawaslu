<?php

function get_uuid($prefix = '')
{
    $chars = md5(uniqid(mt_rand(), true));
    $uuid = substr($chars, 0, 8) . '-';
    $uuid .= substr($chars, 8, 4) . '-';
    $uuid .= substr($chars, 12, 4) . '-';
    $uuid .= substr($chars, 16, 4) . '-';
    $uuid .= substr($chars, 20, 12);
    return $prefix . $uuid;
}

if (!function_exists('format_number')) {

    function format_number($number, $rp = false)
    {
        if (is_numeric($number)) {
            $_rp = $rp ? 'Rp ' . number_format($number, 2, '.', ',') : number_format($number, 0, '.', ',');
            return $_rp;
        }
        return '';
    }
}

function date_convert($date, $time = false)
{
    $_time = ($time) ? date_format(date_create($date), "H:i:s") : "";
    $date = date('Y-m-d', strtotime($date)); // ubah sesuai format penanggalan

    // standart
    $bulan = array(
        '01' => 'Januari', // array bulan konversi
        '02' => 'Februari',
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember'
    );
    $date = explode('-', $date); // ubah string menjadi array dengan parameter
    // '-'

    return $date[2] . ' ' . $bulan[$date[1]] . ' ' . $date[0] . ' ' . $_time; // hasil yang di
    // kembalikan
}

function getMonthName($date)
{
    $ret = '';
    switch ($date) {
        case '1':
            $ret = 'Januari';
            break;
        case '2':
            $ret = 'Februari';
            break;
        case '3':
            $ret = 'Maret';
            break;
        case '4':
            $ret = 'April';
            break;
        case '5':
            $ret = 'Mei';
            break;
        case '6':
            $ret = 'Juni';
            break;
        case '7':
            $ret = 'Juli';
            break;
        case '8':
            $ret = 'Agustus';
            break;
        case '9':
            $ret = 'September';
            break;
        case '10':
            $ret = 'Oktober';
            break;
        case '11':
            $ret = 'November';
            break;
        case '12':
            $ret = 'Desember';
            break;
        case '':
            $ret = '-';
        default:
            $ret = "-";
            break;
    }
    return $ret;
}

function input_text($field_name = '', $label = '', $value = '', $required = false, $readonly = false, $disabled = false)
{
    $validation = \Config\Services::validation();

    $value = (old($field_name)) ? old($field_name) : $value;

    $value = is_null($value) ? '' : $value;

    $hashError = $validation->hasError($field_name) ? 'error' : '';
    $hashRequired = ($required) ? '<font color="red"> *</font>' : '';

    $data = [
        'name'      => $field_name,
        'id'        => $field_name,
        // 'value'     => $value,
        'placeholder' => 'Please Input ' . $label,
        'class'     => "input-xlarge"
    ];

    (!$readonly) ?: $data['readonly'] = true;
    (!$disabled) ?: $data['disabled'] = true;

    echo '<div class="control-group ' . $hashError . '">';
    echo '<label class="control-label">' . $label . $hashRequired . '</label>';
    echo '<div class="controls controls-row">';
    echo form_input($data, $value);
    echo $validation->hasError($field_name) ? '<span class="help-inline ">' . $validation->getError($field_name) . '</span>' : '';
    echo '</div>';
    echo '</div>';
}

function input_number($field_name = '', $label = '', $value = '', $required = false, $readonly = false, $disabled = false)
{
    $validation = \Config\Services::validation();

    $value = (old($field_name)) ? old($field_name) : $value;

    $value = is_null($value) ? '' : $value;

    $hashError = $validation->hasError($field_name) ? 'error' : '';
    $hashRequired = ($required) ? '<font color="red"> *</font>' : '';

    $data = [
        'name'      => $field_name,
        'id'        => $field_name,
        // 'value'     => $value,
        'placeholder' => 'Please Input ' . $label,
        'class'     => "tarkiman input-xlarge"
    ];

    (!$readonly) ?: $data['readonly'] = true;
    (!$disabled) ?: $data['disabled'] = true;

    echo '<div class="control-group ' . $hashError . '">';
    echo '<label class="control-label">' . $label . $hashRequired . '</label>';
    echo '<div class="controls controls-row">';
    echo form_input($data, $value);
    echo $validation->hasError($field_name) ? '<span class="help-inline ">' . $validation->getError($field_name) . '</span>' : '';
    echo '</div>';
    echo '</div>';
}

function input_date($field_name = '', $label = '', $value = '', $required = false, $readonly = false, $disabled = false)
{
    $validation = \Config\Services::validation();

    $value = (old($field_name)) ? old($field_name) : $value;

    $hashError = $validation->hasError($field_name) ? 'error' : '';
    $hashRequired = ($required) ? '<font color="red"> *</font>' : '';

    $data = [
        'name'      => $field_name,
        'id'        => $field_name,
        // 'value'     => $value,
        'placeholder' => 'Please Input ' . $label,
        'class'     => "input-xlarge date_picker"
    ];

    (!$readonly) ?: $data['readonly'] = true;
    (!$disabled) ?: $data['disabled'] = true;


    echo '<div class="control-group ' . $hashError . '">';
    echo '<label class="control-label">' . $label . $hashRequired . '</label>';
    echo '<div class="controls controls-row">';
    echo '<div class="input-append">';
    echo form_input($data, $value);
    echo '<span class="add-on">';
    echo '<i class="icon-calendar"></i>';
    echo '</span>';
    echo '</div>';
    echo $validation->hasError($field_name) ? '<span class="help-inline ">' . $validation->getError($field_name) . '</span>' : '';
    echo '</div>';
    echo '</div>';
}

function input_password($field_name = '', $label = '', $value = '', $required = false, $readonly = false, $disabled = false)
{
    $validation = \Config\Services::validation();

    $value = (old($field_name)) ? old($field_name) : $value;

    $hashError = $validation->hasError($field_name) ? 'error' : '';
    $hashRequired = ($required) ? '<font color="red"> *</font>' : '';

    $data = [
        'name'      => $field_name,
        'id'        => $field_name,
        // 'value'     => $value,
        'placeholder' => 'Please Input ' . $label,
        'class'     => "input-xlarge"
    ];

    (!$readonly) ?: $data['readonly'] = true;
    (!$disabled) ?: $data['disabled'] = true;

    echo '<div class="control-group ' . $hashError . '">';
    echo '<label class="control-label">' . $label . $hashRequired . '</label>';
    echo '<div class="controls controls-row">';
    echo form_password($data, $value);
    echo $validation->hasError($field_name) ? '<span class="help-inline ">' . $validation->getError($field_name) . '</span>' : '';
    echo '</div>';
    echo '</div>';
}

function input_email($field_name = '', $label = '', $value = '', $required = false, $readonly = false, $disabled = false)
{
    $validation = \Config\Services::validation();

    $value = (old($field_name)) ? old($field_name) : $value;

    $hashError = $validation->hasError($field_name) ? 'error' : '';
    $hashRequired = ($required) ? '<font color="red"> *</font>' : '';

    $data = [
        'name'      => $field_name,
        'id'        => $field_name,
        // 'value'     => $value,
        'placeholder' => 'Please Input ' . $label,
        'class'     => "input-xlarge"
    ];

    (!$readonly) ?: $data['readonly'] = true;
    (!$disabled) ?: $data['disabled'] = true;

    echo '<div class="control-group ' . $hashError . '">';
    echo '<label class="control-label">' . $label . $hashRequired . '</label>';
    echo '<div class="controls">';
    echo '<div class="input-prepend">';
    echo '<span class="add-on">@</span>';
    echo form_input($data, $value);
    echo $validation->hasError($field_name) ? '<span class="help-inline ">' . $validation->getError($field_name) . '</span>' : '';
    echo '</div>';
    echo '</div>';
    echo '</div>';
}

function input_hidden($field_name = '', $value = '')
{
    $value = (old($field_name)) ? old($field_name) : $value;
    // echo form_hidden($field_name, $value);
    echo '<input type="hidden" name="' . $field_name . '" id="' . $field_name . '" value="' . $value . '">';
}


function input_image($field_name = '', $label = '', $file_name = 'default.png', $required = false, $readonly = false, $path = 'images')
{

    $validation = \Config\Services::validation();

    $hashError = $validation->hasError($field_name) ? 'error' : '';
    $hashRequired = ($required) ? '<font color="red"> *</font>' : '';

    $data = [
        'name'      => $field_name,
        'id'        => $field_name,
        'value'     => $file_name,
        'onchange'  => 'previewImage_' . $field_name . '();'
    ];

    $file_name = ($file_name) ? $file_name : 'default.png';

    echo '<div class="control-group ' . $hashError . '"">';
    echo '<label class="control-label" for="' . $field_name . '">' . $label . $hashRequired . '</label>';
    echo '<div class="controls controls-row">';
    echo '<a class="thumbnail-img span2" data-gallery="gallery" href="' . base_url('images/' . $file_name) . '" target="_blank" data-original-title="' . $file_name . '">';
    echo '<img src="' . base_url($path . '/' . $file_name) . '" class="img-thumbnail img-preview" style="max-width: 200px;border: 1px solid #ddd;border-radius: 8px;padding: 5px;">';
    echo '</a>';
    echo '</div>';
    echo '<div class="controls controls-row">';
    echo '<br/>';
    if (!$readonly) {
        echo form_upload($data, $file_name, $extra = array());
        echo $validation->hasError($field_name) ? '<span class="help-inline ">' . $validation->getError($field_name) . '</span>' : '';
        // echo '<label class="custom-file-label" for="image">Choose Image</label>';
    }
    echo '</div>';
    echo '</div>';

    // $value = (old($field_name)) ? old($field_name) : $value;
    // echo form_upload($data = array(), $value, $extra = array());

    echo '<script>';
    echo '    function previewImage_' . $field_name . '() {';
    echo '        const image = document.querySelector("#' . $field_name . '");';
    // echo '        const imageLabel = document.querySelector(".custom-file-label");';
    echo '        const imgPreview = document.querySelector(".img-preview");';
    // echo '        imageLabel.textContent = image.files[0].name;';
    echo '        const fileImage = new FileReader();';
    echo '        fileImage.readAsDataURL(image.files[0]);';
    echo '        fileImage.onload = function(e) {';
    echo '            imgPreview.src = e.target.result;';
    echo '        }';
    echo '    }';
    echo '</script>';
}

function input_file($field_name = '', $label = '', $file_name = 'default.png', $required = false, $readonly = false, $path = 'images', $tips = ".jpg | .jpeg | .png")
{

    $validation = \Config\Services::validation();

    $hashError = $validation->hasError($field_name) ? 'error' : '';
    $hashRequired = ($required) ? '<font color="red"> *</font>' : '';

    $data = [
        'name'      => $field_name,
        'id'        => $field_name,
        'value'     => $file_name,
        'onchange'  => 'previewImage_' . $field_name . '();'
    ];

    $file_name = ($file_name) ? $file_name : 'default.png';

    echo '<div class="control-group ' . $hashError . '"">';
    echo '<label class="control-label" for="' . $field_name . '">' . $label . $hashRequired . '</label>';
    echo '<div class="controls controls-row">';
    if (!$readonly) {
        echo form_upload($data, $file_name, $extra = array());
        echo $validation->hasError($field_name) ? '<span class="help-inline ">' . $validation->getError($field_name) . '</span>' : '';
        echo '<label class="custom-file-label" style="font-size: 11px;color: #3a86c8;" for="image">' . $tips . '</label>';
    } else {
        echo '<a href="' . base_url($path . '/' . $file_name) . '"  target="_blank" style="color:#3a86c8;"><span class="fs1 text-info" aria-hidden="true" data-icon="&#xe0c5;"></span> ' . $file_name . '</a>';
    }
    echo '</div>';
    echo '</div>';
}

function input_select($field_name, $label, $options, $selected = '', $required = false, $disabled = false)
{
    $validation = \Config\Services::validation();

    $hashError = $validation->hasError($field_name) ? 'error' : '';
    $hashRequired = ($required) ? '<font color="red"> *</font>' : '';

    $selected = (old($field_name)) ? old($field_name) : $selected;

    $data = [
        'name'      => $field_name,
        'id'        => $field_name,
        'placeholder' => 'Please Input ' . $label,
        // 'class'     => 'input-xlarge chosen-select',
        'class'     => 'input-xlarge',
    ];

    (!$disabled) ?: $data['disabled'] = true;

    $req = ($required) ? '<font color="red"> * </font>' : '';
    $classRequired = ($required) ? 'required' : '';

    echo '<div class="control-group ' . $hashError . '"">';
    echo '    <label class="control-label">' . $label . $hashRequired . '</label>';
    echo '    <div class="controls">';
    echo form_dropdown($field_name, $options, $selected, $data);
    echo $validation->hasError($field_name) ? '<span class="help-inline ">' . $validation->getError($field_name) . '</span>' : '';
    echo '    </div>';
    echo '</div>';
}

function input_multiselect($field_name, $label, $options = array(), $selected = array(), $required = false, $readonly = false)
{
    $validation = \Config\Services::validation();

    $hashError = $validation->hasError($field_name) ? 'error' : '';
    $hashRequired = ($required) ? '<font color="red"> *</font>' : '';

    //$selected = (old($field_name)) ? old($field_name) : $selected;

    // REFF : https://harvesthq.github.io/chosen/?utm_campaign=cdnjs_library&utm_medium=cdnjs_link&utm_source=cdnjs

    $data = [
        'name'      => $field_name,
        'id'        => $field_name,
        'placeholder' => 'Please Select ' . $label,
        'class'     => 'select2-container input-block-level',
        // 'class'     => 'chosen-select input-block-level',
        'multiple'     => 'true'
    ];

    $req = ($required) ? '<font color="red"> * </font>' : '';
    $classRequired = ($required) ? 'required' : '';

    if (!$readonly) {
        echo '<div class="control-group ' . $hashError . '"">';
        echo '<label class="control-label" for="' . $field_name . '">' . $label . $hashRequired . '</label>';
        echo '<div class="controls">';
        echo form_dropdown($field_name, $options, $selected, $data);
        echo $validation->hasError($field_name) ? '<span class="help-inline ">' . $validation->getError($field_name) . '</span>' : '';
        echo '</div>';
        echo '</div>';
    } else {

        echo '<div class="control-group">';
        echo '<label class="control-label" for="' . $field_name . '">' . $label . $hashRequired . '</label>';
        echo '<div class="controls controls-row">';

        $_selected = count($selected);
        if ($_selected > 0) {
            $_result = "";
            foreach ($selected as $s) {
                $_result .= '<span class="badge badge-info">' . $options[$s] . '</span> &nbsp;';
            }
            echo $_result;
        } else {
            echo '<font color="red"> - </font>';
        }
        echo '</div>';
        echo '</div>';
    }
}

function input_textarea($field_name, $label, $value = '', $required = false, $readonly = false, $disabled = false)
{
    $validation = \Config\Services::validation();

    $hashError = $validation->hasError($field_name) ? 'error' : '';
    $hashRequired = ($required) ? '<font color="red"> *</font>' : '';

    $value = is_null($value) ? '' : $value;

    $value = (old($field_name)) ? old($field_name) : $value;

    $data = [
        'name'      => $field_name,
        'id'        => $field_name,
        'placeholder' => 'Please Input ' . $label,
        'class'     => 'input-block-level no-margin',
        'style'     => 'height: 75px;'
    ];

    (!$readonly) ?: $data['readonly'] = true;
    (!$disabled) ?: $data['disabled'] = true;

    $req = ($required) ? '<font color="red"> * </font>' : '';
    $classRequired = ($required) ? 'required' : '';


    echo '<div class="control-group ' . $hashError . '"">';
    echo '    <label class="control-label">' . $label . $hashRequired . '</label>';
    echo '    <div class="controls">';
    echo form_textarea($field_name, $value, $data);
    echo $validation->hasError($field_name) ? '<span class="help-inline ">' . $validation->getError($field_name) . '</span>' : '';
    echo '    </div>';
    echo '</div>';
}

function link_new($uri)
{
    if (in_array($uri, session()->get('user_permissions'))) {

        $html = '
        <a href="' . base_url($uri) . '">
            <li class="color-first">
              <span class="fs1" aria-hidden="true" data-icon="&#xe0b3;"></span>
              <div class="details">
                <span class="big">Create</span>
                <span>New</span>
              </div>
            </li>
        </a>';
        return $html;
    } else {
        return "";
    }
}

function link_detail($uri, $id)
{
    if (in_array($uri, session()->get('user_permissions'))) {

        return anchor(
            base_url($uri . '/' . $id),
            '<i class="icon-zoom-in"></i>',
            array(
                'class' => 'btn btn-success btn-small',
                'data-original-title' => 'view'
            )
        );

        // return anchor(
        //     base_url($uri . '/' . $id),
        //     'View',
        //     array(
        //         'class' => 'btn btn-success',
        //         'data-original-title' => 'view'
        //     )
        // );

    } else {
        return "";
    }
}

function link_edit($uri, $id)
{
    if (in_array($uri, session()->get('user_permissions'))) {

        return anchor(
            base_url($uri . '/' . $id),
            '<i class="icon-pencil"></i>',
            array(
                'class' => 'btn btn-primary btn-small',
                'data-original-title' => 'Edit'
            )
        );

        // return anchor(
        //     base_url($uri . '/' . $id),
        //     'Edit',
        //     array(
        //         'class' => 'btn btn-primary',
        //         'data-original-title' => 'Edit'
        //     )
        // );
    } else {
        return "";
    }
}

function link_delete($uri, $id)
{
    if (in_array($uri, session()->get('user_permissions'))) {

        return anchor(
            base_url($uri . '/' . $id),
            '<i class="icon-remove"></i>',
            array(
                'class' => 'btn btn-danger btn-small',
                'onclick' => "return confirm('sure to delete this data');",
                'data-original-title' => 'Remove'
            )
        );

        // return anchor(
        //     base_url($uri . '/' . $id),
        //     'Delete',
        //     array(
        //         'class' => 'btn btn-danger',
        //         'onclick' => "return confirm('sure to delete this data');",
        //         'data-original-title' => 'Delete'
        //     )
        // );
    } else {
        return "";
    }
}


function tarkiman_datatables($table, $columns, $condition, $primaryKey = 'id')
{

    require_once APPPATH . 'Libraries/ssp.class.php';

    $db      = \Config\Database::connect();

    $sql_details = array(
        'user' => $db->username,
        'pass' => $db->password,
        'db'   => $db->database,
        'host' => $db->hostname
    );

    echo json_encode(
        SSP::complex($_GET, $sql_details, $table, $primaryKey, $columns, null, $condition)
    );
}
