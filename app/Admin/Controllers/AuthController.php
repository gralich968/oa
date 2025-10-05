<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AuthController as BaseAuthController;

class AuthController extends BaseAuthController
{
    public function login()
    {
		return view('admin.login');
		}

    public function scan()
    {
      return view('scan');
    }
}
