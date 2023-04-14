<h1>Add Product</h1>

<?php if(isset($validation)): ?>
    <div class="alert alert-danger"><?= $validation->listErrors() ?></div>
<?php endif; ?>

<form action="<?= base_url('product/store') ?>" method="post">
    <?=csrf_field() ?>
    <div class="form-group">
        <label for="name">Name: </label>
        <input type="text" name="name" class="form-control" value="<?= old('name')?>">
    </div>

    <div class="form-group">
        <label for="description">Description</label>
        <textarea name="description" class="form-control"><?= old('description') ?></textarea>
    </div>

    <div class="form-group">
        <label for="price">Price: </label>
        <input type="text" name="price" class="form-control" value="<?= old('price') ?>">
    </div>
    <button type="submit" class="btn btn-primary">Add Product</button>
    <button class="btn btn-secondary "><a href="/" class="text-decoration-none text-white" >Cancel</a></button>
</form>
