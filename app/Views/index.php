<div class="float-left">
        <a  class="btn btn-primary mb-3 ml-2 " data-toggle="modal" data-target="#exampleModalCenter"><i class="bi bi-cart-plus"></i> Add Products</a>
        <a  class="btn btn-primary mb-3 ml-2" data-toggle="modal" data-target="#uploadModalCenter"><i class="bi bi-database-add"></i> Import DB</a>
        <a  class="btn btn-danger mb-3 ml-2" href="<?php echo base_url('truncate'); ?> "><i class="bi bi-database-add"></i> Truncate Table(Dev Tool)</a>
    </div>
<form class=" mr-2 mb-3"  action="<?= base_url('product/search/') ?>" method="get">
    <div class=" float-right mb-3">
        <input type="text" name="search" class=" ml-2" on placeholder="Search...">
        <button type="submit" class="btn btn-outline-primary ml-2  "><i class="bi bi-search"></i> Search</button>
    </div>
</form>
<br> <br>
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
    <div class="form-group">
        <label for="name">Name: </label>
        <input type="text" name="name" class="form-control" value="<?= old('name')?>">
    </div>

    <div class="form-group">
        <label for="pic"  class="">Picture: </label>
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
        <button type="submit" class="btn btn-primary" data-backdrop="static">Add Product</button>
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
        <form action="<?= base_url('product/import')?>" method="get" >
        <?= csrf_field() ?>
            <label for="excelFile">Upload File: </label>
            <input type="file" name="excelFile">
        </form>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-primary" >Save changes</button>
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>        
      </div>
    </div>
  </div>
</div>
<!-- TABLE START -->
<div class="table-responsive meow">
    <table class="table table-hover table-sm">
        <thead>
            <tr>
                <th class="user-select-auto">ID</th>
                <th class="user-select-none">Name</th>
                <th class="user-select-none">Image</th>
                <th class="user-select-none">Description</th>
                <th class="user-select-none">Price</th>
                <th class="user-select-none">Created At</th>
                <th class="user-select-none">Updated At</th>
                <th class="user-select-none">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($products as $product): ?>
            
            <tr>
                <td class="font-weight-bold">#<?=$product['id'] ?></td>
                <td class="text-break "style="width: 8rem;"><?= $product['name'] ?></td>
                <td class="text-break "style="width: 10rem;"><a href="<?= base_url('product/download/' . $product['pic']) ?>"><?= $product['pic'] ?></a></td>
                <td class="text-break "style="width: 18rem;"><?= $product['description'] ?></td>
                <td class="text-break "style="width: 8rem;"><i class="bi bi-currency-dollar"></i><?= $product['price'] ?></td>
                <td class="text-break "style="width: 12rem;"><?= $product['created_at'] ?></td>
                <td class="text-break "style="width: 12rem;"><?= $product['updated_at']?></td>
                <td>
                    <a href="<?= base_url('product/edit/'.$product['id']) ?>" class="btn btn-warning"><i class="bi bi-pencil-fill"></i> Edit</a>
                    <form action="<?= base_url('product/delete/'.$product['id']) ?>" method="post" class="d-inline">
                        <?= csrf_field() ?>
                        <input type="hidden" name="method" value="DELETE" >
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this product?')"><i class="bi bi-trash"></i> Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach;  ?>  
        </tbody>
    </table>
    </div>