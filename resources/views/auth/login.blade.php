@extends('layouts.auth')

@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="#"><b>Tally Boneless</b></a>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Masukan Username dan Password</p>

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" name="login" class="form-control" placeholder="Username" required autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password"
                            required>

                        <div class="input-group-append">
                            <div class="input-group-text" id="iconPassword" style="cursor:pointer;">
                                <span id="iconPass" class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger mt-2">
                            username atau password salah!
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block btn-sm">Login</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $('#password').on('input', function() {

            if ($(this).val().length > 0) {
                $('#iconPass').removeClass('fa-lock').addClass('fa-eye');
            } else {
                $('#iconPass').removeClass('fa-eye fa-eye-slash').addClass('fa-lock');
                $('#password').attr('type', 'password');
            }

        });

        $('#iconPassword').click(function() {

            if ($('#password').val().length === 0) return;

            let input = $('#password');

            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                $('#iconPass').removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                input.attr('type', 'password');
                $('#iconPass').removeClass('fa-eye-slash').addClass('fa-eye');
            }

        });
    </script>
@endpush
