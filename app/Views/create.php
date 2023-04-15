<h1>Add Product</h1>

<?php if(isset($validation)): ?>
    <div class="alert alert-danger"><?= $validation->listErrors() ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-warning"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>


