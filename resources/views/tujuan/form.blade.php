    <!-- Modal -->
    <div class="modal fade bd-example-modal-lg" id="modal-form" style="overflow:hidden;" role="dialog"
        aria-labelledby="modal-form" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">

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

                        <input type="text" value="{{ $tanggal->id }}" id="tanggal_kiriman_id"
                            name="tanggal_kiriman_id" hidden>

                        <div class="form-group row">
                            <label for="nama_tujuan" class="col-md-2 col-md-offset-1 control-label">
                                @if ($tanggal->jenis == 'Frozen')
                                    Rak
                                @else
                                    DO
                                @endif
                            </label>
                            <div class="col-md 6">
                                <input type="text" name="nama_tujuan" id="nama_tujuan" class="form-control" required
                                    oninvalid="this.setCustomValidity('Nama DO harus diisi')"
                                    oninput="this.setCustomValidity('')" autofocus>
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="prod_date_1" class="col-md-2 col-md-offset-1 control-label">Prod. Date 1</label>
                            <div class="col-md 6">
                                <input type="text" name="prod_date_1" id="prod_date_1" class="form-control">
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="prod_date_2" class="col-md-2 col-md-offset-1 control-label">Prod. Date 2</label>
                            <div class="col-md 6">
                                <input type="text" name="prod_date_2" id="prod_date_2" class="form-control">
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
