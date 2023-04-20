<h1>Edit Product</h1>
<form action="/product/update/<?= $product['id'] ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="mb-3">
        <label for="name" class="form-label">Name: </label>
        <input type="text" class="form-control" id="name" name="name" value="<?= $product['name'] ?>">
    </div>
    <div class="mb-3">
        <label for="pic" class="form-label">File/Img: </label>
        <input type="file" id="pic" name="pic" value="<?=$product['pic']?>">
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description: </label>
        <textarea class="form-control" id="description" name="description"><?= $product['description'] ?></textarea>
    </div>
    <div class="mb-3">
        <label for="price" class="form-label">Price: </label>
        <input type="text" class="form-control" id="price" name="price" value="<?= $product['price'] ?>">
    </div>
    <button type="submit" class="btn btn-primary" id="update-btn">Update </button>
    <button class="btn btn-secondary "><a href="/" class="text-decoration-none text-white" >Cancel</a></button>
</form>