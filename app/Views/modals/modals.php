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
      <div class="modal-body p-3">  
      
<form class="" action="<?= base_url('product/store') ?>" method="post" enctype="multipart/form-data">
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
            <span>
                Download template:<a href="<?= base_url('product/tempDownload/' .'template.csv')?>"> <i class="bi bi-download"></i> Download</a>
            </span>  
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