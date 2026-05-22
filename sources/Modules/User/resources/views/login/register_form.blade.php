@extends('user::login/masterlogin')
@section('title', $title)
@section('link_href')
@endsection

@section('content')
    <div class="login-box">
        <div class="card card-outline card-success">
            <div class="card-header">
                <i class="fas fa-edit"></i><b> Form Pendaftaran</b>
            </div>
            <div class="card-body">

                <form id="form-login" method="POST" action="{{route('register.save')}}">
                    {{ csrf_field() }}
                    {{-- Form Data Diri --}}
                    <div id="form-1" >
                        <p class="login-box-msg text-bold">Data Pribadi</p>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Nama Calon Mahasiswa" name="nama"
                                id="nama">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>
                        <small><code id="warning_nik"></code></small>
                        <div class="input-group mb-3">
                            <input type="number" class="form-control" onkeyup="checkNIK()" placeholder="NIK Calon Mahasiswa" name="nik"
                                id="nik">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="No HP Aktif Calon Mahasiswa"
                                name="nohp" id="nohp">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-phone"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <select class="form-control select2" id="provinsi" name="provinsi" style="width: 100%;">
                                <option value="" selected disabled>-- Pilih Provinsi Tinggal --</option>
                                @foreach ($provinsi as $p)
                                    <option value="{{$p->idprov}}">{{$p->nama_provinsi}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <select class="form-control select2" id="kabupaten" name="kabupaten">
                                <option value="" selected disabled>-- Pilih Kabupaten/Kota Tinggal --</option>
                            </select>
                        </div>
                        <div class="float-right">
                            <button type="button" id="next-1" class="btn btn-primary btn-block">
                                Next <i class="fas fa-angle-right"></i>
                            </button>
                            <!-- /.col -->
                        </div>
                    </div>

                    {{-- Form Akun --}}
                    <div id="form-2" style="display: none;">
                        <p class="login-box-msg text-bold">Data Akun</p>
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" placeholder="Email Calon Mahasiswa" name="email"
                                id="email">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        <small><code id="warning"></code></small>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" onkeyup="checkPassword()" placeholder="Password"
                                name="password" id="password">
                            {{-- <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div> --}}

                            <div class="input-group-append">
                            {{-- Tambahkan ID dan style cursor pointer --}}
                            <div class="input-group-text" id="toggle-password" style="cursor: pointer;">
                                {{-- Ubah default icon jadi mata (fa-eye) --}}
                                <span class="fas fa-eye"></span>
                            </div>
                        </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" onkeyup="checkPassword()"
                                placeholder="Konfirmasi Password" name="password1" id="password1">
                            {{-- <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div> --}}
                            <div class="input-group-append">
                                {{-- Tambahkan ID dan style cursor pointer --}}
                                <div class="input-group-text" id="toggle-password1" style="cursor: pointer;">
                                    {{-- Ubah default icon jadi mata (fa-eye) --}}
                                    <span class="fas fa-eye"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <button type="button" id="prev-1" class="btn btn-primary btn-block">
                                    <i class="fas fa-angle-left"></i> prev
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-success btn-block">
                                    <i class="fas fa-paper-plane"></i> Submit
                                </button>
                            </div>
                            <!-- /.col -->
                        </div>
                    </div>
                </form>

            </div>
            <!-- /.card-body -->
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%'
            })
            loadEvent()

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
            $('#toggle-password1').click(function(){
                var passwordField1 = $('#password1');
                var passwordIcon = $(this).find('span');

                // Cek tipe input saat ini
                if(passwordField1.attr('type') === 'password'){
                    // Ubah jadi text (terlihat)
                    passwordField1.attr('type', 'text');
                    // Ubah icon jadi mata dicoret
                    passwordIcon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    // Balikin jadi password (tersembunyi)
                    passwordField1.attr('type', 'password');
                    // Balikin icon jadi mata biasa
                    passwordIcon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
            // ---------------------------

            function loadEvent() {
                Next1()
                Prev1()
                submitRegist()
                KabKota()
            }

            function Next1() {
                $('#next-1').click(function(e) {
                    e.preventDefault();
                    $('#form-1').hide()
                    $('#form-2').show()
                });
            }

            function Prev1() {
                $('#prev-1').click(function(e) {
                    e.preventDefault();
                    $('#form-1').show()
                    $('#form-2').hide()
                });
            }

            function KabKota()
            {
                $('#provinsi').on('change', function() {
                    let provId = $(this).val();

                    $.ajax({
                        type: "GET",
                        url: '{!! url('GetKabupaten') !!}' + '/' + provId,
                        dataType: "JSON",
                        beforeSend: function(response) {
                            $('#loading').show()
                            $('#kabupaten').empty().html('<option value="" selected disabled>-- Pilih Kabupaten/Kota Tinggal --</option>');
                        },
                        success: function(data) {
                            $('#loading').hide()
                            if (data.hasil == 0) {
                                notifalert('Information', 'Data Kabupaten/Kota Tidak Ditemukan','error')
                            } else {
                                let dis1 = ''
                                for (i = 0; i < data.kab.length; i++) {
                                    dis1 += '<option value="' + data.kab[i].idkab + '">'+data.kab[i].nama_kabupaten+'</option>'
                                }
                                $('#kabupaten').append(dis1);
                            }
                        }
                    });
                    return false;
                    // if(provId) {
                    //     $.get('{{$parameter->api_kab_kota}}'+provId+'.json', function(data) {
                    //         $.each(data, function(index, item) {
                    //             $('#kabupaten').append('<option value="'+item.id+'">'+item.name+'</option>');
                    //         });
                    //     });
                    // }
                });
            }

            function validation() {
            let nama    = $('#nama').val()
            let nik     = $('#nik').val()
            let nohp    = $('#nohp').val()
            let prov    = $('#provinsi').val()
            let kabk    = $('#kabupaten').val()
            let email   = $('#email').val()
            let pass    = $('#password').val()
            let repass  = $('#password1').val()

            let notif = ''

            // Regex Pattern untuk validasi final (8 char, upper, number, no symbol)
            // let passwordRegex = /^(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/;

            if (nama == '' || nama == null) {
                notif = 'Nama Calon Mahasiswa Tidak Boleh Kosong';
                $('#prev-1').trigger('click');
            } else if (nik == '' || nik == null) {
                notif = 'NIK Calon Mahasiswa Tidak Boleh Kosong';
                $('#prev-1').trigger('click');
            } else if (nohp == '' || nohp == null) {
                notif = 'No HP Calon Mahasiswa Tidak Boleh Kosong';
                $('#prev-1').trigger('click');
            } else if (prov == '' || prov == null) {
                notif = 'Provinsi Tempat Tinggal Tidak Boleh Kosong';
                $('#prev-1').trigger('click');
            } else if (kabk == '' || kabk == null) {
                notif = 'Kabupaten/Kota Tinggal Tidak Boleh Kosong';
                $('#prev-1').trigger('click');
            } else if (email == '' || email == null) {
                notif = 'Email Tidak Boleh Kosong';
                $('#next-1').trigger('click');
            }
            // --- MODIFIKASI VALIDASI PASSWORD DISINI ---
            else if (pass == '' || pass == null) {
                notif = 'Password Tidak Boleh Kosong';
                $('#next-1').trigger('click');
            }
            // Validasi Logika Ketat
            else if (pass.length < 8) {
                notif = 'Password Minimal 8 Karakter!';
                $('#next-1').trigger('click');
            }
            else if (!/[A-Z]/.test(pass)) {
                notif = 'Password Harus Mengandung Huruf Besar!';
                $('#next-1').trigger('click');
            }
            else if (!/[0-9]/.test(pass)) {
                notif = 'Password Harus Mengandung Angka!';
                $('#next-1').trigger('click');
            }
            // else if (/[^a-zA-Z0-9]/.test(pass)) {
            //     notif = 'Password Tidak Boleh Mengandung Simbol!';
            //     $('#next-1').trigger('click');
            // }
            // -------------------------------------------
            else if (repass == '' || repass == null) {
                notif = 'Konfirmasi Password Tidak Boleh Kosong';
                $('#next-1').trigger('click');
            } else if (pass != repass) {
                notif = 'Konfirmasi Password Tidak Cocok'; // Tambahan keamanan
                $('#next-1').trigger('click');
            } else {
                notif = 'success'
            }
            return notif;
        }

            function submitRegist()
            {
                $('#form-login').on('submit', function(e){
                    e.preventDefault();
                    let validasiku = validation()
                    if(validasiku=='success'){
                            Swal.fire({
                            title: 'Information',
                            text: 'Apakah Data Anda Sudah Benar ?',
                            icon: 'question',
                            showConfirmButton: true,
                            showCancelButton: true,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                this.submit();
                            }else{
                                return false;
                            }
                        })
                    }else{
                        notifalert('Information',validasiku,'warning')
                    }
                });
            }

            function changeJurusanSekolah() {
                $('#jurusan_sekolah').on('change', function() {
                    let params = $(this).val()
                    // console.log(params)
                    $.ajax({
                        type: "GET",
                        url: '{!! url('showJurusan') !!}' + '/' + params,
                        dataType: "JSON",
                        beforeSend: function(response) {
                            $('#loading').show()
                            $('#pilihan1').empty()
                            $('#pilihan2').empty()
                        },
                        success: function(data) {
                            $('#loading').hide()
                            if (data.hasil == 0) {
                                notifalert('Information', 'Data Jurusan Tidak Ditemukan',
                                    'error')
                            } else {
                                let dis1 =
                                    '<option value="" selected disabled>-- Pilihan Jurusan 1 --</option>';
                                let dis2 =
                                    '<option value="" selected disabled>-- Pilihan Jurusan 2 --</option>';

                                for (i = 0; i < data.jurusan.length; i++) {
                                    dis1 += '<option value="' + data.jurusan[i].KodeJurusan + '">'+data.jurusan[i].jenjang.jenjang+' - ' + data
                                        .jurusan[i].jurusan + '</option>'
                                    dis2 += '<option value="' + data.jurusan[i].KodeJurusan + '">'+data.jurusan[i].jenjang.jenjang+' - ' + data
                                        .jurusan[i].jurusan + '</option>'
                                }
                                $('#pilihan1').append(dis1);
                                $('#pilihan2').append(dis2);
                            }
                        }
                    });
                    return false;
                });
            }

            // $('#password,#password1').keypress(function(event) {
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
            var password_re = $('#password1').val();
            var error_msg = [];

            // Reset Class
            $('#password').removeClass('is-invalid is-valid');
            $('#warning').html('');

            // Cek Kesamaan Password (Konfirmasi)
            if (password != '' && password_re != '') {
                if (password != password_re) {
                    $('#password1').addClass('is-invalid');
                    $('#password1').removeClass('is-valid');
                } else {
                    $('#password1').removeClass('is-invalid');
                    $('#password1').addClass('is-valid');
                }
            }

            // Jika password kosong, stop
            if (password == '') return;

            // 1. Cek Minimal 8 Karakter
            if (password.length < 8) {
                error_msg.push('Min 8 Karakter');
            }

            // 2. Cek Huruf Besar (A-Z)
            if (!/[A-Z]/.test(password)) {
                error_msg.push('Harus ada Huruf Besar');
            }

            // 3. Cek Angka (0-9)
            if (!/[0-9]/.test(password)) {
                error_msg.push('Harus ada Angka');
            }

            // 4. Cek Simbol (Tidak boleh ada simbol)
            // Regex ini mendeteksi jika ada karakter SELAIN huruf dan angka
            // if (/[^a-zA-Z0-9]/.test(password)) {
            //     error_msg.push('Tidak boleh ada Simbol');
            // }

            if (error_msg.length > 0) {
                // Jika ada error
                $('#password').addClass('is-invalid');
                $('#password').removeClass('is-valid');
                $('#warning').html('*' + error_msg.join(', ')); // Tampilkan semua error
            } else {
                // Jika lolos semua syarat
                $('#password').removeClass('is-invalid');
                $('#password').addClass('is-valid');
                $('#warning').html('');
            }
        }

        function checkNIK() {
            var password = $('#nik').val()

            if (password.length < 16) {
                $('#nik').addClass('is-invalid');
                $('#nik').removeClass('is-valid');

                $('#warning_nik').html('*Mininum length : 16');
                // $('#submit').attr('disabled', 'disabled');
            } else {
                $('#nik').removeClass('is-invalid');
                $('#nik').addClass('is-valid');
                $('#warning_nik').html('');
            }
        }
    </script>
@endsection
