<!-- <h1 class="ml-2"><a href="/" class="text-decoration-none text-dark">Products</a></h1> -->


<div class="float-left">
        <a  class="btn btn-primary mb-3 ml-2 " data-toggle="modal" data-target="#exampleModalCenter"><i class="bi bi-cart-plus"></i> Add Products</a>
        <a  class="btn btn-primary mb-3 ml-2"><i class="bi bi-database-add"></i> Import DB</a>
    </div>
<form class=" mr-2"  action="<?= base_url('product/search/') ?>" method="get" class="mb-3">
    <div class=" float-right mb-3">
        <input type="text" name="search" class=" ml-2" on placeholder="Search...">
        <button type="submit" class="btn btn-outline-primary ml-2  "><i class="bi bi-search"></i> Search</button>
    </div>
</form>
<br> <br>
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('update')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('update') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('delete')): ?>
    <div class="alert alert-warning"><?= session()->getFlashdata('delete') ?></div>
<?php endif; ?>  
<?php if (session()->getFlashdata('query')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('query') ?></div>
<?php endif; ?>    

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
      <form action="<?= base_url('product/store') ?>" method="post">
    <?=csrf_field() ?>

    <div class="form-group">
        <label for="name">Name: </label>
        <input type="text" name="name" class="form-control" value="<?= old('name')?>">
    </div>

    <div class=" custom-file ">
        
            <label for="file"  class="">Picture: </label>
            <input type="file" class=""id="" name="pic" required>
    </div>

    <div class="form-group">
        <label for="description">Description: </label>
        <textarea name="description" class="form-control"><?= old('description') ?></textarea>
    </div>

    <div class="form-group">
        <label for="price">Price: </label>
        <input type="text" name="price" class="form-control" value="<?= old('price') ?>">
    </div>
        
      </div>
      <div class="modal-footer">
      <button type="submit" class="btn btn-primary">Add Product</button>
      <button class="btn btn-primary"data-dismiss="modal" aria-label="Close">Cancel</button>
    </form>
      </div>
    </div>
  </div>
</div>

    <table class="table table-hover table-responsive{-sm|-md|-lg|-xl}">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Image</th>
                <th>Description</th>
                <th>Price</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($products as $product): ?>
            
            <tr>
                <td class="font-weight-bold">#<?=$product['id'] ?></td>
                <td><?= $product['name'] ?></td>
                <td><?= $product['pic'] ?></td>
                <td><?= $product['description'] ?></td>
                <td><i class="bi bi-currency-dollar"></i><?= $product['price'] ?></td>
                <td><?= $product['created_at'] ?></td>
                <td><?= $product['updated_at']?></td>
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
