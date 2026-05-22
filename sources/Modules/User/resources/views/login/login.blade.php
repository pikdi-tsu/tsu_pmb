@extends('user::login/masterlogin')
@section('title', $title)
@section('link_href')
@endsection

@section('content')
    <div class="login-box">
        <div class="card card-outline card-success">
            <div class="card-header">
                <i class="fas fa-user"></i><b> Login PMB</b>
            </div>
            <div class="card-body">
                {{-- Form Akun --}}
                <form id="form-login" method="POST" action="{{route('login.action')}}">
                    @csrf
                    <p class="login-box-msg text-bold">Form Login</p>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Email Calon Mahasiswa" name="email"
                            id="email" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>

                    {{-- BAGIAN PASSWORD (DIUBAH) --}}
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Password"
                            name="password" id="password" required>
                        <div class="input-group-append">
                            {{-- Tambahkan ID dan style cursor pointer --}}
                            <div class="input-group-text" id="toggle-password" style="cursor: pointer;">
                                {{-- Ubah default icon jadi mata (fa-eye) --}}
                                <span class="fas fa-eye"></span>
                            </div>
                        </div>
                    </div>
                    {{-- END BAGIAN PASSWORD --}}

                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{route('ResetPassword')}}" class="btn btn-danger btn-block">
                                Lupa Password
                            </a>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" form="form-login" class="btn btn-success btn-block">
                                Login
                            </button>
                        </div>
                        </div>
                </form>
                <p style="margin-top:10px;">
                    <a href="{{ route('register') }}" class="text-center">Registrasi Akun Baru</a>
                </p>
            </div>
            </div>
    </div>
@endsection

@section('script')
    <script>
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // --- FITUR SHOW PASSWORD ---
            $('#toggle-password').click(function(){
                var passwordField = $('#password');
                var passwordIcon = $(this).find('span');

                // Cek tipe input saat ini
                if(passwordField.attr('type') === 'password'){
                    // Ubah jadi text (terlihat)
                    passwordField.attr('type', 'text');
                    // Ubah icon jadi mata dicoret
                    passwordIcon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    // Balikin jadi password (tersembunyi)
                    passwordField.attr('type', 'password');
                    // Balikin icon jadi mata biasa
                    passwordIcon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
            // ---------------------------

            // $('#password').keypress(function(event) {
            //     var ew = event.which;
            //     if (48 <= ew && ew <= 57)
            //         return true;
            //     if (65 <= ew && ew <= 90)
            //         return true;
            //     if (97 <= ew && ew <= 122)
            //         return true;
            //     return false;
            // });
        });

        function checkPassword() {
            var password = $('#password').val();

            if (password.length < 8) {
                $('#password').addClass('is-invalid');
                $('#password').removeClass('is-valid');

                $('#warning').html('*Mininum length : 8');
                $('#submit').prop('disabled', false);
            } else {
                pass_numb = password.replace(/[^0-9]/g, '').length;
                // pass_char = password.replace(/[0-9]/g, '').length;

                if (pass_numb == 0) {
                    $('#password').addClass('is-invalid');
                    $('#password').removeClass('is-valid');

                    $('#warning').html('*Must contain Number');
                    $('#submit').attr('disabled', 'disabled');
                }
                // else if (pass_char == 0) {
                //     $('#password').addClass('is-invalid');
                //     $('#password').removeClass('is-valid');

                //     $('#warning').html('*Must contain Letter');
                //     $('#submit').attr('disabled', 'disabled');
                // }
                else {
                    $('#password').removeClass('is-invalid');
                    $('#password').addClass('is-valid');
                    $('#warning').html('');
                }
            }
        }
    </script>
@endsection
