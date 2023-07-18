<?= $this->extend('layouts/app'); ?>

<?= $this->section('css') ?>
<style>
    /* for sm */

.custom-switch.custom-switch-sm .custom-control-label {
    padding-left: 1rem;
    padding-bottom: 1rem;
}

.custom-switch.custom-switch-sm .custom-control-label::before {
    height: 1rem;
    width: calc(1rem + 0.75rem);
    border-radius: 2rem;
}

.custom-switch.custom-switch-sm .custom-control-label::after {
    width: calc(1rem - 4px);
    height: calc(1rem - 4px);
    border-radius: calc(1rem - (1rem / 2));
}

.custom-switch.custom-switch-sm .custom-control-input:checked ~ .custom-control-label::after {
    transform: translateX(calc(1rem - 0.25rem));
}

/* for md */

.custom-switch.custom-switch-md .custom-control-label {
    padding-left: 2rem;
    padding-bottom: 1.5rem;
}

.custom-switch.custom-switch-md .custom-control-label::before {
    height: 1.5rem;
    width: calc(2rem + 0.75rem);
    border-radius: 3rem;
}

.custom-switch.custom-switch-md .custom-control-label::after {
    width: calc(1.5rem - 4px);
    height: calc(1.5rem - 4px);
    border-radius: calc(2rem - (1.5rem / 2));
}

.custom-switch.custom-switch-md .custom-control-input:checked ~ .custom-control-label::after {
    transform: translateX(calc(1.5rem - 0.25rem));
}

/* for lg */

.custom-switch.custom-switch-lg .custom-control-label {
    padding-left: 3rem;
    padding-bottom: 2rem;
}

.custom-switch.custom-switch-lg .custom-control-label::before {
    height: 2rem;
    width: calc(3rem + 0.75rem);
    border-radius: 4rem;
}

.custom-switch.custom-switch-lg .custom-control-label::after {
    width: calc(2rem - 4px);
    height: calc(2rem - 4px);
    border-radius: calc(3rem - (2rem / 2));
}

.custom-switch.custom-switch-lg .custom-control-input:checked ~ .custom-control-label::after {
    transform: translateX(calc(2rem - 0.25rem));
}

/* for xl */

.custom-switch.custom-switch-xl .custom-control-label {
    padding-left: 4rem;
    padding-bottom: 2.5rem;
}

.custom-switch.custom-switch-xl .custom-control-label::before {
    height: 2.5rem;
    width: calc(4rem + 0.75rem);
    border-radius: 5rem;
}

.custom-switch.custom-switch-xl .custom-control-label::after {
    width: calc(2.5rem - 4px);
    height: calc(2.5rem - 4px);
    border-radius: calc(4rem - (2.5rem / 2));
}

.custom-switch.custom-switch-xl .custom-control-input:checked ~ .custom-control-label::after {
    transform: translateX(calc(2.5rem - 0.25rem));
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content-header') ?>
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Edit URL</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= route_to('App\Controllers\Backend\ManageUrlController::index') ?>">dashboard</a></li>
                    <li class="breadcrumb-item active">edit url</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
<div class="col-md-12">
            <div class="card">
                <div class="card-body">
                <?php
                use App\Controllers\Backend\ManageUrlController;
                if (session()->getFlashdata('success')) : ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php elseif (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                    <form action="<?= route_to('App\Controllers\Backend\ManageUrlController::update' , $url['code']) ?>" method="POST">
                    <input type="hidden" name="_method" value="PUT">
                        <div class="row mb-3">
                          <label for="url" class="col-lg-2 offset-lg-2 col-form-label">Origin Url</label>
                          <div class="col-lg-4">
                            <input type="text" class="form-control" id="url" name="url" value="<?= old('url',$url['url']) ?>">
                          </div>
                        </div>
                        <div class="row mb-3">
                          <label for="shorten_url" class="col-lg-2 offset-lg-2 col-form-label">Shorten Url</label>
                          <div class="col-lg-4">
                            <input type="text" class="form-control" id="short_url" name="short_url" value="<?= old('short_url',$url['short_url']) ?>" disabled>
                          </div>
                        </div>
                        <div class="row mb-3">
                            <label for="code" class="col-lg-2 offset-lg-2 col-form-label">Code</label>
                            <div class="col-lg-4">
                              <input type="text" class="form-control" id="code" name="code" value="<?= old('code',$url['code']) ?>" disabled>
                            </div>
                          </div>
                        <div class="row mb-3">
                        <label for="code" class="col-lg-2 offset-lg-2 col-form-label">Status</label>
                          <div class="col-lg-4">
                          <div class="custom-control custom-switch custom-switch-md">
                                        <input type="checkbox" class="custom-control-input switch-status align-middle" id="customSwitch" name="status" <?= $url['status'] == 1 ? 'checked' : null ?>>
                                        <label class="custom-control-label" for="customSwitch"></label>
                                    </div>
                          </div>
                        </div>
                        <div class="row mb-3">
                          <div class="col-lg-6 offset-lg-2">
                                <button type="submit" class="btn btn-primary btn-block" type="button">Save</button>
                          </div>
                        </div>
                      </form>
                </div>
            </div>
        </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>

<?= $this->endSection() ?>
