<?php 
 
 namespace App\Controllers;
 use App\Models\ProductModel;


 class ViewController extends BaseController
 {
    public function index($perPage)
{
    //Load Helpers
    helper('form');
    //Load Model
    $model = new ProductModel();
    //Get page Numbers
    $perPage = $this->request->getPost('show_entries');
    //Load Data
    $data = [
        'products' => $model->paginate($perPage),
        'pager' => $model->pager,
        'count' => $model->countAll(),
        'perPage' => $perPage,
    ];
    //Return View
    return view('templates/header')
        . view('modals/modals')
        . view('index', $data)
        . view('templates/footer');
}

    public function edit($id)
    {
        //Load Model
        $model = new ProductModel();
        // Load Query
        $data['product'] =$model->find($id);
        //Pass view and data to edit page
        return view('templates/header')
                .view('edit', $data)
                .view('templates/footer');

    }
 }
 
 ?>