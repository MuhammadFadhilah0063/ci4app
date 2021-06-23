<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container">
    <div class="row">
        <div class="col">
            <h1 class="mt-1">Daftar Komik</h1>
            <table class="table table-bordered table-responsive align-middle text-center">
                <thead>
                    <th>No.</th>
                    <th>Sampul</th>
                    <th>Judul</th>
                    <th>Aksi</th>
                </thead>

                <tbody>
                    <td>1</td>
                    <td><img src="/img/sampulnaruto2.jpg" class="img-responsive sampul"></td>
                    <td>Naruto</td>
                    <td>
                        <a href="#" class="btn btn-success">Detail</a>
                    </td>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>