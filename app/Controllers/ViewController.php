<?php 
 
 namespace App\Controllers;
 use App\Models\ProductModel;


 class ViewController extends BaseController
 {
    public function index()
    {
     $model = new ProductModel();
     $data = [
         'products' => $model ->paginate(10),
         'pager' => $model->pager,
         'count' => $model->countAll(),
         
     ];
     
     return  view('templates/header')
            .view('index', $data)
            .view('templates/footer');
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