 <!-- Grafik Penjualan dan Pembelian per Hari (Harian) -->
 <div class="row mt-4">
     <div class="col-md-6">
         <div class="card">
             <div class="card-header">
                 <h3>Grafik Penjualan Harian</h3>
             </div>
             <div class="card-body">
                 <canvas id="penjualanHarianChart"></canvas>
             </div>
         </div>
     </div>
     <div class="col-md-6">
         <div class="card">
             <div class="card-header">
                 <h3>Grafik Pembelian Harian</h3>
             </div>
             <div class="card-body">
                 <canvas id="pembelianHarianChart"></canvas>
             </div>
         </div>
     </div>
 </div>

 <!-- Grafik Penjualan dan Pembelian per Bulan -->
 <div class="row mt-4">
     <div class="col-md-6">
         <div class="card">
             <div class="card-header">
                 <h3>Grafik Penjualan per Hari (Bulan Ini)</h3>
             </div>
             <div class="card-body">
                 <canvas id="penjualanBulanChart"></canvas>
             </div>
         </div>
     </div>
     <div class="col-md-6">
         <div class="card">
             <div class="card-header">
                 <h3>Grafik Pembelian per Hari (Bulan Ini)</h3>
             </div>
             <div class="card-body">
                 <canvas id="pembelianBulanChart"></canvas>
             </div>
         </div>
     </div>
 </div>
 @push('scripts')
     <script>
         // Grafik Penjualan Harian
         var ctxPenjualanHarian = document.getElementById('penjualanHarianChart').getContext('2d');
         var penjualanHarianChart = new Chart(ctxPenjualanHarian, {
             type: 'bar',
             data: {
                 labels: ['Hari Ini'], // Misalnya, hanya hari ini
                 datasets: [{
                     label: 'Total Penjualan',
                     data: [{{ $totalPenjualanHarian }}], // Data dari controller
                     backgroundColor: 'rgba(54, 162, 235, 0.2)',
                     borderColor: 'rgba(54, 162, 235, 1)',
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

         // Grafik Pembelian Harian
         var ctxPembelianHarian = document.getElementById('pembelianHarianChart').getContext('2d');
         var pembelianHarianChart = new Chart(ctxPembelianHarian, {
             type: 'bar',
             data: {
                 labels: ['Hari Ini'], // Misalnya, hanya hari ini
                 datasets: [{
                     label: 'Total Pembelian',
                     data: [{{ $totalPembelianHarian }}], // Data dari controller
                     backgroundColor: 'rgba(255, 99, 132, 0.2)',
                     borderColor: 'rgba(255, 99, 132, 1)',
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

         // Grafik Penjualan Bulanan
         var ctxPenjualanBulan = document.getElementById('penjualanBulanChart').getContext('2d');
         var penjualanBulanChart = new Chart(ctxPenjualanBulan, {
             type: 'line',
             data: {
                 labels: @json($penjualanPerBulan->pluck('day')), // Hari-hari dalam bulan ini
                 datasets: [{
                     label: 'Penjualan Bulanan',
                     data: @json($penjualanPerBulan->pluck('total_penjualan')), // Data penjualan per hari
                     borderColor: 'rgba(75, 192, 192, 1)',
                     tension: 0.1
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

         // Grafik Pembelian Bulanan
         var ctxPembelianBulan = document.getElementById('pembelianBulanChart').getContext('2d');
         var pembelianBulanChart = new Chart(ctxPembelianBulan, {
             type: 'line',
             data: {
                 labels: @json($pembelianPerBulan->pluck('day')), // Hari-hari dalam bulan ini
                 datasets: [{
                     label: 'Pembelian Bulanan',
                     data: @json($pembelianPerBulan->pluck('total_pembelian')), // Data pembelian per hari
                     borderColor: 'rgba(153, 102, 255, 1)',
                     tension: 0.1
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
 @endpush
