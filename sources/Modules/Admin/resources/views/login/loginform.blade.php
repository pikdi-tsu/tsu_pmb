@extends('admin::template/layout/masterlogin')
@section('title', $title)
@section('link_href')
    <style>
        #secret-trigger {
            cursor: default;
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }
        .btn-tsu-sso {
            background-color: #1d7a87;
            border-color: #1d7a87;
            color: #ffffff;
            font-weight: 600;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        .btn-tsu-sso:hover {
            background-color: #094b54;
            border-color: #094b54;
            color: #ffffff;
        }
    </style>
@endsection

@section('content')
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="javascript:void(0)" id="secret-trigger" class="h1 text-dark" style="text-decoration: none;">
                    <b>TSU</b> <br> PMB ADMIN
                </a>
            </div>
            <div class="card-body">
                <p class="login-box-msg text-bold">Start Your Session</p>

                {{-- ALERT CUSTOM --}}
                @if(Session::has('alert'))
                    <div class="alert alert-{{ Session::get('alert')['status'] ?? 'info' }} alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        @if(isset(Session::get('alert')['title']))
                            <h5><i class="icon fas fa-info-circle"></i> {{ Session::get('alert')['title'] }}</h5>
                        @endif
                        {!! Session::get('alert')['message'] !!}
                    </div>
                @endif

                {{-- ALERT ERROR STANDARD --}}
                @if(Session::has('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-ban"></i> Error!</h5>
                        {!! Session::get('error') !!}
                    </div>
                @endif

                {{-- ALERT SUCCESS STANDARD --}}
                @if(Session::has('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-check"></i> Sukses!</h5>
                        {!! Session::get('success') !!}
                    </div>
                @endif

                {{-- BAGIAN 1: SSO (Default Utama) --}}
                <div id="sso-section">
                    <a href="{{ route('sso.login') }}" id="btn-sso" onclick="freezeButton(this)" class="btn btn-tsu-sso btn-block btn-lg mb-3">
                        <i class="fas fa-fingerprint mr-2"></i> <b>Login with TSU</b>
                    </a>
                    <p class="text-muted text-center text-sm mt-3">
                        Khusus Dosen, Tendik, dan Panitia PMB. Masuk menggunakan akun SSO TSU Homebase.
                    </p>
                </div>

                {{-- BAGIAN 2: PIKDI ACCESS (Tersembunyi via Klik 5x pada Judul) --}}
                <div id="manual-section" style="display: none;">
                    <hr>
                    <p class="text-center text-danger text-sm"><b><i class="fas fa-user-secret"></i> PIKDI Access</b></p>

                    <form onsubmit="document.getElementById('btn-login').disabled = true; document.getElementById('btn-login').innerText = 'Loading...';" id="form-login" method="POST" action="{{ route('admin.loginaction') }}">
                        @csrf

                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Email / NIK / Username" name="identity" id="identity" value="{{ old('identity') }}" required>
                            <div class="input-group-append">
                                <div class="input-group-text"><span class="fas fa-user"></span></div>
                            </div>
                        </div>

                        <div class="input-group mb-3">
                            <input type="password" class="form-control" placeholder="Password" name="password" id="password" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span type="button" id="toggle-password" class="fas fa-eye" style="cursor: pointer;"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" id="btn-login" class="btn btn-dark btn-block btn-sm">PIKDI Login</button>
                            </div>
                        </div>

                        <div class="mt-2 text-center">
                            <a href="#" id="btn-close-manual" class="text-xs text-muted">Tutup Akses PIKDI</a>
                        </div>
                    </form>
                </div>

                {{-- Link Darurat ke Rescue Mode --}}
                <div class="mt-4 pt-2 border-top text-center">
                    <a href="{{ route('rescue') }}" class="text-xs text-muted">
                        <i class="fas fa-shield-halved mr-1"></i> Mode Darurat (Rescue Login)
                    </a>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        // Mencegah klik berulang tombol SSO
        function freezeButton(element) {
            var originalWidth = element.offsetWidth;
            element.style.width = originalWidth + 'px';
            element.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2"></i> Connecting...';
            element.classList.add('disabled');
            element.style.pointerEvents = 'none';
            element.style.cursor = 'not-allowed';
        }

        $(document).ready(function() {
            // Trigger Rahasia 5 Klik dalam 500ms
            let clickCount = 0;
            let clickTimer;
            let requiredClicks = 5;

            $('#secret-trigger').click(function(e) {
                e.preventDefault();
                clickCount++;
                clearTimeout(clickTimer);

                if (clickCount === requiredClicks) {
                    $('#manual-section').slideDown(500);
                    clickCount = 0;
                } else {
                    clickTimer = setTimeout(function() {
                        clickCount = 0;
                    }, 500);
                }
            });

            // Tombol Tutup PIKDI form
            $('#btn-close-manual').click(function(e) {
                e.preventDefault();
                $('#manual-section').slideUp(300);
            });

            // Logic Timer SSO Lockdown
            let ssoSeconds = {{ session('retry_seconds_sso', $existing_sso_seconds ?? 0) }};
            if (ssoSeconds > 0) {
                let btnSso = $('#btn-sso');
                let alertTimer = $('#sso-alert-timer');

                freezeButton(btnSso[0]);

                function updateSsoTimer() {
                    btnSso.html(`<i class="fas fa-hourglass-half fa-spin mr-2"></i> Tunggu ${ssoSeconds}s`);
                    if (alertTimer.length > 0) {
                        alertTimer.text(ssoSeconds);
                    }
                    ssoSeconds--;

                    if (ssoSeconds < 0) {
                        clearInterval(timerSso);
                        btnSso.removeClass('disabled');
                        btnSso.html('<i class="fas fa-fingerprint mr-2"></i> <b>Login with TSU</b>');
                        btnSso.css('pointer-events', 'auto').css('cursor', 'pointer').css('width', 'auto');
                        $('.alert-danger').fadeOut();
                    }
                }

                updateSsoTimer();
                let timerSso = setInterval(updateSsoTimer, 1000);
            }

            // Logic Timer Manual Login PIKDI Lockdown & Auto open
            let manualSeconds = {{ session('retry_seconds_manual', $existing_manual_seconds ?? 0) }};
            let oldIdentity = "{{ old('identity') }}";
            let isManualBlocked = manualSeconds > 0;

            if (oldIdentity !== "" || isManualBlocked) {
                $('#manual-section').show();
            }

            if (manualSeconds > 0) {
                let btnManual = $('#btn-login');
                let alertTimer = $('#sso-alert-timer');
                let originalText = btnManual.text();

                btnManual.prop('disabled', true).addClass('btn-secondary').removeClass('btn-dark');

                function updateManualTimer() {
                    btnManual.html(`<i class="fas fa-lock mr-1"></i> Locked (${manualSeconds}s)`);
                    if (alertTimer.length > 0) {
                        alertTimer.text(manualSeconds);
                    }
                    manualSeconds--;

                    if (manualSeconds < 0) {
                        clearInterval(timerManual);
                        btnManual.prop('disabled', false).removeClass('btn-secondary').addClass('btn-dark');
                        btnManual.text(originalText);
                        $('.alert-danger').fadeOut();
                    }
                }

                updateManualTimer();
                let timerManual = setInterval(updateManualTimer, 1000);
            }

            // Toggle Password
            $('#toggle-password').click(function() {
                let passInput = $('#password');
                let icon = $(this);
                if (passInput.attr('type') === 'password') {
                    passInput.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passInput.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
        });
    </script>
@endsection
