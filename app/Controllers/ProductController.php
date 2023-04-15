<?php 
namespace App\Controllers;

use App\Models\ProductModel;
use CodeIgniter\Controller;
helper('session');
helper('filesystem');
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
   public function store()
{
    $rules = [
            'name' => 'required|min_length[2]',
            'description' => 'required|min_length[2]',
            'price' => 'required|numeric',
            'pic' => 'uploaded[image]|max_size[image,1024]|ext_in[image,jpg,jpeg,png]'
        ];

    if (!$this->validate($rules)) {
        session()->setFlashdata('error', 'Incomplete Form.');
        return redirect()->to('/product/create');
    }

    $productModel = new ProductModel();
    $product = ['name' => $this->request->getVar('name'),
                'description' => $this->request->getVar('description'),
                'price' => $this->request->getVar('price'),
                'pic' => $this->request->getFile('pic')
                
            ];
            
    $productModel->insert($product);
    session()->setFlashdata('success', 'PRODUCT ADDED SUCCESSFULLY');
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
    session()->setFlashdata('update', 'PRODUCT UPDATED SUCCESSFULLY.');
    return redirect()->to('/');
    
   }
    public function delete($id = null)
    {
        $model = new ProductModel();
        $model->delete($id);
        session()->setFlashdata('delete', 'DELETED SUCCESSFULLY.');
        return redirect()->back();
    }
    public function search()
    {
        $model = new ProductModel();
        $searchTerm = $this->request->getGet('search');
        
        $data = [
            'products' =>$model->like(['name'=> $searchTerm])
                                ->orLike(['description' => $searchTerm])
                                ->orLike(['price' => $searchTerm])
                                ->findAll()
        ];
        if(empty($searchTerm)){
            session()->setFlashdata('error', 'INVALID INPUT.');
            return redirect()->to('/');
        }
        session()->setFlashdata('query', 'INPUT ACCEPTED');
        return view('templates/header')
              .view('index', $data)
              .view('templates/footer');
    }
    
}
?>