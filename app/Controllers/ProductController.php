<?php 
namespace App\Controllers;
use App\Models\ProductModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
helper('file');

class ProductController extends BaseController
{
    
   public function index()
   {
    $model = new ProductModel();
    $data = [
        'products' => $model ->findAll(),
        'count' => $model->countAll(),
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
    $rules = [
            'name' => 'required|min_length[2]',
            'description' => 'required|min_length[2]|max_length[255]|alpha_numeric_space',
            'price' => 'required|numeric',
            'pic' => 'permit_empty|max_size[pic,2048]'
            ];

    if (!$this->validate($rules)) {
        session()->setFlashdata('errorModal', 'Incomplete or invalid form data.');
        return redirect()->withInput()-> to('/');
    }

    $file = $this->request->getFile('pic');
    if ($file && !$file->isValid()) {
        session()->setFlashdata('error', 'Invalid file uploaded.');
        return redirect()->withInput()-> to('/');
    }

    $product = ['name' => $this->request->getVar('name'),
                'description' => $this->request->getVar('description'),
                'price' => $this->request->getVar('price')    
               ];   

    if ($file && $file->isValid()) {
        $fileName = $file->getRandomName();
        $file->move(WRITEPATH . 'uploads', $fileName);
        $product['pic'] = $fileName;
    }

    $model->insert($product);

    session()->setFlashdata('success', 'Product Added Successfully');
    return redirect()->withInput()-> to('/');
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
    public function update($id)
{
    helper('filesystem');

    $model = new ProductModel();

    $data = [
        'name' => $this->request->getPost('name'),
        'description' => $this->request->getPost('description'),
        'price' => $this->request->getPost('price'),
    ];

    $file = $this->request->getFile('pic');
    if ($file && $file->isValid()) {
        $fileName = $file->getRandomName();
        $file->move(WRITEPATH . 'uploads', $fileName);
        $data['pic'] = $fileName;
    }

    $model->update($id, $data);

    session()->setFlashdata('success', 'Product updated successfully.');

    return redirect()->to('/');
}


public function delete($id = null)
{
    $model = new ProductModel();
    $product = $model->find($id);

    // Delete the uploaded file
    $filepath = WRITEPATH . 'uploads/' . $product['pic'];
    if (file_exists($filepath)) {
        unlink($filepath);
    }

    $model->delete($product);
    session()->setFlashdata('delete', 'DELETED SUCCESSFULLY.');
    return redirect()->withInput()->to('/');
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
        if(empty($searchTerm)|| empty($data['products'])){
            session()->setFlashdata('error', 'DATA IS INVALID OR MISSING');
            return redirect()->withInput()-> to('/');
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
    helper('filesystem');
    $model = new ProductModel();
    $products = $model->findAll(); 
    if(!empty($products))
    {
        $model->db->table('products')->truncate();
        session()->setFlashdata('success', 'Table Cleared Successfully');
        delete_files('C:\xampp\htdocs\dashboard-OJT\writable\session');
        delete_files('C:\xampp\htdocs\dashboard-OJT\writable\uploads');
    }
    else   
    {
        session()->setFlashdata('error', 'Table Already Empty');
    }
    return redirect()->to('/');
    }

    public function import()
    {
        helper('form');
        helper('url');
        
        $model = new ProductModel();
        
        $file = $this->request->getFile('excelFile');
        if ($file->isValid() && ! $file->hasMoved())
        {
            
            $reader = IOFactory::createReader('Xlsx');
            $spreadsheet = $reader->load($file->getTempName());
            
            
            $worksheet = $spreadsheet->getActiveSheet();
            
            
            foreach ($worksheet->getRowIterator() as $row)
            {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE);
                
                $data = array();
                foreach ($cellIterator as $cell)
                {
                    $data[] = $cell->getValue();
                }
                if(!empty($data[0])){
                    $model->insert(array(
                        'name' => $data[0],
                        'pic' =>$data[1],
                        'description' => $data[2],
                        'price' => $data[3],
                    ));
                }
                    
            }
            session()->setFlashdata('success', 'Data imported successfully.');
            return redirect()->to('/');
        }else{
            session()->setFlashdata('error', 'Input empty or not supported');
            return redirect()->to('/');
        }
    }
}  



?>