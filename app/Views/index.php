 
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
      <div class="modal-body p-2">  
      
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
        <input type="file" id="pic" name="pic" value="<?= old('pic') ?>">
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
        <button class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Cancel</button>
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
        <h5 class="modal-title" id="exampleModalLongTitle">Import Data from CSV</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
<div class="modal-body">
    <form action="<?= base_url('product/import')?>" method="post" enctype="multipart/form-data"> 
        <?= csrf_field() ?>
        <div class="text-wrap mb-1 p-2">
            <label for="excelFile"><h5>Upload File: <i class="bi bi-filetype-csv"></i></h5></label>
            <input type="file" name="excelFile">   
            
            <span>Download template:<a href="<?= base_url('product/tempDownload/' .'template.csv')?>"> <i class="bi bi-download"></i> Download</a></span>  
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Import</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>        
        </div>
    </form>
</div>
    </div>
  </div>
</div>

<!-- TABLE START -->    
<div style="height:600px;" class="table-responsive overflow-auto">
    <div class=" d-flex align-items-center">
        <div class="mr-auto p-2">
            <a  class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter"><i class="bi bi-cart-plus"></i> Add Products</a>
            <a  class="btn btn-primary" data-toggle="modal" data-target="#uploadModalCenter"><i class="bi bi-database-add"></i> Import data</a>
            <a  class="btn btn-danger mr-4" href="<?php echo base_url('truncate'); ?> " onclick="return confirm('Are you sure you want to truncate this table?')"><i class="bi bi-database-dash"></i> Truncate Table(Dev Tool)</a>
        </div>
        <form class=" mt-2  p-2"  action="<?= base_url('product/search/') ?>" method="get">
            <div>
                <input type="text" name="search" class="ml-2 mb-2 p-1" on placeholder="Search...">
                <button type="submit" class="btn btn-outline-primary ml-2  "><i class="bi bi-search"></i> Search</button>
            </div>
        </form>
    </div>
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
    <table class="table table-hover ">
        <caption class="ml-2"><?= esc('Total Number of Entries: '). $count?></caption>
        <thead>
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
                <td class="font-weight-bold align-middle" style="width: 2rem;">#<?=$product['id'] ?></td>
                <td class="text-break align-middle"style="width: 10rem;"><?= $product['name'] ?></td>
                <td class="text-break align-middle "style="width: 16rem;"><a href="<?= base_url('product/download/' . $product['pic']) ?>"><?= $product['pic'] ?></a></td>
                <td class="text-break align-middle"style="width: 24rem;"><?= $product['description'] ?></td>
                <td class="text-break align-middle"style="width: 8rem;">$</i><?= $product['price'] ?></td>
                <td class="text-break align-middle"style="width: 10rem;">
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
    <?= $pager->links() ?>
</div>



   
