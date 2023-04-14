<h1>Edit Product</h1>

<?php if (session()->has('success')): ?>
    <div class="alert alert-success"><?= session()->get('success') ?></div>
<?php endif; ?>

<?php if (isset($validation)): ?>
    <div class="alert alert-danger"><?= $validation->listErrors() ?></div>
<?php endif; ?>

<form action="/product/update/<?= $product['id'] ?>" method="post">
    <?= csrf_field() ?>
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" id="name" name="name" value="<?= $product['name'] ?>">
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description"><?= $product['description'] ?></textarea>
    </div>
    <div class="mb-3">
        <label for="price" class="form-label">Price</label>
        <input type="text" class="form-control" id="price" name="price" value="<?= $product['price'] ?>">
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="/" class="btn btn-secondary">Cancel</a>
</form>