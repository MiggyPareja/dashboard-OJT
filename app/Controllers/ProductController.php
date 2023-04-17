<?php 
namespace App\Controllers;
use App\Models\ProductModel;
use PhpOffice\PhpSpreadsheet\IOFactory;

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

    $model = new ProductModel();
    $rules = [ 'name' => 'required|min_length[2]',
                'description' => 'required|min_length[2]|max_length[255]',
                'price' => 'required|numeric',
                'pic' => 'uploaded[pic]|mime_in[pic,image/jpg,image/jpeg,image/png]|max_size[pic,2048]'
            ];

    if (!$this->validate($rules)) {
        session()->setFlashdata('error', 'Incomplete or invalid form data.');
        return redirect()->to('/');
    }

    $file = $this->request->getFile('pic');
    if (!$file->isValid()) {
        session()->setFlashdata('error', 'Invalid file uploaded.');
        return redirect()->to('/');
    }

    $fileName = $file->getRandomName();
    $file->move(WRITEPATH . 'uploads', $fileName);

    $product = ['name' => $this->request->getVar('name'),
                'description' => $this->request->getVar('description'),
                'price' => $this->request->getVar('price'),
                'pic' => $fileName,    
               ];

    $model->insert($product);

    session()->setFlashdata('success', lang('Product Added Successfully'));
    return redirect()->to('/');

    }

    public function Upload()
    {
        return view('templates/header')
                .view('upload')
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
        $path = WRITEPATH . "uploads/" .$fileName ;

        if (!file_exists($path)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("File not found: $fileName");
        }
    
        return $this->response->download($path, null);
    
        
    }
    public function truncate()
    {
    $model = new ProductModel();
    $products = $model->findAll(); 
    
    if(!empty($products))
    {
        $model->db->table('products')->truncate();
        session()->setFlashdata('success', 'Table Cleared Successfully');
    }
    else   
    {
        session()->setFlashdata('error', 'Table Empty');
    }
    
    return redirect()->to('/');
    }

    public function import()
    {



    }
    
}
?>