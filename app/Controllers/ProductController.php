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
    
    return  view('templates/header')
           .view('index', $data)
           .view('templates/footer');
   }
    
   public function store()
{
    // Load necessary helpers
    helper(['filesystem', 'url']);

    // Load the model
    $model = new ProductModel();

    // Define the validation rules
    $rules = [
        'name' => 'required|min_length[2]',
        'description' => 'required|min_length[2]|max_length[255]|alpha_numeric_space',
        'price' => 'required|numeric',
        'pic' => 'permit_empty|max_size[pic,2048]'
    ];

    // Validate the request data
    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('errorModal', 'Incomplete or invalid form data.');
    }

    // Check for valid file upload
    $file = $this->request->getFile('pic');
    if ($file && !$file->isValid()) {
        return redirect()->back()->withInput()->with('error', 'Invalid file uploaded.');
    }

    // Prepare product data
    $product = [
        'name' => $this->request->getVar('name'),
        'description' => $this->request->getVar('description'),
        'price' => $this->request->getVar('price'),
        'pic' => null
    ];

    // Handle file upload
    if ($file && $file->isValid()) {
        $fileName = $file->getRandomName();
        $file->move(WRITEPATH . 'uploads', $fileName);
        $product['pic'] = $fileName;
    }

    // Save the product
    $model->save($product);

    // Set success message
    session()->setFlashdata('success', 'Product added successfully.');

    // Redirect to the product list page
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
    
    public function update($id)
{
    // Load the necessary helpers
    helper('filesystem');

    // Load the model
    $model = new ProductModel();

    // Validate the request data
    $rules = [
        'name' => 'required|min_length[2]',
        'description' => 'required|min_length[2]|max_length[255]|alpha_numeric_space',
        'price' => 'required|numeric',
        'pic' => 'permit_empty|max_size[pic,2048]'
    ];
    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('errorModal', 'Incomplete or invalid form data.');
    }

    // Prepare data to be updated
    $data = [
        'name' => $this->request->getPost('name'),
        'description' => $this->request->getPost('description'),
        'price' => $this->request->getPost('price'),
    ];

    // Handle file upload
    $file = $this->request->getFile('pic');
    if ($file && $file->isValid()) {
        $fileName = $file->getRandomName();
        $file->move(WRITEPATH . 'uploads', $fileName);
        $data['pic'] = $fileName;
    }

    // Update the product
    $model->update($id, $data);

    // Set success message
    session()->setFlashdata('success', 'Product updated successfully.');

    // Redirect to the product list page
    return redirect()->to('/');
}



public function delete($id = null)
{
    // Load the model
    $model = new ProductModel();

    // Get the product ID to be deleted
    $product = $model->find($id);

    if (!$product) {
        // If product not found, set error message and redirect to home page
        session()->setFlashdata('error', 'Product not found.');
        return redirect()->to('/');
    }

    // Delete the uploaded file, if it exists
    $filepath = WRITEPATH . 'uploads/' . $product['pic'];
    if (is_file($filepath)) {
        unlink($filepath);
    }

    // Delete the product from the database
    $model->delete($id);

    // Set success message and redirect to home page
    session()->setFlashdata('success', 'Product deleted successfully.');
    return redirect()->to('/');
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
            session()->setFlashdata('error', 'Invalid or missing search term.');
            return redirect()->withInput()-> to('/');
        }
        session()->setFlashdata('success', 'Search results for "' . $searchTerm . '".');
        return view('templates/header')
              .view('index', $data)
              .view('templates/footer');
}
    public function download($fileName)
    {
        $path = WRITEPATH . "uploads/" .$fileName ;
        

        if (!file_exists($path)|| !file_exists($path)) {
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
    $model = new ProductModel();

    if ($model->countAll() > 0) {
        helper('filesystem');
        delete_files(WRITEPATH . 'session');
        delete_files(WRITEPATH.'uploads');
        $model->truncate();
        session()->setFlashdata('success', 'Table Cleared Successfully');
    } else {
        session()->setFlashdata('error', 'Table Already Empty');
    }

    return redirect()->to('/');
}

public function import()
{
    helper(['form', 'url', 'text', 'filesystem']);

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