<?php 
namespace App\Controllers;

use App\Models\ProductModel;
use CodeIgniter\Files\File;
use CodeIgniter\Controller;
use PharIo\Manifest\Library;
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
    
   public function store()
{
    helper('filesystem');
    helper('url');
    $productModel = new ProductModel();
    $rules = [
            'name' => 'required|min_length[2]',
            'description' => 'required|min_length[2]',
            'price' => 'required|numeric',
        ];
    
    if (!$this->validate($rules)) {
        session()->setFlashdata('error', 'Incomplete Form.');
        return redirect()->to('/');
    }
    
    $file = $this->request->getFile('pic');
    $fileName = $file->getRandomName();
    $file->move('writable\uploads\upload', $fileName);

    $product = ['name' => $this->request->getVar('name'),
                'description' => $this->request->getVar('description'),
                'price' => $this->request->getVar('price'),
                'pic' =>$fileName,
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
    public function download($fileName) {
        $path = WRITEPATH . 'uploads/' . $fileName;
    
        return $this->response->download($path, null);
    }
    
}
?>