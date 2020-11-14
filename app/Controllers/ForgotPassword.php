<?php

/**
 *
 * @author Tarkiman | tarkiman@itasoft.co.id / tarkiman.zone@gmail.com 
 */

namespace App\Controllers;

use App\Models\ForgotPasswordModel;
use CodeIgniter\I18n\Time;

class ForgotPassword extends BaseController
{
    protected $forgotPasswordModel;
    protected $dateTime;

    public function __construct()
    {
        $this->forgotPasswordModel = new ForgotPasswordModel();
        $this->dateTime = new Time('now', 'Asia/Jakarta', 'id_ID');
    }

    public function index()
    {
        $data = [
            'title' => 'Forgot Password',
            'validation' => \Config\Services::validation()
        ];
        return view('forgot_password/index', $data);
    }

    public function submit()
    {

        $validation = [
            'email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ]
        ];

        if (!$this->validate($validation)) {
            return redirect()->to('/forgot-password')->withInput()->with('messages', 'Error validation.');
        } else {
            $emailAddress = $this->request->getVar('email');
            $token = $this->forgotPasswordModel->generateTokenByEmail($emailAddress);
            if ($token) {

                /*SEND EMAIL*/
                try {

                    $contentEmail = '
                    <div>We heard that you lost your password. Sorry about that!<br>
                    <br>
                    But don’t worry! You can use the following link to reset your password:
                    <br>
                    <a href="' . base_url('/forgot-password-confirm/' . $token) . '">' . base_url('/forgot-password-confirm/' . $token) . '</a>
                        <br>
                        If you don’t use this link within 3 hours, it will expire. To get a new password reset link, visit <a href="' . base_url('/forgot-password') . '" rel="noreferrer" target="_blank">' . base_url('/forgot-password') . '</a>
                        <br>
                        <br>
                        Thanks,<br>
                        Administrator
                    </div>';

                    $email = \Config\Services::email();

                    $email->setFrom('noreply@tarkiman.com', 'Inventory System - PT.Intan Triputra Abadi');
                    $email->setTo($emailAddress);
                    //$email->setCC('tarkiman.mail@gmail.com');
                    //$email->setBCC('tarkiman.mail@gmail.com');

                    $email->setSubject('Request Reset Password');
                    $email->setMessage($contentEmail);

                    if ($email->send()) {
                        return redirect()->to('/forgot-password-redirect')->with('email', $emailAddress);
                    } else {
                        return redirect()->to('/forgot-password')->withInput()->with('messages', 'Failed send email.');
                    }
                } catch (\Exception $e) {
                    return redirect()->to('/forgot-password-redirect')->with('messages', $e->getMessage());
                }
                /*SEND EMAIL*/
            } else {
                return redirect()->to('/forgot-password')->withInput()->with('messages', 'Email not registered.');
            }
        }
    }

    public function redirectAfterSubmit()
    {
        if (session()->getFlashData('email')) {

            $data = [
                'title' => 'Forgot Password',
                'validation' => \Config\Services::validation()
            ];
            return view('forgot_password/redirect_after_submit', $data);
        } else {
            return redirect()->to('/forgot-password');
        }
    }

    public function confirmEmailLink($token)
    {

        $result = $this->forgotPasswordModel->checkTokenExpired($token);

        if ($result) {

            //d($this->dateTime->difference($result->token_password_expired)->getSeconds());

            if ($this->dateTime->difference($result->token_password_expired)->getSeconds() > 0) {
                $data = [
                    'title' => 'Request Reset Password',
                    'validation' => \Config\Services::validation(),
                    'token' => $token
                ];
                return view('forgot_password/from_setup_new_password', $data);
            } else {
                $data = [
                    'title' => 'Request Reset Password'
                ];
                return view('forgot_password/redirect_expired_token', $data);
            }
        }
        return redirect()->to('/login');
    }

    public function saveNewPassword()
    {

        $token = $this->request->getVar('token');

        $validation = [
            'password' => [
                'rules' => 'required',
                'errors' => [
                    // 'required' => '{field} harus diisi.'
                ]
            ],
            'repeat_password' => [
                'rules' => 'required|matches[password]',
                'errors' => [
                    // 'required' => '{field} harus diisi.',
                    // 'matches' => 'inputan {field} tidak sama dengan password'
                ]
            ]
        ];
        if (!$this->validate($validation)) {
            return redirect()->to('/forgot-password/confirm/' . $token)->withInput()->with('messages', 'Error validation.');
        } else {

            $data = [
                'password' => sha1($this->request->getVar('password'))
            ];

            if ($this->forgotPasswordModel->saveNewPassword($token, $data)) {
                return redirect()->to('/forgot-password-success')->with('messages', 'Success Reset Password');
            } else {
                return redirect()->to('/forgot-password-confirm/' . $token)->withInput()->with('messages', 'Failed Save New Password');
            }
        }
    }

    public function redirectSuccessSaveNewPasssword()
    {
        if (session()->getFlashData('messages')) {
            $data = [
                'title' => 'Success Reset Password'
            ];
            return view('forgot_password/redirect_success_save_new_password', $data);
        } else {
            return redirect()->to('/login');
        }
    }
}
