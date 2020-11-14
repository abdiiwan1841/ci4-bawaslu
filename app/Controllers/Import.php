<?php

/**
 *
 * @author Tarkiman | tarkiman@itasoft.co.id / tarkiman.zone@gmail.com 
 */

namespace App\Controllers;

use App\Models\PositionModel;
use App\Models\DirectorateModel;
use App\Models\DivisionModel;
use App\Models\DepartmentModel;
use App\Models\SectionModel;
use App\Models\EmployeeModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class Import extends BaseController
{

    public function __construct()
    {
        $this->positionModel = new PositionModel();
        $this->directorateModel = new DirectorateModel();
        $this->divisionModel = new DivisionModel();
        $this->departmentModel = new DepartmentModel();
        $this->sectionModel = new SectionModel();
        $this->employeeModel = new EmployeeModel();
    }

    public function index()
    {
    }

    public function position()
    {

        $data = [];

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load("sample_data/POSITION.xlsx");

        $excelData = $spreadsheet->getActiveSheet()->toArray();

        // dd($excelData)
        try {
            $db      = \Config\Database::connect();

            $db->transStart();
            $i = 0;
            foreach ($excelData as $r) {

                if ($i > 0) {
                    $positionCode = $r[0];
                    $positionName = $r[1];
                    $batchData[] = array(
                        'id' => get_uuid(),
                        'position_code' => $positionCode,
                        'position_name' => $positionName
                    );
                }
                $i++;
            }

            $this->positionModel->insertBatch($batchData);

            $db->transComplete();
            if ($db->transStatus() === FALSE) {
                $data['status'] = false;
                $data['message'] = 'filed save transaction';
            } else {
                $data['status'] = true;
                $data['message'] = 'save data success';
            }
        } catch (\Exception $e) {
            $data['status'] = false;
            $data['message'] = $e->getMessage();
        }

        echo json_encode($data);
    }

    public function directorate()
    {

        $data = [];

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load("sample_data/DIRECTORATE.xlsx");

        $excelData = $spreadsheet->getActiveSheet()->toArray();

        // dd($excelData);

        try {
            $db      = \Config\Database::connect();

            $db->transStart();
            $i = 0;
            foreach ($excelData as $r) {

                if ($i > 0) {
                    $directorateCode = $r[0];
                    $directorateName = $r[1];
                    $batchData[] = array(
                        'id' => get_uuid(),
                        'directorate_code' => $directorateCode,
                        'directorate_name' => $directorateName
                    );
                }
                $i++;
            }

            $this->directorateModel->insertBatch($batchData);

            $db->transComplete();
            if ($db->transStatus() === FALSE) {
                $data['status'] = false;
                $data['message'] = 'filed save transaction';
            } else {
                $data['status'] = true;
                $data['message'] = 'save data success';
            }
        } catch (\Exception $e) {
            $data['status'] = false;
            $data['message'] = $e->getMessage();
        }

        echo json_encode($data);
    }

    public function division()
    {

        $data = [];

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load("sample_data/DIVISION.xlsx");

        $excelData = $spreadsheet->getActiveSheet()->toArray();

        // dd($excelData);

        try {
            $db      = \Config\Database::connect();

            $db->transStart();
            $i = 0;
            foreach ($excelData as $r) {

                if ($i > 0) {
                    $divisionCode = $r[0];
                    $divisionName = $r[1];
                    $directorateCode = $r[2];
                    $batchData[] = array(
                        'id' => get_uuid(),
                        'division_code' => $divisionCode,
                        'division_name' => $divisionName,
                        'directorate_code' => $directorateCode
                    );
                }
                $i++;
            }

            $this->divisionModel->insertBatch($batchData);

            $db->transComplete();
            if ($db->transStatus() === FALSE) {
                $data['status'] = false;
                $data['message'] = 'filed save transaction';
            } else {
                $data['status'] = true;
                $data['message'] = 'save data success';
            }
        } catch (\Exception $e) {
            $data['status'] = false;
            $data['message'] = $e->getMessage();
        }

        echo json_encode($data);
    }

    public function department()
    {

        $data = [];

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load("sample_data/DEPARTMENT.xlsx");

        $excelData = $spreadsheet->getActiveSheet()->toArray();

        // dd($excelData);

        try {
            $db      = \Config\Database::connect();

            $db->transStart();
            $i = 0;
            foreach ($excelData as $r) {

                if ($i > 0) {
                    $departmentCode = $r[0];
                    $departmentName = $r[1];
                    $divisionCode = $r[2];
                    $batchData[] = array(
                        'id' => get_uuid(),
                        'department_code' => $departmentCode,
                        'department_name' => $departmentName,
                        'division_code' => $divisionCode
                    );
                }
                $i++;
            }

            $this->departmentModel->insertBatch($batchData);

            $db->transComplete();
            if ($db->transStatus() === FALSE) {
                $data['status'] = false;
                $data['message'] = 'filed save transaction';
            } else {
                $data['status'] = true;
                $data['message'] = 'save data success';
            }
        } catch (\Exception $e) {
            $data['status'] = false;
            $data['message'] = $e->getMessage();
        }

        echo json_encode($data);
    }

    public function section()
    {

        $data = [];

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load("sample_data/SECTION.xlsx");

        $excelData = $spreadsheet->getActiveSheet()->toArray();

        // dd($excelData);

        try {
            $db      = \Config\Database::connect();

            $db->transStart();
            $i = 0;
            foreach ($excelData as $r) {

                if ($i > 0) {
                    $sectionCode = $r[0];
                    $sectionName = $r[1];
                    $departmentCode = $r[2];
                    $batchData[] = array(
                        'id' => get_uuid(),
                        'section_code' => $sectionCode,
                        'section_name' => $sectionName,
                        'department_code' => $departmentCode
                    );
                }
                $i++;
            }

            $this->sectionModel->insertBatch($batchData);

            $db->transComplete();
            if ($db->transStatus() === FALSE) {
                $data['status'] = false;
                $data['message'] = 'filed save transaction';
            } else {
                $data['status'] = true;
                $data['message'] = 'save data success';
            }
        } catch (\Exception $e) {
            $data['status'] = false;
            $data['message'] = $e->getMessage();
        }

        echo json_encode($data);
    }

    public function employee()
    {
        ini_set('memory_limit', '-1');

        $data = [];

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load("sample_data/EMPLOYEE.xlsx");

        $excelData = $spreadsheet->getActiveSheet()->toArray();

        // dd($excelData);

        try {
            $db      = \Config\Database::connect();

            $db->transStart();
            $i = 0;
            foreach ($excelData as $r) {

                if ($i > 0) {
                    $employeeId = $r[0];
                    $name = $r[1];
                    $positionCode = $r[2];
                    $positionPercent = $r[3];
                    $sectionCode = $r[4];
                    $departmentCode = $r[5];
                    $divisionCode = $r[6];
                    $directorateCode = $r[7];
                    $address = $r[8];
                    $district = $r[9];
                    $city = $r[10];
                    $pob = $r[11];
                    $dob = date_format(date_create_from_format('m/d/Y', $r[12]), 'Y-m-d');
                    $entryDate = date_format(date_create_from_format('m/d/Y', $r[13]), 'Y-m-d');

                    $batchData[] = array(
                        'id' => get_uuid(),
                        'employee_id' => $employeeId,
                        'name' => $name,
                        'position_code' => $positionCode,
                        'position_percent' => $positionPercent,
                        'section_code' => $sectionCode,
                        'department_code' => $departmentCode,
                        'division_code' => $divisionCode,
                        'directorate_code' => $directorateCode,
                        'address' => $address,
                        'district' => $district,
                        'city' => $city,
                        'pob' => $pob,
                        'dob' => $dob,
                        'entry_date' => $entryDate
                    );
                }
                $i++;
            }

            $this->employeeModel->insertBatch($batchData);

            $db->transComplete();
            if ($db->transStatus() === FALSE) {
                $data['status'] = false;
                $data['message'] = 'filed save transaction';
            } else {
                $data['status'] = true;
                $data['message'] = 'save data success';
            }
        } catch (\Exception $e) {
            $data['status'] = false;
            $data['message'] = $e->getMessage();
        }

        echo json_encode($data);
    }
}
