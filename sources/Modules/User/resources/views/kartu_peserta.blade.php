<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Peserta PMB - {{ $daftar->KodePendaftaran }}</title>
    <style>
        @page {
            margin: 1.2cm 1.5cm;
            size: A4 portrait;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11pt;
            color: #222;
            line-height: 1.35;
        }

        /* Kop Surat / Header */
        .header-table {
            width: 100%;
            border-bottom: 3px double #1b3a5b;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }

        .header-logo {
            width: 80px;
            vertical-align: middle;
            text-align: center;
        }

        .header-logo img {
            width: 72px;
            height: auto;
        }

        .header-text {
            vertical-align: middle;
            text-align: center;
            padding-left: 10px;
        }

        .univ-name {
            font-size: 15pt;
            font-weight: bold;
            color: #1b3a5b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }

        .univ-sub {
            font-size: 11pt;
            font-weight: bold;
            color: #444;
            margin-top: 2px;
            text-transform: uppercase;
        }

        .univ-address {
            font-size: 8pt;
            color: #666;
            margin-top: 3px;
        }

        /* Title Box */
        .title-box {
            text-align: center;
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            padding: 8px 10px;
            margin-bottom: 14px;
        }

        .card-title {
            font-size: 13pt;
            font-weight: bold;
            color: #1e293b;
            text-transform: uppercase;
            margin: 0;
            letter-spacing: 0.5px;
        }

        .card-subtitle {
            font-size: 9.5pt;
            color: #475569;
            margin-top: 3px;
        }

        /* Content Table */
        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        .content-left {
            width: 72%;
            vertical-align: top;
            padding-right: 15px;
        }

        .content-right {
            width: 28%;
            vertical-align: top;
            text-align: center;
        }

        /* Biodata Table */
        .bio-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
        }

        .bio-table td {
            padding: 4px 2px;
            vertical-align: top;
        }

        .bio-table td.label {
            width: 34%;
            color: #334155;
            font-weight: 500;
        }

        .bio-table td.colon {
            width: 3%;
            text-align: center;
            color: #334155;
        }

        .bio-table td.value {
            width: 63%;
            color: #0f172a;
            font-weight: 600;
        }

        .reg-number {
            font-size: 11pt;
            font-weight: bold;
            color: #1b3a5b;
            background-color: #e0f2fe;
            padding: 2px 6px;
            border-radius: 3px;
            display: inline-block;
        }

        /* Photo Frame */
        .photo-box {
            width: 115px;
            height: 150px;
            border: 2px solid #94a3b8;
            margin: 0 auto 8px auto;
            background-color: #f8fafc;
            text-align: center;
            overflow: hidden;
        }

        .photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-placeholder {
            font-size: 8pt;
            color: #94a3b8;
            line-height: 140px;
        }

        .barcode-badge {
            display: inline-block;
            border: 1px dashed #64748b;
            padding: 4px 8px;
            font-size: 8pt;
            color: #475569;
            background: #fff;
            margin-top: 4px;
        }

        /* Info & Tata Tertib */
        .section-header {
            font-size: 9.5pt;
            font-weight: bold;
            color: #1b3a5b;
            text-transform: uppercase;
            border-bottom: 1.5px solid #cbd5e1;
            padding-bottom: 3px;
            margin-bottom: 6px;
            margin-top: 10px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
            margin-bottom: 10px;
            border: 1px solid #e2e8f0;
        }

        .info-table th {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 5px 8px;
            text-align: left;
            font-size: 8.5pt;
            color: #475569;
        }

        .info-table td {
            border: 1px solid #e2e8f0;
            padding: 5px 8px;
            color: #1e293b;
        }

        /* Tata Tertib List */
        .rules-list {
            margin: 0;
            padding-left: 18px;
            font-size: 8pt;
            color: #334155;
            line-height: 1.4;
        }

        .rules-list li {
            margin-bottom: 3px;
        }

        /* Signatures */
        .sign-table {
            width: 100%;
            margin-top: 20px;
            font-size: 9pt;
        }

        .sign-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }

        .sign-space {
            height: 55px;
        }

        .sign-name {
            font-weight: bold;
            text-decoration: underline;
            color: #0f172a;
        }

        .sign-title {
            font-size: 8pt;
            color: #64748b;
        }

        .footer-note {
            margin-top: 18px;
            border-top: 1px dashed #cbd5e1;
            padding-top: 6px;
            font-size: 7pt;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- Kop Header -->
    <table class="header-table">
        <tr>
            <td class="header-logo">
                @if($logo)
                    <img src="{{ $logo }}" alt="Logo TSU">
                @else
                    <div style="font-weight: bold; font-size: 16pt; color: #1b3a5b;">TSU</div>
                @endif
            </td>
            <td class="header-text">
                <div class="univ-name">Universitas Tiga Serangkai</div>
                <div class="univ-sub">Panitia Penerimaan Mahasiswa Baru (PMB)</div>
                <div class="univ-address">
                    Jl. Slamet Riyadi No. 456, Surakarta, Jawa Tengah | Telp: (0271) 714344<br>
                    Website: pmb.tsu.ac.id | Email: info@tsu.ac.id
                </div>
            </td>
        </tr>
    </table>

    <!-- Card Title -->
    <div class="title-box">
        <div class="card-title">KARTU TANDA PESERTA SELEKSI PMB</div>
        <div class="card-subtitle">
            Tahun Akademik {{ $daftar->batch->tahun_akademik ?? (date('Y') . '/' . (date('Y') + 1)) }} &bull;
            {{ $daftar->batch->nama_batch ?? 'Gelombang Reguler' }}
        </div>
    </div>

    <!-- Content: Data Peserta + Foto -->
    <table class="content-table">
        <tr>
            <!-- Kiri: Data Peserta -->
            <td class="content-left">
                <table class="bio-table">
                    <tr>
                        <td class="label">Nomor Registrasi</td>
                        <td class="colon">:</td>
                        <td class="value">
                            <span class="reg-number">{{ $daftar->KodePendaftaran }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Nama Lengkap</td>
                        <td class="colon">:</td>
                        <td class="value">{{ strtoupper($bio->nama ?? '-') }}</td>
                    </tr>
                    <tr>
                        <td class="label">NIK / No. KTP</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $bio->nik ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Tempat, Tanggal Lahir</td>
                        <td class="colon">:</td>
                        <td class="value">
                            {{ $bio->tempat_lahir ?? '-' }},
                            {{ $bio->tgl_lahir ? date('d-m-Y', strtotime($bio->tgl_lahir)) : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Jenis Kelamin</td>
                        <td class="colon">:</td>
                        <td class="value">
                            @if(isset($bio->jenkel))
                                {{ $bio->jenkel == 'L' ? 'Laki-laki' : ($bio->jenkel == 'P' ? 'Perempuan' : $bio->jenkel) }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Asal Sekolah</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $bio->nama_sekolah ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">No. Handphone / WA</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $bio->nohp ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Jalur Pendaftaran</td>
                        <td class="colon">:</td>
                        <td class="value">
                            {{ $daftar->jalur->nama_jalur ?? '-' }}
                            @if($daftar->jenisbeasiswa)
                                ({{ $daftar->jenisbeasiswa->nama_beasiswa ?? '' }})
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Pilihan Program Studi 1</td>
                        <td class="colon">:</td>
                        <td class="value">
                            {{ $daftar->prodi1->jenjang->nama_jenjang ?? '' }}
                            {{ $daftar->prodi1->NamaJurusan ?? '-' }}
                        </td>
                    </tr>
                    @if($daftar->prodi2)
                    <tr>
                        <td class="label">Pilihan Program Studi 2</td>
                        <td class="colon">:</td>
                        <td class="value">
                            {{ $daftar->prodi2->jenjang->nama_jenjang ?? '' }}
                            {{ $daftar->prodi2->NamaJurusan ?? '-' }}
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td class="label">Waktu Kuliah</td>
                        <td class="colon">:</td>
                        <td class="value">{{ $daftar->waktukuliah->waktu_kuliah ?? 'Reguler Pagi' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Status Biaya Pendaftaran</td>
                        <td class="colon">:</td>
                        <td class="value" style="color: #16a34a;">LUNAS / TERKONFIRMASI</td>
                    </tr>
                </table>
            </td>

            <!-- Kanan: Foto & Verifikasi -->
            <td class="content-right">
                <div class="photo-box">
                    @if($photo)
                        <img src="{{ $photo }}" alt="Foto Peserta">
                    @else
                        <div class="photo-placeholder">Pas Foto<br>3 x 4</div>
                    @endif
                </div>
                <div class="barcode-badge">
                    <strong>VALIDASI SISTEM PMB</strong><br>
                    {{ $daftar->KodePendaftaran }}<br>
                    <span style="font-size: 7pt; color: #16a34a;">&#10004; TERVERIFIKASI</span>
                </div>
            </td>
        </tr>
    </table>

    <!-- Jadwal Seleksi / Ujian -->
    <div class="section-header">Informasi Seleksi & Pelaksanaan Ujian</div>
    <table class="info-table">
        <thead>
            <tr>
                <th style="width: 25%;">Materi Seleksi</th>
                <th style="width: 30%;">Jadwal Pelaksanaan</th>
                <th style="width: 45%;">Tempat / Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Online Assessment / CBT</strong></td>
                <td>
                    @if($daftar->batch && $daftar->batch->tglmulai && $daftar->batch->tglselesai)
                        {{ date('d M Y', strtotime($daftar->batch->tglmulai)) }} - {{ date('d M Y', strtotime($daftar->batch->tglselesai)) }}
                    @else
                        Sesuai jadwal gelombang aktif
                    @endif
                </td>
                <td>
                    Portal PMB Online TSU (Menu <em>Online Test / Assessment</em>) atau Lab CBT Kampus TSU
                </td>
            </tr>
            <tr>
                <td><strong>Verifikasi Berkas</strong></td>
                <td>Mengikuti jadwal pengumuman panitia</td>
                <td>Unggah di portal PMB atau diserahkan ke Panitia PMB Kampus TSU</td>
            </tr>
        </tbody>
    </table>

    <!-- Tata Tertib -->
    <div class="section-header">Tata Tertib & Petunjuk Peserta Ujian</div>
    <ol class="rules-list">
        <li>Peserta <strong>wajib mencetak</strong> Kartu Tanda Peserta ini dan membawanya bersama identitas resmi (KTP/Kartu Pelajar) saat seleksi/verifikasi.</li>
        <li>Peserta wajib mengikuti seluruh tahapan seleksi sesuai jadwal gelombang yang telah ditentukan oleh panitia.</li>
        <li>Bagi yang mengikuti tes secara online, pastikan perangkat komputer/laptop dan jaringan internet dalam kondisi stabil sebelum memulai ujian.</li>
        <li>Kecurangan dalam bentuk apapun selama pelaksanaan ujian akan mengakibatkan peserta langsung <strong>dinyatakan gugur</strong>.</li>
        <li>Hasil seleksi PMB akan diumumkan secara resmi melalui portal PMB pada akun masing-masing pendaftar.</li>
    </ol>

    <!-- Tanda Tangan -->
    <table class="sign-table">
        <tr>
            <td>
                Surakarta, {{ $tglCetak }}<br>
                Peserta Ujian,
                <div class="sign-space"></div>
                <div class="sign-name">{{ strtoupper($bio->nama ?? 'PESERTA') }}</div>
                <div class="sign-title">Tanda Tangan & Nama Terang</div>
            </td>
            <td>
                Surakarta, {{ $tglCetak }}<br>
                Panitia PMB Universitas Tiga Serangkai,
                <div class="sign-space"></div>
                <div class="sign-name">PANITIA SELEKSI PMB</div>
                <div class="sign-title">Cap & Tanda Tangan Panitia</div>
            </td>
        </tr>
    </table>

    <div class="footer-note">
        Dokumen ini dicetak otomatis dari Sistem Informasi Penerimaan Mahasiswa Baru (PMB) Universitas Tiga Serangkai pada {{ date('d/m/Y H:i') }}.
    </div>

</body>
</html>
