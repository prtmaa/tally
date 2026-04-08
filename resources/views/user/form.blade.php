    <!-- Modal -->
    <div class="modal fade" id="modal-form" style="overflow:hidden;" role="dialog" aria-labelledby="modal-form"
        aria-hidden="true">
        <div class="modal-dialog" role="document">

            <form action="" method="post" enctype="multipart/form-data" data-toggle="validator"
                class="form-horizontal">
                @csrf
                @method('post')

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-md-offset-1 control-label">Nama</label>
                            <div class="col-md-8">
                                <input type="text" name="name" id="name" class="form-control" required
                                    oninvalid="this.setCustomValidity('Nama user harus diisi')"
                                    oninput="this.setCustomValidity('')" autofocus>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-md-offset-1 control-label">Email</label>
                            <div class="col-md-8">
                                <input type="email" name="email" id="email" class="form-control" required
                                    oninvalid="this.setCustomValidity('Email user harus diisi')"
                                    oninput="this.setCustomValidity('')" autofocus>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="username" class="col-md-4 col-md-offset-1 control-label">Username</label>
                            <div class="col-md-8">
                                <input type="text" name="username" id="username" class="form-control" required
                                    oninvalid="this.setCustomValidity('Username harus diisi')"
                                    oninput="this.setCustomValidity('')" autofocus>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-4 control-label">Password</label>

                            <div class="col-md-8">
                                <div class="password-wrapper">
                                    <input type="password" name="password" id="password" class="form-control">
                                    <i class="fas fa-eye toggle-password"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="role" class="col-md-4 col-md-offset-1 control-label">Role</label>
                            <div class="col-md-8">
                                <select name="role" id="role" class="form-control select2bs4">
                                    <option value="" disabled selected>Pilih role...</option>
                                    <option value="Master">Master</option>
                                    <option value="User">User</option>
                                </select>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal"><i
                                class="fa fa-xmark"></i> Batal</button>
                        <button class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Simpan</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
