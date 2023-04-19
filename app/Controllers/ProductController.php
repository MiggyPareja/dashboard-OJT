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
    if ($file && !$file->isValid())
    {
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
                                ->orLike(['pic' => $searchTerm])
                                ->findAll(),
            'count' => $model->countAll(),
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
    public function download($fileName)
    {
        $path = WRITEPATH . "uploads/" .$fileName ;
        $templatePath = WRITEPATH . "templateFile/" .$fileName;

        if (!file_exists($path)|| !file_exists($templatePath)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("File not found: $fileName");
        }
        return $this->response->download($path, null);
    }
    public function tempDownload($fileName)
    {
        $templatePath = WRITEPATH . "templateFile/" .$fileName;

        if (!file_exists($templatePath)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("File not found: $fileName");
        }
        return $this->response->download($templatePath, null);
    }
    public function truncate()
    {
        helper('filesystem');
        $model = new ProductModel();
        $products = $model->findAll(); 
        if(!empty($products))
    {
        delete_files('C:\xampp\htdocs\dashboard-OJT\writable\session');
        delete_files('C:\xampp\htdocs\dashboard-OJT\writable\uploads');
        $model->db->table('products')->truncate();
        session()->setFlashdata('success', 'Table Cleared Successfully');
        
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
        helper('text');
        helper('filesystem');
    
        $model = new ProductModel();
    
        $file = $this->request->getFile('excelFile');
        if ($file->isValid() && ! $file->hasMoved())
        {
            $handle = fopen($file->getTempName(), "r");
            fgets($handle);
            while (($data = fgetcsv($handle)) !== FALSE) {
                $name = isset($data[0]) ? $data[0] : '';
                $pic = isset($data[1]) ? $data[1] : '';
                $description = isset($data[2]) ? $data[2] : '';
                $price = isset($data[3]) ? $data[3] : '';
    
                if (!empty($name)) {
                    $imageFileName = null;
                    if (filter_var($pic, FILTER_VALIDATE_URL)) {
                        
                        $imageFile = file_get_contents($pic);
                        $imageFileExtension = pathinfo(parse_url($pic, PHP_URL_PATH), PATHINFO_EXTENSION);
                        $imageFileName = random_string('basic', 16) . '.' . $imageFileExtension;
                        write_file(WRITEPATH . 'uploads/' . $imageFileName, $imageFile);
                    } else {
                        
                        if (is_file($pic)) {
                            $imageFileName = basename($pic);
                            $imageFile = file_get_contents($pic);
                            write_file(WRITEPATH . 'uploads/' . $imageFileName, $imageFile);
                        }
                    }
                    
                    $model->insert(array(
                        'name' => $name,
                        'pic' => $imageFileName,
                        'description' => $description,
                        'price' => $price,
                    ));
                }
            }
            fclose($handle);
    
            session()->setFlashdata('success', 'Data imported successfully.');
            return redirect()->to('/');
        } else {
            
            session()->setFlashdata('error', 'Input empty or not supported');
            return redirect()->to('/');
        }
    }
    
}  
?>