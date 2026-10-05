@extends('user::daftar_rekomendator/masterrekom')
@section('title', $title)
@section('link_href')

@endsection

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h5 class="m-0">{{ $menu }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <form id="form-rekomendator" method="POST" action="{{ route('daftarrekomendator.save') }}">
                                    @csrf
                                    <input type="hidden" id="IdRekomendator" name="IdRekomendator" value="">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="nama_rekomendator">Nama Rekomendator</label>
                                                <input type="text" id="nama_rekomendator" name="nama_rekomendator"
                                                    placeholder="Nama Lengkap" class="form-control" autocomplete="off" value="{{ old('nama_rekomendator') }}" required>
                                                @error('nama_rekomendator')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="kategori">Kategori</label>
                                                <select class="form-control select2" id="kategori" name="kategori" required>
                                                    <option value="" selected disabled>-- Pilih Kategori --
                                                    </option>
                                                    @foreach ($kategori as $kat)
                                                        <option value="{{ $kat->kode_kategori }}">
                                                            {{ $kat->kategori_rekomendator }}</option>
                                                    @endforeach
                                                </select>
                                                @error('kategori')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="pekerjaan">Pekerjaan</label>
                                                <input type="text" id="pekerjaan" name="pekerjaan"
                                                    placeholder="Pekerjaan" class="form-control" autocomplete="off" value="{{ old('pekerjaan') }}" required>
                                                @error('pekerjaan')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="alamat">Alamat</label>
                                                <textarea id="alamat" name="alamat" rows="4" class="form-control" placeholder="Alamat Lengkap" autocomplete="off" value="{{ old('alamat') }}" required></textarea>
                                                @error('alamat')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="no_hp">No. Handphone</label>
                                                <input type="text" id="no_hp" name="no_hp"
                                                    placeholder="08xxxxxxxxxx" class="form-control" autocomplete="off" value="{{ old('no_hp') }}" required>
                                                @error('no_hp')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="email">Email</label>
                                                <input type="email" id="email" name="email" placeholder="Email"
                                                    class="form-control" autocomplete="off" value="{{ old('email') }}" required>
                                                @error('email')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="nama_bank">Nama Bank</label>
                                                <input type="text" id="nama_bank" name="nama_bank"
                                                    placeholder="Contoh: BCA, BRI" class="form-control" autocomplete="off" value="{{ old('nama_bank') }}"required>
                                                @error('nama_bank')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="no_rekening">No. Rekening</label>
                                                <input type="text" id="no_rekening" name="no_rekening"
                                                    placeholder="Nomor Rekening" class="form-control" autocomplete="off" value="{{ old('no_rekening') }}" required>
                                                @error('no_rekening')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="atasnama_rekening">Atas Nama Rekening</label>
                                                <input type="text" id="atasnama_rekening" name="atasnama_rekening"
                                                    placeholder="Atas Nama" class="form-control" autocomplete="off" value="{{ old('atasnana_rekening') }}" required>
                                                @error('atasnama_rekening')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <small class="text-danger"> <code>*</code> Email harus aktif dikarenakan untuk mengirimkan Kode Rekomendator <code>*</code></small>
                                            </div>
                                            <div class="col-md-6">
                                                <button type="submit" id="submit-rekomendator" class="btn btn-success float-right" style="margin-left:10px;"><i class="fas fa-paper-plane"></i> Submit</button>
                                            </div>
                                        </div>
                                        {{-- <button id="btn-reset" class="btn btn-warning float-right">Reset</button> --}}
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

            loadEvent()

            function loadEvent()
            {
                EventSubmit()
            }

            function EventSubmit() {
                $('#submit-rekomendator').click(function(e) {
                    e.preventDefault(); // cegah submit langsung
                    Swal.fire({
                        title: "Konfirmasi",
                        text: "Apakah Data Sudah Benar dan Tidak Ada yang Kosong ? Tekan Ya untuk menyimpan data",
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonText: "Ya",
                        cancelButtonText: "Tidak"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#loading').show()
                            $('#form-rekomendator').submit();
                        }
                    });
                });
            }

        });
    </script>
@endsection
