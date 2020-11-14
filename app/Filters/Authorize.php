<?php

/**
 *
 * @author Tarkiman | tarkiman@itasoft.co.id / tarkiman.zone@gmail.com 
 */

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Authorize implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        /*CHECK DB CONNETCION*/
        $db = \Config\Database::connect();
        try {
            $db->persistentConnect()->ping();
        } catch (\Exception $e) {
            echo "<center><h1>Database " . $e->getMessage() . "<h1></center>";
            die();
        } finally {
            $db->close();
        }

        /**-----------------**/

        $bypass = true;
        /**
         * jika $bypass di TRUE maka AUTHORIZE / USER PERMISSIONS di NON AKTIFKAN, semua user boleh melakukan apapun TETAPI harus TETAP LOGIN DULU
         */

        $uri = '';

        if ($request->uri->getTotalSegments() > 0) {

            $uri = $request->uri->getSegment(1); //getSegment(1) = Controller (Method Index)

            $exceptionMethods = array('datatables');
            /**
             * $exceptionMethods
             * datatables =  (URI Segment 2) sengaja dikecualikan, agar tidak harus membuat permission lagi untuk method ini, cukup dengan NAMA CONTROLLER nya saja atau method index sudah bisa mengakses data (datatables server side)
             * */

            $acceptedMethod = array('create', 'save', 'edit', 'update', 'delete', 'detail');
            /**
             * $acceptedMethods
             * adalah method-method (URI Segment 2) yang di terima saja, selain itu maka akan di abbaykan
             */

            //getSegment(2) = Method dari Controller
            if ($request->uri->getSegment(2) != null && !in_array($request->uri->getSegment(2), $exceptionMethods) && in_array($request->uri->getSegment(2), $acceptedMethod)) {
                $uri .= '/' . $request->uri->getSegment(2);
            }
        }

        $noRedirect = array(
            'login',
            'signin',
            'forgot-password',
            'forgot-password-submit',
            'forgot-password-redirect',
            'forgot-password-confirm',
            'forgot-password-save-new-password',
            'forgot-password-success'
        );
        /**
         * $noRedirect
         * URI dari permission-permission ini bebas untuk di akses tanpa harus login :
         * login = form login
         * signin = action ketika form login di submit
         * */

        if (!session()->get('isLoggedIn') && !in_array($uri, $noRedirect)) {

            return redirect()->to('/login');
        } else {

            $exceptionPermissions = array('', 'login', 'signin', 'logout', 'not-found', 'forbidden', 'profile', 'profile/edit', 'profile/update');
            /**
             * $exceptionPermissions
             * '' = wajib ada, string kosong wajib ada karena akan mengarah ke default controller yang mana mengarah ke login, dan disana ada pengecekan jika sudah login akan otomatis di redirect ke landing page
             * login = wajib ada, mengarah ke controller auth/index dan wajib ada, otomotis akan diarahkan ke landing page jika user sudah login
             * signin = wajib ada, action ketika form login di submit
             */

            if (!$bypass) {

                if (!in_array($uri, $exceptionPermissions)) {

                    if (session()->get('user_permissions')) {

                        if (!in_array($uri, session()->get('user_permissions'))) {
                            // return redirect()->to('/forbidden');
                            return redirect()->to('/not-found');
                            /**
                             * redirectnya bisa tentuin
                             * mau ke forbidden atau ke not-found (pilih salah satu)
                             * jika ke not-found lebih untuk ke amanan agar user menganggap method atau controller tersebut tidak ada
                             * dan kalau ke forbidden, kemungkinan user akan tahu bahwa ada controller atau method tersebut namun dia ga punya akses
                             */
                        }
                    }
                }
            }
        }
    }

    //--------------------------------------------------------------------

    /**
     * We don't have anything to do here.
     *
     * @param RequestInterface|\CodeIgniter\HTTP\IncomingRequest $request
     * @param ResponseInterface|\CodeIgniter\HTTP\Response       $response
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }

    //--------------------------------------------------------------------
}
