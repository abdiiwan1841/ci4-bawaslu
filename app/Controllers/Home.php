<?php

/**
 *
 * @author Tarkiman | tarkiman@itasoft.co.id / tarkiman.zone@gmail.com 
 */

namespace App\Controllers;

class Home extends BaseController
{

    public function __construct()
    {
    }

    public function index()
    {
        $data = [
            'title' => 'Home',
            'active' => 'home'
        ];

        return view('home/index', $data);
    }
}
