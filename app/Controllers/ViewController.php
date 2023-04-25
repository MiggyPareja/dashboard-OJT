<?php 
 
 namespace App\Controllers;
 use App\Models\ProductModel;


 class ViewController extends BaseController
 {
    
    public function index()
{
    $model = new ProductModel();
    $perPage = null;
    $data = [
        'products' => $model->paginate($perPage),
        'pager' => $model->pager,
        'count' => $model->countAll(),
        'perPage' => $perPage,
    ];

    return view('templates/header')
        . view('modals/modals')
        . view('index', $data)
        . view('templates/footer');
}
   
    public function edit($id)
    {
        $model = new ProductModel();
        $data['product'] =$model->find($id);
        
        return view('templates/header')
                .view('edit', $data)
                .view('templates/footer');

    }
 }
 
 ?>