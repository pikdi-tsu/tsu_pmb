<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Print Assessment DISC - {{ $nama }}</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Pengaturan Dasar */
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #000;
            margin: 0;
            padding: 20px;
            line-height: 1.3;
        }

        /* Kop Surat TSU (Format Table agar stabil diprint) */
        .header {
            display: table;
            width: 100%;
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }

        .header-logo {
            display: table-cell;
            width: 25%;
            vertical-align: middle;
            text-align: left;
        }

        .header-logo img {
            width: 100px;
        }

        .header-title {
            display: table-cell;
            width: 50%;
            vertical-align: middle;
            text-align: center;
        }

        .header-title h2 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
            text-decoration: underline;
            color: #333;
            letter-spacing: 1px;
        }

        .header-spacer {
            display: table-cell;
            width: 25%;
        }

        /* Identitas */
        .identitas-table {
            width: 100%;
            margin-bottom: 15px;
            font-weight: bold;
            font-size: 11px;
        }

        .identitas-table td {
            padding: 3px;
        }

        /* Tabel Rekap */
        .score-table {
            width: 60%;
            margin: 0 auto 15px auto;
            border-collapse: collapse;
            text-align: center;
            font-size: 11px;
        }

        .score-table th,
        .score-table td {
            border: 1px solid #000;
            padding: 4px;
        }

        .bg-gray {
            background-color: #e0e0e0 !important;
        }

        .text-red {
            color: red !important;
        }

        /* Layout Grafik (Table Layout Anti-Pecah) */
        .chart-container {
            display: table;
            width: 100%;
            table-layout: fixed;
            margin-bottom: 15px;
        }

        .chart-box {
            display: table-cell;
            padding: 0 10px;
            text-align: center;
            vertical-align: top;
        }

        .chart-title {
            font-weight: bold;
            font-size: 10px;
            margin-bottom: 5px;
            border: 1px solid #000;
            padding: 4px;
            background-color: #f5f5f5 !important;
        }

        .canvas-wrapper {
            width: 170px;
            height: 170px;
            margin: 0 auto;
            border: 1px solid #000;
            padding: 5px;
            background: #fff;
        }

        .canvas-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* Layout 3 Kolom Deskripsi (Table Layout Anti-Pecah) */
        .desc-container {
            display: table;
            width: 100%;
            table-layout: fixed;
            margin-bottom: 15px;
        }

        .desc-col {
            display: table-cell;
            padding: 0 10px;
            vertical-align: top;
            border-right: 1px dashed #ccc;
        }

        .desc-col:first-child {
            padding-left: 0;
        }

        .desc-col:last-child {
            padding-right: 0;
            border-right: none;
        }

        .desc-col h4 {
            margin: 0 0 5px 0;
            text-decoration: underline;
            font-size: 11px;
        }

        .desc-col ul {
            padding-left: 15px;
            margin: 0;
        }

        .desc-col ul li {
            margin-bottom: 2px;
        }

        /* Box Bawah */
        .box-section {
            margin-bottom: 10px;
            page-break-inside: avoid;
        }

        .box-title {
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 3px;
        }

        .box-content {
            border: 1px solid #000;
            padding: 8px;
            text-align: justify;
            line-height: 1.4;
            background-color: #fff;
        }

        /* Footer */
        .footer-wrap {
            position: fixed;
            bottom: 15mm;
            left: 15mm;
            right: 15mm;
            width: calc(100% - 30mm);
        }

        .footer-table {
            display: table;
            width: 100%;
            font-size: 9px;
            color: #555;
        }

        .footer-left {
            display: table-cell;
            text-align: left;
            vertical-align: bottom;
        }

        .footer-right {
            display: table-cell;
            text-align: right;
            border-right: 4px solid #d4af37;
            padding-right: 10px;
        }

        /* PENGATURAN KERTAS A4 SAAT PRINT */
        @media print {
            .no-print {
                display: none !important;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            @page {
                size: A4 portrait;
                margin: 0;
            }

            body {
                padding: 15mm 15mm 35mm 15mm !important;
            }

            .chart-container,
            .desc-container,
            .box-section {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>
    <div class="no-print" style="text-align: right; margin-bottom: 15px;">
        <button onclick="window.print()"
            style="padding: 10px 20px; font-weight:bold; cursor: pointer; background: #ffeb3b; border: 1px solid #000; border-radius: 5px;">🖨️
            Print Kertas A4</button>
    </div>

    <div class="header">
        <div class="header-logo">
            <img src="{{ asset('public/assets/user/img/logotsu.png') }}" alt="TSU Logo">
        </div>
        <div class="header-title">
            <h2>HASIL ASSESSMENT DISC</h2>
        </div>
        <div class="header-spacer"></div>
    </div>

    <table class="identitas-table">
        <tr>
            <td width="15%">Nama</td>
            <td width="35%">: {{ $nama }}</td>
            <td width="15%">No Registrasi</td>
            <td width="35%">: {{ $noreg }}</td>
        </tr>
        <tr>
            <td>Batch Daftar</td>
            <td>: {{ $batch }}</td>
            <td>Jalur Daftar</td>
            <td>: {{ $jalur }}</td>
        </tr>
    </table>

    @php
        $tot1 = $l1['D'] + $l1['I'] + $l1['S'] + $l1['C'] + $l1['star'];
        $tot2 = $l2['D'] + $l2['I'] + $l2['S'] + $l2['C'] + $l2['star'];
    @endphp
    <table class="score-table">
        <tr>
            <th>Line</th>
            <th>D</th>
            <th>I</th>
            <th>S</th>
            <th>C</th>
            <th>*</th>
            <th>tot</th>
        </tr>
        <tr>
            <td><strong>1 (Most)</strong></td>
            <td>{{ $l1['D'] }}</td>
            <td>{{ $l1['I'] }}</td>
            <td>{{ $l1['S'] }}</td>
            <td>{{ $l1['C'] }}</td>
            <td>{{ $l1['star'] }}</td>
            <td class="text-red"><strong>{{ $tot1 }}</strong></td>
        </tr>
        <tr>
            <td><strong>2 (Least)</strong></td>
            <td>{{ $l2['D'] }}</td>
            <td>{{ $l2['I'] }}</td>
            <td>{{ $l2['S'] }}</td>
            <td>{{ $l2['C'] }}</td>
            <td>{{ $l2['star'] }}</td>
            <td class="text-red"><strong>{{ $tot2 }}</strong></td>
        </tr>
        <tr class="bg-gray">
            <td><strong>3 (Change)</strong></td>
            <td>{{ $l3['D'] }}</td>
            <td>{{ $l3['I'] }}</td>
            <td>{{ $l3['S'] }}</td>
            <td>{{ $l3['C'] }}</td>
            <td></td>
            <td></td>
        </tr>
    </table>

    <div class="chart-container">
        <div class="chart-box">
            <div class="chart-title">GRAPH 1 MOST<br><small>Mask Public Self</small></div>
            <div class="canvas-wrapper" id="wrap-chart1">
                <canvas id="chart1" width="170" height="170"></canvas>
            </div>
        </div>
        <div class="chart-box">
            <div class="chart-title">GRAPH 2 LEAST<br><small>Core Private Self</small></div>
            <div class="canvas-wrapper" id="wrap-chart2">
                <canvas id="chart2" width="170" height="170"></canvas>
            </div>
        </div>
        <div class="chart-box">
            <div class="chart-title">GRAPH 3 CHANGE<br><small>Mirror Perceived Self</small></div>
            <div class="canvas-wrapper" id="wrap-chart3">
                <canvas id="chart3" width="170" height="170"></canvas>
            </div>
        </div>
    </div>

    @if ($hasil)
        @php
            function safePrint($val)
            {
                if (!$val) {
                    return '-';
                }
                if (is_string($val)) {
                    return $val;
                }
                if (is_array($val)) {
                    if (isset($val['nama'])) {
                        return $val['nama'];
                    }
                    if (isset($val['karakter'])) {
                        return $val['karakter'];
                    }
                    return json_encode($val);
                }
                return $val;
            }
            function safeArray($val)
            {
                if (!$val) {
                    return [];
                }
                if (is_array($val)) {
                    return $val;
                }
                return [$val];
            }
        @endphp

        <div
            style="text-align: center; margin-bottom: 10px; font-weight: bold; text-decoration: underline; font-size:13px;">
            Gambaran Karakter</div>

        <div class="desc-container">
            <div class="desc-col">
                <h4>Kepribadian Saat di Publik</h4>
                <div style="font-weight:bold; margin-bottom:5px; color: #1565c0;">
                    {{ safePrint($hasil['karakter_publik'] ?? null) }}</div>
                <ul>
                    @foreach (safeArray($hasil['sifat_publik'] ?? null) as $sifat)
                        <li>{{ is_array($sifat) ? json_encode($sifat) : $sifat }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="desc-col">
                <h4>Kepribadian Asli</h4>
                <div style="font-weight:bold; margin-bottom:5px; color: #1565c0;">
                    {{ safePrint($hasil['karakter_asli'] ?? null) }}</div>
                <ul>
                    @foreach (safeArray($hasil['sifat_asli'] ?? null) as $sifat)
                        <li>{{ is_array($sifat) ? json_encode($sifat) : $sifat }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="desc-col">
                <h4>Kepribadian Saat Tertekan</h4>
                <div style="font-weight:bold; margin-bottom:5px; color: #1565c0;">
                    {{ safePrint($hasil['karakter_tekanan'] ?? null) }}</div>
                <ul>
                    @foreach (safeArray($hasil['sifat_tekanan'] ?? null) as $sifat)
                        <li>{{ is_array($sifat) ? json_encode($sifat) : $sifat }}</li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="box-section">
            <div class="box-title">Deskripsi Kepribadian:</div>
            <div class="box-content">
                {{ is_array($hasil['deskripsi'] ?? null) ? json_encode($hasil['deskripsi']) : $hasil['deskripsi'] ?? '-' }}
            </div>
        </div>
        <div class="box-section">
            <div class="box-title">Job Match :</div>
            <div class="box-content">
                {{ is_array($hasil['job_match'] ?? null) ? json_encode($hasil['job_match']) : $hasil['job_match'] ?? '-' }}
            </div>
        </div>
    @endif

    <div class="footer-wrap">
        <div class="footer-table">
            <div class="footer-left">
                Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }} pukul {{ date('H.i') }} WIB
            </div>
            <div class="footer-right">
                <strong>Tiga Serangkai University</strong><br>
                KH. Samanhudi St. No. 84 - 86, Laweyan, Surakarta 57142<br>
                Phone: 0271 - 716 500 | E-mail: info@tsu.ac.id
            </div>
        </div>
    </div>

    <script>
        function drawChart(canvasId, wrapperId, dataSkor, isLine3) {
            let canvas = document.getElementById(canvasId);
            let ctx = canvas.getContext('2d');

            canvas.width = 170;
            canvas.height = 170;

            let myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['D', 'I', 'S', 'C'],
                    datasets: [{
                        data: [dataSkor.D, dataSkor.I, dataSkor.S, dataSkor.C],
                        borderColor: '#000',
                        backgroundColor: '#000',
                        borderWidth: 2,
                        pointRadius: 4,
                        fill: false,
                        tension: 0
                    }]
                },
                options: {
                    responsive: false,
                    maintainAspectRatio: false,
                    animation: false,
                    scales: {
                        y: {
                            min: isLine3 ? -24 : 0,
                            max: 24
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            let imgUrl = myChart.toBase64Image();
            let img = document.createElement('img');
            img.src = imgUrl;

            let wrapper = document.getElementById(wrapperId);
            wrapper.innerHTML = '';
            wrapper.appendChild(img);
        }

        window.onload = function() {
            let l1 = {!! json_encode($l1) !!};
            let l2 = {!! json_encode($l2) !!};
            let l3 = {!! json_encode($l3) !!};

            drawChart('chart1', 'wrap-chart1', l1, false);
            drawChart('chart2', 'wrap-chart2', l2, false);
            drawChart('chart3', 'wrap-chart3', l3, true);
        };
    </script>
</body>

</html>
