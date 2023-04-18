<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success timer"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('update')): ?>
    <div class="alert alert-success timer"><?= session()->getFlashdata('update') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger timer"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('delete')): ?>
    <div class="alert alert-warning timer"><?= session()->getFlashdata('delete') ?></div>
<?php endif; ?>  
<?php if (session()->getFlashdata('query')): ?>
    <div class="alert alert-success timer"><?= session()->getFlashdata('query') ?></div>
<?php endif; ?> 
<?php if (session()->getFlashdata('errorModal')): ?>
    <div class="alert alert-danger timer"><?= session()->getFlashdata('errorModal') ?></div>
<?php endif; ?> 
<!-- ADD MODAL -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Add Product</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">  
      
<form action="<?= base_url('product/store') ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <?php if (session()->getFlashdata('errorModal')): ?>
    <div class="alert alert-danger timer"><?= session()->getFlashdata('errorModal') ?></div>
    <?php endif; ?>
    <div class="form-group">
        <label for="name">Name: </label>
        <input type="text" name="name" class="form-control" value="<?= old('name')?>">
    </div>

    <div class="form-group">
        <label for="pic"  class="">File/Img: </label>
        <input type="file" id="pic" name="pic">
    </div>

    <div class="form-group">
        <label for="description">Description: </label>
        <textarea name="description" class="form-control"><?= old('description') ?></textarea>
    </div>

    <div class="form-group">
        <label for="price">Price: </label>
        <input type="text" name="price" class="form-control" value="<?= old('price') ?>">
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-primary" >Add Product</button>
        <button class="btn btn-primary" data-dismiss="modal" aria-label="Close">Cancel</button>
    </div>
</form>
            </div>
        </div>
    </div>
</div>
<!-- IMPORT MODAL -->
<div class="modal fade" id="uploadModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Import Excel File</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
<div class="modal-body">
    <form action="<?= base_url('product/import')?>" method="post" enctype="multipart/form-data"> 
        <?= csrf_field() ?>
        <div>
            <label for="excelFile">Upload File/Img: </label>
            <input type="file" name="excelFile" id="excelFile">   
            
            <span>Download template:<a href="writable\templateFile\Book2.xlsx">Click Me!</a></span>  
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Import Excel</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>        
        </div>
    </form>
</div>
    </div>
  </div>
</div>

<!-- TABLE START -->    
<div style="height:500px;" class="table-responsive ml-2 overflow-auto">
    <div class="float-left d-inline-flex">
            <a  class="btn btn-primary mb-3 ml-2 " data-toggle="modal" data-target="#exampleModalCenter"><i class="bi bi-cart-plus"></i> Add Products</a>
            <a  class="btn btn-primary mb-3 ml-2" data-toggle="modal" data-target="#uploadModalCenter"><i class="bi bi-database-add"></i> Import DB</a>
            <a  class="btn btn-danger mb-3 ml-2 mr-4" href="<?php echo base_url('truncate'); ?> " onclick="return confirm('Are you sure you want to truncate this table?')"><i class="bi bi-database-add"></i> Truncate Table(Dev Tool)</a>
        </div>
    <form class=" mr-2 mb-3"  action="<?= base_url('product/search/') ?>" method="get">
        <div class=" float-right mb-3">
            <input type="text" name="search" class=" ml-2" on placeholder="Search...">
            <button type="submit" class="btn btn-outline-primary ml-3 mr-3  "><i class="bi bi-search"></i> Search</button>
        </div>
    </form>
    <table class="table table-hover">
        <caption><?= esc('Total Number of Entries: '). $count?></caption>
        <thead class="sticky-top">
            <tr>
                <th class="user-select-none">ID</th>
                <th class="user-select-none">Name</th>
                <th class="user-select-none">File/Img</th>
                <th class="user-select-none">Description</th>
                <th class="user-select-none">Price</th>
                <th class="user-select-none">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($products as $product): ?>
            <tr>
                <td class="font-weight-bold">#<?=$product['id'] ?></td>
                <td class="text-break "style="width: 8rem;"><?= $product['name'] ?></td>
                <td class="text-break "style="width: 20rem;"><a href="<?= base_url('product/download/' . $product['pic']) ?>"><?= $product['pic'] ?></a></td>
                <td class="text-break"style="width: 30rem;"><?= $product['description'] ?></td>
                <td class="text-break "style="width: 8rem;"><i class="bi bi-currency-dollar"></i><?= $product['price'] ?></td>
                <td>
                    <a href="<?= base_url('product/edit/'.$product['id']) ?>" class="btn btn-warning"><i class="bi bi-pencil-fill"></i></a>
                    <form action="<?= base_url('product/delete/'.$product['id']) ?>" method="post" class="d-inline">
                        <?= csrf_field() ?>
                        <input type="hidden" name="method" value="DELETE" >
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this product?')"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
            <?php endforeach;  ?>  
        </tbody>
    </table>
</div>
   
