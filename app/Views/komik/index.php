<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container">
    <div class="row">
        <div class="col">
            <h1 class="mt-1">Daftar Komik</h1>
            <table class="table table-bordered table-responsive align-middle text-center">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Sampul</th>
                        <th>Judul</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                <?php $i = 1; ?>
                    <?php foreach($komik as $k) : ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><img src="/img/<?= $k['sampul']; ?>" class="img-responsive sampul"></td>
                        <td><?= $k['judul']; ?></td>
                        <td>
                            <a href="#" class="btn btn-success">Detail</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>