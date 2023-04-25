 


<!-- TABLE START -->    
<div style="height:600px;" class="table-responsive overflow-auto">
    <div class=" d-flex align-items-center">
        <div class="mr-auto p-2">
            <a  class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter"><i class="bi bi-cart-plus"></i> Add Products</a>
            <a  class="btn btn-primary" data-toggle="modal" data-target="#uploadModalCenter"><i class="bi bi-database-add"></i> Import data</a>
            <a  class="btn btn-danger mr-4" href="<?= base_url('truncate'); ?> " onclick="confirm('Are you sure you want to clear this table?')"><i class="bi bi-database-dash"></i> Truncate Table(Dev Tool)</a>
        </div>
        <form class=" mt-2  p-2"  action="<?= base_url('product/search/') ?>" method="get">
            <div>
                <input type="text" name="search" class="ml-2 mb-2 p-1"  placeholder="Search...">
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
    <table class="table table-hover  ">
<caption class="ml-2">
    <form action="<?= base_url('/') ?>" method="get" class="">
        <label for="show-entries">Show 
            <select id="show-entries" name="show_entries">
                <option value="10" <?= ($selected_entries == 10) ? 'selected' : '' ?>>10</option>
                <option value="25" <?= ($selected_entries == 25) ? 'selected' : '' ?>>25</option>
                <option value="50" <?= ($selected_entries == 50) ? 'selected' : '' ?>>50</option>
                <option value="100" <?= ($selected_entries == 100) ? 'selected' : '' ?>>100</option>
            </select>
            Entries
        </label>
                <button type="submit" class="btn btn-secondary p-1">Go</button>
    </form>
</caption>
        <thead>
            <tr>
                <th class="user-select-none text-center">ID</th>
                <th class="user-select-none text-center">Name</th>
                <th class="user-select-none text-center">File/Img</th>
                <th class="user-select-none text-center">Description</th>
                <th class="user-select-none text-center">Price</th>
                <th class="user-select-none text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($products as $product): ?>
            <tr>
                <td class="font-weight-bold align-middle text-center" style="width: 2rem;">#<?=$product['id'] ?></td>
                <td class="text-break align-middle text-center"style="width: 10rem;"><?= $product['name'] ?></td>
                <td class="text-break align-middle text-center "style="width: 16rem;"><a href="<?= base_url('product/download/' . $product['pic']) ?>"><?= $product['pic'] ?></a></td>
                <td class="text-break align-middle text-center"style="width: 24rem;"><?= $product['description'] ?></td>
                <td class="text-break align-middle text-center"style="width: 8rem;">$</i><?= $product['price'] ?></td>
                <td class="text-break align-middle text-center"style="width: 10rem;">
                    <a href="<?= base_url('product/edit/'.$product['id']) ?>" class="btn btn-warning"><i class="bi bi-pencil-fill"></i></a>
                    <form action="<?= base_url('product/delete/'.$product['id']) ?>" method="post" class="d-inline">
                        <?= csrf_field() ?>
                        <input type="hidden" name="method" value="DELETE" >
                        <button type="submit" class="btn btn-danger" onclick="confirm('Are you sure you want to delete this product?')"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>  
        </tbody>
    </table>
    <!-- Pagination -->
<div class="d-inline p-2">
    <div class="align-items-center float-left ml-3">Showing <?= count($products) ?> out of <?= $count ?> results</div>
    <div class="float-right mr-3"><?= $pager->links() ?></div> 
</div>
</div>



   
