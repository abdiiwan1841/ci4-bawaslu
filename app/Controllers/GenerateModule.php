<?php

/**
 *
 * @author Tarkiman | tarkiman@itasoft.co.id / tarkiman.zone@gmail.com 
 */

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\GenerateModuleModel;

class GenerateModule extends BaseController
{

    protected $generateModuleModel;

    public function __construct()
    {
        $this->generateModuleModel = new GenerateModuleModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Generate Module',
            'active' => 'generate_module',
            'data' => null
        ];

        return view('generate_module/index', $data);
    }

    public function save()
    {
        if (!$this->validate([
            'module' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.',
                    // 'is_unique' => '{field} sudah terdaftar'
                ]
            ]
        ])) {
            return redirect()->to('/generatemodule')->withInput();
        }

        $json = json_decode($this->request->getVar('json_generated'));
        $module = $this->request->getVar('module');

        // dd($json);
        /*https://codeigniter4.github.io/userguide/dbmgmt/forge.html */
        $forge = \Config\Database::forge();

        $fields = array(
            'id' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
            ),
            'created_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'updated_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'deleted_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
            'created_by' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE,
            ),
            'updated_by' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE,
            ),
            'deleted_by' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE,
            )
        );

        try {
            $forge->addField($fields);
            $forge->dropTable(strtolower($module), TRUE);
            $attributes = ['ENGINE' => 'InnoDB'];
            $forge->createTable(strtolower($module), TRUE, $attributes);
            $forge->addKey('id');

            foreach ($json as $r) {
                $field = [
                    $r->name => [
                        'type' => 'VARCHAR',
                        'constraint' => '100',
                        'after' => 'id'
                    ],
                ];
                $forge->addColumn(strtolower($module), $field);
            }

            $this->generateModule($module, $json);
        } catch (\Exception $e) {
            return redirect()->to('/generatemodule')->withInput()->with('messages', $e->getMessage());
        }

        session()->setFlashData('messages', 'New module was successfull created');
        return redirect()->to('/' . strtolower($module));
    }

    public function generateModule($module, $json)
    {

        $phpController = $this->createController($module, $json);
        $phpModel = $this->createModel($module, $json);
        $phpViewIndex = $this->createViewIndex($module, $json);
        $phpViewCreate = $this->createViewCreate($module, $json);
        $phpViewEdit = $this->createViewEdit($module, $json);
        $phpViewDetail = $this->createViewDetail($module, $json);

        $appPath    = dirname(__DIR__, 1);

        $controllersPath = $appPath . '\Controllers\/';
        $modelsPath = $appPath . '\Models\/';
        $viewsPath = $appPath . '\Views\/' . strtolower($module) . '/';

        try {

            @mkdir($viewsPath);

            file_put_contents($controllersPath . ucfirst($module) . '.php', $phpController);
            file_put_contents($modelsPath . ucfirst($module) . 'Model.php', $phpModel);
            file_put_contents($viewsPath . 'index.php', $phpViewIndex);
            file_put_contents($viewsPath . 'create.php', $phpViewCreate);
            file_put_contents($viewsPath . 'edit.php', $phpViewEdit);
            file_put_contents($viewsPath . 'detail.php', $phpViewDetail);
        } catch (\Exception $e) {
            return redirect()->to('/generatemodule')->withInput()->with('messages', $e->getMessage());
        }
    }

    public function createController($module, $json)
    {

        $methodDatatables = $this->createMethodDatatables($module, $json);

        $phpController =
            "<?php

/**
 *
 * @author Tarkiman | tarkiman.zone@gmail.com | +62-852-2224-1987 | https://www.linkedin.com/in/tarkiman
 */

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\\" . ucfirst($module) . "Model;

class " . ucfirst($module) . " extends BaseController
{
    protected \$" . strtolower($module) . "Model;

    public function __construct()
    {
        \$this->" . strtolower($module) . "Model = new " . ucfirst($module) . "Model();
    }

    public function index()
    {
        \$data = [
            'title' => 'List of " . ucfirst($module) . "',
            'active' => '" . strtolower($module) . "',
            'data' => null
        ];

        return view('" . strtolower($module) . "/index', \$data);
    }

    " . $methodDatatables . "

    public function create()
    {

        \$data = [
            'title' => 'Create New " . ucfirst($module) . "',
            'active' => '" . strtolower($module) . "',
            'validation' => \Config\Services::validation()
        ];
        return view('" . strtolower($module) . "/create', \$data);
    }

    public function save()
    {

        \$validation = [

            ";

        foreach ($json as $r) {

            if ($r->required) {
                $phpController = $phpController . "'" . $r->name . "' => ['label' => '" . $r->label . "', 'rules' => 'required','errors' => ['required' => '{field} harus diisi.']],";
            }
        }
        $phpController = $phpController . "];

                        if (!\$this->validate(\$validation)) {
                            return redirect()->to('/" . strtolower($module) . "/create')->withInput();
                        }

                        try {
                            \$db      = \Config\Database::connect();

                            \$db->transStart();

                            \$data = array(
                                'id' => get_uuid(),";

        foreach ($json as $r) {
            $phpController = $phpController . "
                                '" . $r->name . "' => \$this->request->getVar('" . $r->name . "'),";
        }
        $phpController = $phpController . "
            );

            \$this->" . strtolower($module) . "Model->insert(\$data);

            \$db->transComplete();
            if (\$db->transStatus() === FALSE) {
                return redirect()->to('/" . strtolower($module) . "/create')->withInput();
            } else {
                session()->setFlashData('messages', 'new data added successfully');
            }
        } catch (\Exception \$e) {
            return redirect()->to('/" . strtolower($module) . "/create')->withInput()->with('messages', \$e->getMessage());
        }

        return redirect()->to('/" . strtolower($module) . "');
    }

    public function edit(\$id)
    {
        \$data = [
            'title' => 'Edit " . ucfirst($module) . "',
            'active' => '" . strtolower($module) . "',
            'data' => \$this->" . strtolower($module) . "Model->getDataById(\$id),
            'validation' => \Config\Services::validation()
        ];

        return view('" . strtolower($module) . "/edit', \$data);
    }

    public function update(\$id)
    {

        \$validation = [

            ";

        foreach ($json as $r) {

            if ($r->required) {
                $phpController = $phpController . "'" . $r->name . "' => ['label' => '" . $r->label . "', 'rules' => 'required','errors' => ['required' => '{field} harus diisi.']],";
            }
        }
        $phpController = $phpController . "];

        if (!\$this->validate(\$validation)) {
            return redirect()->to('/" . strtolower($module) . "/edit/' . \$id)->withInput()->with('messages', 'Validation Error');
        } else {

            try {
                \$db      = \Config\Database::connect();

                \$db->transStart();

                \$data = array(
                    'id' => \$id,";

        foreach ($json as $r) {
            $phpController = $phpController . "
                    '" . $r->name . "' => \$this->request->getVar('" . $r->name . "'),";
        }
        $phpController = $phpController . "
                );

                \$this->" . strtolower($module) . "Model->save(\$data);

                \$db->transComplete();
                if (\$db->transStatus() === FALSE) {                                 
                    return redirect()->to('/" . strtolower($module) . "/edit/' . \$id)->withInput();                           
                } else {

                    session()->setFlashData('messages', 'Data was successfully updated');
                }
            } catch (\Exception \$e) {
                return redirect()->to('/" . strtolower($module) . "/edit/' . \$id)->withInput()->with('messages', \$e->getMessage());
            }

            return redirect()->to('/" . strtolower($module) . "');
        }
    }

    public function detail(\$id)
    {
        \$data = [
            'title' => 'Detail " . ucfirst($module) . "',
            'active' => '" . strtolower($module) . "',
            'data' => \$this->" . strtolower($module) . "Model->getDataById(\$id),
            'validation' => \Config\Services::validation()
        ];

        if (empty(\$data['data'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data ' . \$id . ' is not found.');
        }
        return view('" . strtolower($module) . "/detail', \$data);
    }

    public function delete(\$id)
    {
        try {
            \$db      = \Config\Database::connect();

            \$db->transStart();

            \$this->" . strtolower($module) . "Model->delete(\$id);
            \$db->transComplete();

            if (\$db->transStatus() === FALSE) {
                return redirect()->to('/" . strtolower($module) . "')->with('messages', 'failed delete data');
            } else {
                session()->setFlashData('messages', 'Data was successfully deleted');
            }
        } catch (\Exception \$e) {
            return redirect()->to('/" . strtolower($module) . "')->with('messages', \$e->getMessage());
        }

        return redirect()->to('/" . strtolower($module) . "');
    }               

}

            ";


        return $phpController;
    }

    function createMethodDatatables($module, $json)
    {

        $datatables = "
        public function datatables(){
        \$table = 
            \"
            (
            SELECT a.id";
        foreach ($json as $r) {
            $datatables = $datatables . ", a." . $r->name;
        }
        $datatables = $datatables . "
            FROM " . strtolower($module) . " a
            WHERE a.deleted_at IS NULL
            ) temp
            \"; 

        \$columns = array(
            array('db' => 'id', 'dt' => 0 ),";

        $n = 1;
        foreach ($json as $r) {

            $datatables = $datatables . "
            array('db' => '" . $r->name . "', 'dt' => " . $n . " ),";

            $n++;
        }


        $datatables = $datatables . "                        
            array(
                'db'        => 'id',
                'dt'        => " . $n . ",
                'formatter' => function( \$i, \$row ) {
                    \$html = \"
                    <center>
                        \" . link_detail('" . strtolower($module) . "/detail', \$i) . \"
                        \" . link_edit('" . strtolower($module) . "/edit', \$i) . \"
                        \" . link_delete('" . strtolower($module) . "/delete', \$i) . \"
                    </center>\";
                    return \$html;
                }
            ),
        );
      
        \$primaryKey = 'id';

        \$condition = null;

        tarkiman_datatables(\$table, \$columns, \$condition, \$primaryKey);


    }";

        return $datatables;
    }

    public function createModel($module, $json)
    {
        $phpModel =
            "<?php

/**
 *
 * @author Tarkiman | tarkiman.zone@gmail.com | +62-852-2224-1987 | https://www.linkedin.com/in/tarkiman
 */

namespace App\Models;

use CodeIgniter\Model;

class " . ucfirst($module) . "Model extends Model
{

    protected \$table      = '" . strtolower($module) . "';
    protected \$primaryKey = 'id';

    protected \$returnType     = 'object';
    protected \$useSoftDeletes = true;

    protected \$allowedFields = [
        'id',";

        foreach ($json as $r) {
            $phpModel = $phpModel . "'" . $r->name . "',";
        }
        $phpModel = $phpModel . "
    ];

    protected \$useTimestamps = true;
    // protected \$createdField  = 'created_at';
    // protected \$updatedField  = 'updated_at';
    // protected \$deletedField  = 'deleted_at';

    // protected \$validationRules    = [];
    // protected \$validationMessages = [];
    // protected \$skipValidation     = false;

    public function getData()
    {
        \$this->select('
        id";
        foreach ($json as $r) {
            $phpModel = $phpModel . "," . $r->name . "";
        }
        $phpModel = $phpModel . "');
        \$this->orderBy('id', 'ASC');
        \$query = \$this->get();
        \$data = \$query->getResult();
        if (isset(\$data)) {
            return \$data;
        }
        return array();
    }

    public function getDataById(\$id)
    {
        \$this->select('
        id";
        foreach ($json as $r) {
            $phpModel = $phpModel . "," . $r->name . "";
        }
        $phpModel = $phpModel . "');
        \$this->orderBy('id', 'ASC');
        \$this->where('id', \$id);
        \$query = \$this->get();
        \$data = \$query->getRow();
        if (isset(\$data)) {
            return \$data;
        }
        return array();
    }
}";
        return $phpModel;
    }


    public function createViewIndex($module, $json)
    {

        $phpView = '<?= $this->extend(\'layout/backend_template\'); ?>

        <?= $this->section(\'backend_content\'); ?>
        
        <link rel="stylesheet" type="text/css" href="<?= \'/assets/datatables/css/jquery.dataTables.min.css\' ?>">
        <link rel="stylesheet" type="text/css" href="<?= \'/assets/datatables/css/buttons.dataTables.min.css\' ?>">
        
        <div class="row-fluid">
            <div class="span12">
                <div class="widget">
                    <div class="widget-header">
                        <div class="title">
                            <span class="fs1" aria-hidden="true" data-icon="&#xe14a;"></span> <?= $title; ?>
                        </div>
                    </div>
                    <div class="widget-body">
                        <?php if (session()->getFlashData(\'messages\')) : ?>
                            <div class="alert alert-success" role="alert">
                                <?= session()->getFlashData(\'messages\') ?>
                            </div>
                        <?php endif; ?>
                        <table id="datatables" class="table table-condensed table-bordered no-margin">
                            <thead>
                            <tr>
                            <th>No.</th>';

        foreach ($json as $r) {
            $phpView = $phpView . '
                            <th>' . $r->label . '</th>';
        }

        $phpView = $phpView . '   
                            <th>Action</th>
                        </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="<?= \'/assets/datatables/js/jquery.dataTables.min.js\' ?>"></script>
        <script type="text/javascript" src="<?= \'/assets/datatables/js/dataTables.buttons.min.js\' ?>"></script>
        <script type="text/javascript">
            $(document).ready(function() {
        
                var table = $(\'#datatables\').DataTable({
                    "dom": \'Bfrtip\',
                    "buttons": [
                        <?php if (in_array(\'' . strtolower($module) . '/create\', session()->get(\'user_permissions\'))) : ?> {
                                text: \'Create New\',
                                action: function(e, dt, node, config) {
                                    window.location.href = "<?= base_url(\'/' . strtolower($module) . '/create\'); ?>";
                                }
                            }
                        <?php endif; ?>
                    ],
                    "processing": true,
                    "serverSide": true,
                    "order": [
                        [2, "asc"]
                    ],
                    "search": {
                        "caseInsensitive": false
                    },
                    "ajax": "<?= base_url(\'' . strtolower($module) . '/datatables\'); ?>",
                    "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                        var info = table.page.info();
                        var page = info.page;
                        var length = info.length;
                        var index = (page * length + (iDisplayIndex + 1));
                        $(\'td:first\', nRow).html(index);
                        $(\'td:eq(1)\', nRow).css("text-align", "left");
                        $(\'td:eq(2)\', nRow).css("text-align", "center");
                        return nRow;
                    },
                });
        
            });
        </script>
        
        <?= $this->endSection(); ?>';


        return $phpView;
    }

    public function createViewCreate($module, $json)
    {
        $formElement = $this->formElement($mode = 'create', $json);
        $phpView = '<?= $this->extend(\'layout/backend_template\'); ?>

        <?= $this->section(\'backend_content\'); ?>
        
        <div class="row-fluid">
        <div class="span6">
            <div class="widget">
                <div class="widget-header">
                    <div class="title">
                        <span class="fs1" aria-hidden="true" data-icon="&#xe023;"></span><?= $title; ?>
                    </div>
                </div>
                <div class="widget-body">
                    <?php if (session()->getFlashData(\'messages\')) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?= session()->getFlashData(\'messages\') ?>
                        </div>
                    <?php endif; ?>
                    <form action="<?= base_url(\'' . strtolower($module) . '/save\'); ?>" method="POST" enctype="multipart/form-data" class="form-horizontal no-margin">
    
                        <?= csrf_field(); ?>
                        ' . $formElement . '    
                        <div class="form-actions no-margin">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn" onclick="window.history.back();">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
        
        <?= $this->endSection(); ?>';


        return $phpView;
    }

    public function createViewEdit($module, $json)
    {
        $formElement = $this->formElement($mode = 'edit', $json);
        $phpView = '<?= $this->extend(\'layout/backend_template\'); ?>

        <?= $this->section(\'backend_content\'); ?>
        
        <div class="row-fluid">
        <div class="span6">
            <div class="widget">
                <div class="widget-header">
                    <div class="title">
                        <span class="fs1" aria-hidden="true" data-icon="&#xe023;"></span><?= $title; ?>
                    </div>
                </div>
                <div class="widget-body">
                    <?php if (session()->getFlashData(\'messages\')) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?= session()->getFlashData(\'messages\') ?>
                        </div>
                    <?php endif; ?>
                    <form action="<?= base_url(\'' . strtolower($module) . '/update/\' . $data->id); ?>" method="POST" enctype="multipart/form-data" class="form-horizontal no-margin">
    
                        <?= csrf_field(); ?>
                        ' . $formElement . '    
                        <div class="form-actions no-margin">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn" onclick="window.history.back();">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
        
        <?= $this->endSection(); ?>';


        return $phpView;
    }

    public function createViewDetail($module, $json)
    {
        $formElement = $this->formElement($mode = 'detail', $json);
        $phpView = '<?= $this->extend(\'layout/backend_template\'); ?>

        <?= $this->section(\'backend_content\'); ?>
        
        <div class="row-fluid">
        <div class="span6">
            <div class="widget">
                <div class="widget-header">
                    <div class="title">
                        <span class="fs1" aria-hidden="true" data-icon="&#xe023;"></span><?= $title; ?>
                    </div>
                </div>
                <div class="widget-body">
                    <?php if (session()->getFlashData(\'messages\')) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?= session()->getFlashData(\'messages\') ?>
                        </div>
                    <?php endif; ?>
                    <form action="#" method="POST" enctype="multipart/form-data" class="form-horizontal no-margin">
    
                        <?= csrf_field(); ?>
                        ' . $formElement . '    
                        <div class="form-actions no-margin">
                            <button type="button" class="btn" onclick="window.history.back();">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
        
        <?= $this->endSection(); ?>';


        return $phpView;
    }



    /*Form Builder*/
    function formElement($mode = 'create', $json = [])
    {

        $element = "";
        foreach ($json as $r) {

            $type  = isset($r->type) ? $r->type : '';
            $name  = isset($r->name) ? $r->name : '';
            $label  = isset($r->label) ? $r->label : '';
            $valueElement = isset($r->value) ? $r->value : '';
            $required = ($r->required) ? 'true' : 'false';
            $className = isset($r->className) ? $r->className : '';

            $value = ($mode != 'create') ? '$data->' . $name : '\'\'';
            $readonly = ($mode != 'detail') ? 'false' : 'true';

            if ($r->type == 'text') {
                $element = $element . "
                <?= input_text(\$field_name = '" . $name . "', \$label = '" . $label . "', \$value = " . $value . ", \$required = " . $required . ", \$readonly = " . $readonly . ", \$disabled = false); ?>";
            } elseif ($r->type == 'hidden') {
                $element = $element . "
            <?php input_hidden('" . $name . "',''); ?>";
            } elseif ($r->type == 'date') {
                $element = $element . "
            <?php input_date('" . $name . "','" . $label . "',''," . $required . ",false); ?>";
            } elseif ($r->type == 'button') {
                $element = $element . "
            <?php input_button('" . $name . "','" . $label . "','','" . $className . "'); ?>";
            } elseif ($r->type == 'select') {
                $element = $element . "
            <?php
            \$options = array(";


                foreach ($r->values as $x) {
                    $element = $element . "
                '" . $x->value . "' => '" . $x->label . "',";
                }

                $element = rtrim($element, ",");

                $element = $element . "
            );
            ?>";

                $element = $element . "
            <?php input_select('" . $name . "','" . $label . "',\$options,''," . $required . ",true); ?>";
            }
        }

        return $element;
    }
}
