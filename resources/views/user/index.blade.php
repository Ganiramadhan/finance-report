@extends('layouts.admin.appUser', ['title' => 'Dashboard'])

@section('content')

    <div class="row justify-content-center align-items-center mt-4">
        @if ($metode_pembayaran->count() > 0)
            @foreach ($metode_pembayaran as $row)
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center d-flex align-items-center">
                            <i class="fas fa-credit-card fa-4x mr-3"></i> <!-- Adjust the margin-right for spacing -->
                            <div>
                                <h1 class="text-dark font-weight-bold" style="font-size: 24px;">{{ $row->metode_pembayaran }}
                                </h1>
                                <hr />
                                <h6 class="text-muted saldo-animation" data-saldo="{{ $row->saldo }}">Rp.
                                    {{ number_format($row->saldo, 0, ',', '.') }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12 text-center">
                <p class="text-muted">Saldo not found</p>
            </div>
        @endif
    </div>

    <div class="row mt-4 justify-content-center align-items-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title text-center">10 Daftar Produk Terlaris</h4>
                </div>
                <div class="card-body">
                    <canvas id="chartpenjualan"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title text-center">Pengeluaran</h4>
                </div>
                <div class="card-body">
                    <canvas id="chart_pengeluaran"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-10 justify-content-center align-items-center mt-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title text-center">Laba Per Produk</h4>
                </div>
                <div class="card-body">
                    <canvas id="labaKotorChart"></canvas>
                </div>
            </div>
        </div>

    </div>

    <div class="row justify-content-center mt-4">
        <div class="col-md-2">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title text-center">Piutang</h4>
                </div>
                <div class="card-body">
                    <hr />
                    <p>Rp. {{ number_format($sisa_piutang, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title text-center">Utang</h4>
                </div>
                <div class="card-body">
                    <hr />
                    <p>Rp. {{ number_format($sisa_utang, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title text-center">Penjualan</h4>
                </div>
                <div class="card-body">
                    <hr />
                    <p>Rp. {{ number_format($total_penjualan, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title text-center">Pembelian</h4>
                </div>
                <div class="card-body">
                    <hr />
                    <p>Rp. {{ number_format($total_pembelian, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title text-center">Laba Kotor</h4>
                </div>
                <div class="card-body">
                    <hr />
                    <p>Rp. {{ number_format($total_laba_kotor, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title text-center">Laba Bersih</h4>
                </div>
                <div class="card-body">
                    <hr>
                    <p>Rp. {{ number_format($laba_bersih, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function animateNumbers(targetElements, duration) {
            targetElements.forEach(function(element) {
                var startValue = 0;
                var targetValue = parseFloat(element.getAttribute("data-target"));
                var interval = 10; // Interval waktu dalam milidetik
                var steps = duration / interval;
                var increment = (targetValue - startValue) / steps;
                var currentStep = 0;

                function updateNumber() {
                    startValue += increment;
                    element.textContent = "Rp. " + startValue.toFixed(0).replace(/\d(?=(\d{3})+$)/g, "$&,");
                    currentStep++;

                    if (currentStep < steps) {
                        requestAnimationFrame(updateNumber);
                    } else {
                        element.textContent = "Rp. " + targetValue.toFixed(0).replace(/\d(?=(\d{3})+$)/g, "$&,");
                    }
                }

                requestAnimationFrame(updateNumber);
            });
        }

        // Ambil elemen-elemen yang ingin di-animate
        var elementsToAnimate = document.querySelectorAll(".animate-number");

        // Panggil fungsi animateNumbers dengan elemen-elemen yang ingin di-animate dan durasi animasi (misalnya, 1000 ms untuk 1 detik)
        animateNumbers(elementsToAnimate, 1000);
        document.addEventListener('DOMContentLoaded', function() {
            const saldoElements = document.querySelectorAll('.saldo-animation');
            saldoElements.forEach(function(element) {
                const saldo = parseFloat(element.dataset.saldo);
                let currentSaldo = 0;
                const step = saldo /
                    100; // Ubah angka 50 sesuai dengan kecepatan bertambahnya saldo (semakin besar, semakin cepat)

                function updateSaldo() {
                    if (currentSaldo < saldo) {
                        currentSaldo += step;
                        element.innerText = 'Rp. ' + currentSaldo.toFixed(0).replace(/\d(?=(\d{3})+$)/g,
                            '$&,');
                        requestAnimationFrame(updateSaldo);
                    } else {
                        element.innerText = 'Rp. ' + saldo.toFixed(0).replace(/\d(?=(\d{3})+$)/g, '$&,');
                    }
                }

                updateSaldo();
            });
        });



        //CHART PENJUALAN
        var ctx = document.getElementById('chartpenjualan').getContext('2d');

        // Extract data nama produk dan total qty ke dalam dua array terpisah
        var productNames = {!! json_encode($penjualanData->pluck('nama')->toArray()) !!};
        var totalQty = {!! json_encode($penjualanData->pluck('total_qty')->toArray()) !!};

        var chartpenjualan = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: productNames,
                datasets: [{
                        label: 'Qty',
                        data: totalQty,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },

                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>



    <script>
        //CHART PENGELUARAN
        // Data dari controller
        var chartData = {!! json_encode($chart_pengeluaran) !!};

        // Ekstraksi data untuk chart
        var labels = chartData.map(function(item) {
            return item.keterangan;
        });
        var values = chartData.map(function(item) {
            return item.total_pembayaran;
        });

        // Menggambar chart menggunakan Chart.js
        var ctx = document.getElementById('chart_pengeluaran').getContext('2d');
        var chart_pengeluaran = new Chart(ctx, {
            type: 'bar', // Jenis chart (bisa diganti dengan line, pie, dll.)
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Pengeluaran',
                    data: values,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)', // Warna area chart
                    borderColor: 'rgba(75, 192, 192, 1)', // Warna garis chart
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    <script>
        // Data dari controller, $laba_kotor_per_product harus disediakan oleh controller
        var labaKotorData = {!! json_encode($laba_kotor_per_product) !!};

        // Ambil label (nama_produk) dan data (laba_kotor) dari objek JavaScript
        var labels = labaKotorData.map(function(item) {
            return item.nama_produk;
        });
        var data = labaKotorData.map(function(item) {
            return item.laba_kotor;
        });

        // Konteks untuk menggambar chart di canvas
        var ctx = document.getElementById('labaKotorChart').getContext('2d');

        // Buat chart menggunakan Chart.js
        var labaKotorChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Laba per Produk',
                    data: data,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)', // Warna latar belakang bar
                    borderColor: 'rgba(75, 192, 192, 1)', // Warna border bar
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
