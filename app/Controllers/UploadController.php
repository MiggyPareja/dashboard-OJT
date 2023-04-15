<?php 

namespace App\Controllers;

use App\UploadModel;
use CodeIgniter\Controller;

class UploadController extends BaseController
{

    public function Upload()
    {
        return view('templates/header')
               .view('templates/footer');
    }
}

?>