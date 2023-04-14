<?php 
namespace App\Controllers;

use App\Models\ProductModel;
use CodeIgniter\Controller;
helper('session');
class ProductController extends BaseController
{
   public function index()
   {
    $model = new ProductModel();
    $data = [
        'products' => $model ->findAll(),
    ];

    return  view('templates/header',$data)
           .view('index', $data)
           .view('templates/footer');
   }
   public function create(){

    return  view('templates/header')
           .view('create')
           .view('templates/footer');

   }
   public function store()
{
    
    $rules = [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric'];

    if (!$this->validate($rules)) {
        session()->setFlashdata('error', 'Incomplete Form.');
        return redirect()->to('/');
    }

    
    $productModel = new ProductModel();
    $product = ['name' => $this->request->getVar('name'),
                'description' => $this->request->getVar('description'),
                'price' => $this->request->getVar('price')];

    $productModel->insert($product);
    session()->setFlashdata('success', 'Product created successfully.');
    return redirect()->to('/');

}

   public function edit($id)
   {
    $model = new ProductModel();
    $data['product'] =$model->find($id);
    return view('templates/header')
            .view('edit', $data)
            .view('templates/footer');

   }
   public function update($id){
    $model= new ProductModel();
    $data = [
        'name' =>$this->request->getPost('name'),
        'description' => $this->request->getPost('description'),
        'price' => $this->request->getPost('price')
    ];

    $model->update($id,$data);
    session()->setFlashdata('update', 'Product updated successfully.');
    return redirect()->to('/');
   }
    public function delete($id = null)
    {
        $model = new ProductModel();
        $model->delete($id);
        session()->setFlashdata('delete', 'Deleted Successfully.');
        return redirect()->to('/');
    }
    public function search()
    {
        $model = new ProductModel();
        $searchTerm = $this->request->getGet('search');
        
        $data = [
            'products' =>$model->like(['name'=> $searchTerm])->findAll()
        ];
        
            
        
        session()->setFlashdata('query', 'Query Accepted');
        return view('templates/header')
              .view('index', $data)
              .view('templates/footer');
    }
}
?>