<!-- <h1 class="ml-2"><a href="/" class="text-decoration-none text-dark">Products</a></h1> -->


<div class="float-left">
        <a href="<?= base_url('product/create') ?>" class="btn btn-primary mb-3 ml-2 "><i class="bi bi-cart-plus"></i> Add Products</a>
        <a href="<?= base_url('product/create') ?>" class="btn btn-primary mb-3 ml-2 "><i class="bi bi-upload"></i> Upload File</a>
    </div>
<form class=" mr-2"  action="<?= base_url('product/search/') ?>" method="get" class="mb-3">
    <div class=" float-right">
        <input type="text" name="search" class="ml-2" on placeholder="Search...">
        <button type="submit" class="btn btn-outline-primary ml-2 "><i class="bi bi-search"></i> Search</button>
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
    <div class="alert alert-warning"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('delete')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('delete') ?></div>
<?php endif; ?>  
<?php if (session()->getFlashdata('query')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('query') ?></div>
<?php endif; ?>     

    <table class="table table-hover table-responsive{-sm|-md|-lg|-xl}">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
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
