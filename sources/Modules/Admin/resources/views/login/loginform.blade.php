@extends('admin::template/layout/masterlogin')
@section('title', $title)
@section('link_href')
@endsection

@section('content')
{{-- Login-box --}}
<div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
        <div class="card-header">
            <i class="fas fa-sign-in-alt"></i><b> Login Admin</b>
        </div>
        <div class="card-body">
            @php
                $login_chance = Session::get('login_chance');
                if (Session::has('login_chance')) {
                    $chance = $login_chance['chance'];
                    $time = $login_chance['time_start'];
                } else {
                    $chance = 5;
                    $time = 0;
                }

                if (Session::has('time_chance')) {
                    $time_chance = date('i:s', Session::get('time_chance'));
                } else {
                    $time_chance = '00:00';
                }
            @endphp
            @if ($chance > 0)
                <p class="login-box-msg text-bold">Start Your Session</p>
                <form id="form-login" method="POST" action="{{route('admin.loginaction')}}">
                    {{ csrf_field() }}
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Email" name="email" id="email"
                            required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Password" name="password" id="password" required>
                        <div class="input-group-append">
                            {{-- Tambahkan ID dan style cursor pointer --}}
                            <div class="input-group-text" id="toggle-password" style="cursor: pointer;">
                                {{-- Ubah default icon jadi mata (fa-eye) --}}
                                <span class="fas fa-eye"></span>
                            </div>
                        </div>
                        {{-- <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div> --}}
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <a href="{{route('admin.ForgotPassword.show')}}" class="btn btn-danger btn-block">Forgot
                                Password</a>
                        </div>
                        <!-- /.col -->
                        <div class="col-6">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            @else
                <div class="alert alert-warning text-center">
                    Kesempatan login habis. Silakan tunggu:
                    <h1 id="time_remaining" class="text-danger font-weight-bold text-center mt-2"></h1>
                </div>
            @endif
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>
<!-- /.login-box -->
@endsection

@section('script')
<script>
    function chance() {
        $.ajax({
            url: '{{ route('admin.loginchance') }}',
            success: function(data) {
                console.log(data);
            },
        });
    }

    $(function() {
        //Initialize Select2 Elements
        @if ($chance <= 0)
            var timer2 = '{{ $time_chance }}';
            var interval = setInterval(function() {

                var timer = timer2.split(':');
                //by parsing integer, I avoid all extra string processing
                var minutes = parseInt(timer[0], 10);
                var seconds = parseInt(timer[1], 10);
                --seconds;
                minutes = (seconds < 0) ? --minutes : minutes;
                if (minutes < 0) clearInterval(interval);
                seconds = (seconds < 0) ? 59 : seconds;
                seconds = (seconds < 10) ? '0' + seconds : seconds;
                //minutes = (minutes < 10) ?  minutes : minutes;

                if (minutes == 0 && seconds == 0) {
                    window.location.href = "{{ url('admin') }}";
                }

                $('#time_remaining').html(minutes + ':' + seconds);
                timer2 = minutes + ':' + seconds;
            }, 1000);
        @endif
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
</script>
@endsection
