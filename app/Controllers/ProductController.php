<?php

namespace App\Controllers;

use App\Models\ProductModel;
use Config\Pager;

class ProductController extends BaseController
{
    public function store()
    {
        // Load necessary helpers
        helper(['filesystem', 'url', 'security']);

        // Load the model
        $model = new ProductModel();

        // Define the validation rules
        $rules = [
            'name' => 'required|min_length[3]',
            'description' => 'required|min_length[3]',
            'price' => 'required|numeric',
            'pic' => 'uploaded[pic]|max_size[pic,2048]'
        ];

        // Validate the request data
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errorModal', 'Incomplete or invalid form data.');
        }

        // Store File in variable
        $file = $this->request->getFile('pic');
        // Handle file upload 
        if ($file && $file->isValid()) {
            $fileName = $file->getRandomName();
            $file->move(WRITEPATH . 'uploads', $fileName);
            sanitize_filename($fileName);
        }
        // Prepare product data
        $product = [
            'name' => $this->request->getVar('name'),
            'description' => $this->request->getVar('description'),
            'price' => $this->request->getVar('price'),
            'pic' => $fileName,
        ];

        // Save the product
        $model->save($product);
        // Set success message
        session()->setFlashdata('success', 'Product added successfully.');
        // Redirect to the product list page
        return redirect()->to(previous_url());
    }
    public function update($id)
    {
        // Load the necessary helpers
        helper('filesystem');
        helper('security');
        // Load the model
        $model = new ProductModel();
        // Validate the request data
        $rules = [
            'name' => 'required|min_length[3]',
            'description' => 'required|min_length[3]',
            'price' => 'required|numeric'
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errorModal', 'Incomplete or invalid form data.');
        }
        //Check if File is set/uploaded
        $file = $this->request->getFile('pic');
        if ($file && is_file($file)) {
            $fileName = $file->getRandomName();
            sanitize_filename($file);
            $file->move(WRITEPATH . 'uploads', $fileName);
        }
        // Prepare data to be updated
        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'pic' => $fileName
        ];
        // Update the product
        $model->update($id, $data);
        // Set success message
        session()->setFlashdata('success', 'Product updated successfully.');
        // Redirect to the product list page
        return redirect()->to('/');
    }
    public function delete($id)
    {
        // Load the model
        $model = new ProductModel();

        // Get the product ID to be deleted
        $product = $model->find($id);

        if (!$product) {
            // If product not found, set error message and redirect to home page
            session()->setFlashdata('error', 'Product not found.');
            return redirect()->to(previous_url());
        }
        // Delete the product from the database
        $model->delete($id);
        // Delete the uploaded file, if it exists
        $filepath = WRITEPATH . 'uploads/' . $product['pic'];
        if (is_file($filepath)) {
            unlink($filepath);
        }
        // Set success message and redirect to home page
        session()->setFlashdata('success', 'Product deleted successfully.');
        return redirect()->to(previous_url());
    }

    public function search()
    {
        //Load Model
        $model = new ProductModel();
        //Get string that is to be searched
        $searchTerm = $this->request->getGet('search');
        //Pass string to Like statement finding string from name,desc,price,and pic.
        $data = [
            'products' => $model->like(['name' => $searchTerm])
                                ->orLike(['description' => $searchTerm])
                                ->orLike(['price' => $searchTerm])
                                ->orLike(['pic' => $searchTerm])
                                ->paginate(10),
            'pager' => $model->pager,
            'count' =>  $model->countAllResults()
        ];
        //if string is empty or if table is empty pass error flashdata then redirect to index
        if (empty($searchTerm) || empty($data['products'])) {
            session()->setFlashdata('error', 'Invalid or missing search term.');
            return redirect()->withInput()->to('/');
        }
        //else pass success flashdata to index
        session()->setFlashdata('success', 'Search results for "' . $searchTerm . '".');
        return view('templates/header')
            . view('index', $data)
            . view('templates/footer');
    }
    public function download($fileName)
    {
        //get File path in uploads through filename
        $path = WRITEPATH . "uploads/" . $fileName;

        //if file path doesnt exists throw exception
        if (!file_exists($path)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("File not found: $fileName");
        }   
        //else download filePath
        return $this->response->download($path, null);
    }
    public function tempDownload($fileName)
    {
        // Same as download but points to templateFile to be downloaded
        $templatePath = WRITEPATH . "templateFile/" . $fileName;

        if (!file_exists($templatePath)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("File not found: $fileName");
        }
        return $this->response->download($templatePath, null)->setFileName('templates.csv');
    }
    public function truncate()
    {
        helper('filesystem');
        //Load Model
        $model = new ProductModel();
        //Check if table is empty
        if ($model->countAll() > 0) {
            //Delete file from file path to clear uploads and session
            delete_files(WRITEPATH . 'session');
            delete_files(WRITEPATH . 'uploads');
            //truncate table
            $model->truncate();
            // pass success flashdata
            session()->setFlashdata('success', 'Table Cleared Successfully');
        } else {
            //pass error flashdata
            session()->setFlashdata('error', 'Table Already Empty');
        }
        //redirect to index
        return redirect()->to(previous_url());
    }

    public function import()
    {
        helper([ 'url', 'text', 'filesystem']);
        //Load Model
        $model = new ProductModel();
        // Retrieve the uploaded CSV file from the form request
        $file = $this->request->getFile('excelFile');
        // Check if the file is valid and has not been moved yet
        if ($file->isValid() && !$file->hasMoved()) {
            // Open the file and read its contents line by line
            $handle = fopen($file->getTempName(), "r");
            // Skips first row
            fgets($handle);
            while (($data = fgetcsv($handle)) !== FALSE) {
                // Get the values for each column in the CSV file, If there is no data, Leave blank
                $name = isset($data[0]) ? $data[0] : '';
                $pic = isset($data[1]) ? $data[1] : '';
                $description = isset($data[2]) ? $data[2] : '';
                $price = isset($data[3]) ? $data[3] : '';

                // If the name field is not empty, process the file (if provided) and save the data to the database
                if (!empty($name)) {
                    $imageFileName = null;
                    if (filter_var($pic, FILTER_VALIDATE_URL)|| is_file($pic)) {
                        // If the image is a remote URL || a file, download it and save it to the server
                        $imageFile = file_get_contents($pic);
                        //Gets file extension ex. [.jpeg,.php,.docx.,.xlsx]
                        $imageFileExtension = pathinfo(parse_url($pic, PHP_URL_PATH), PATHINFO_EXTENSION);
                        //Returns File name that consists of imageFile and imageFileExtension
                        $imageFileName = random_string('alnum', 18) . '.' . $imageFileExtension;
                        //Saves File into wiratable/uploads/
                        write_file(WRITEPATH . 'uploads/' . $imageFileName, $imageFile);
                    }
                    // Insert the data into the database
                    $model->insert(array(
                        'name' => $name,
                        'pic' => $imageFileName,
                        'description' => $description,
                        'price' => $price,
                    ));
                }
            }
            //Close the CSV file
            fclose($handle);
            // Set a success message and redirect back to the homepage
            session()->setFlashdata('success', 'Data imported successfully.');
            return redirect()->to(previous_url());
        } else {
            // If the file is not valid or has already been moved, set an error message and redirect back to the homepage
            session()->setFlashdata('error', 'Input empty or not supported');
            return redirect()->to(previous_url());
        }
    }
}
