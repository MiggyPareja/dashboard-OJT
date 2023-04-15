<?php 
namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model{

    protected $table = 'products';
    protected $primatKey = 'id';
    protected $allowedFields =['name','description','price','pic'];
    public function savePicture($filename)
    {
        $data = [
            'pic' => $filename
        ];
        $this->insert($data);
    }
}
?>