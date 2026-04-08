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

                        <div class="form-group row">
                            <label for="tanggal" class="col-md-4 col-md-offset-1 control-label">Tanggal</label>
                            <div class="col-md-8">
                                <input type="text" name="tanggal" id="tanggal" class="form-control tanggal"
                                    required autofocus oninvalid="this.setCustomValidity('Silahkan pilih tanggal')"
                                    oninput="this.setCustomValidity('')">
                                <span class="help-block with-errors"></span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="jenis" class="col-md-4 col-md-offset-1 control-label">Tally</label>
                            <div class="col-md-8">
                                <select name="jenis" id="jenis" class="form-control select2bs4">
                                    <option value="" disabled selected>Pilih tally...</option>
                                    <option value="Fresh Campuran">Fresh Campuran</option>
                                    <option value="Fresh Tulang">Fresh Tulang</option>
                                    <option value="Frozen">Frozen</option>
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
