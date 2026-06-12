<?php $this->load->view('admin/layout/header'); ?>

<!-- Dashboard Title & Summary Cards -->
<div class="d-flex align-items-center mb-4">
    <span class="material-symbols-outlined text-primary-custom me-2" style="font-size: 32px;">dashboard</span>
    <h3 class="fw-bold mb-0 text-slate-800">Dashboard Utama</h3>
</div>

<div class="row g-4 mb-5">
    <!-- Total Tiket -->
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="glass-card p-4 d-flex align-items-center gap-3 h-100" style="border-left: 4px solid var(--primary);">
            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 50px;">
                <span class="material-symbols-outlined" style="font-size: 24px;">confirmation_number</span>
            </div>
            <div>
                <div class="text-slate-500 fw-semibold text-uppercase" style="font-size: 12px; letter-spacing: 0.5px;">Total Tiket</div>
                <div class="fw-bold text-slate-800" style="font-size: 24px; line-height: 1;"><?= $stats['total'] ?></div>
            </div>
        </div>
    </div>
    
    <!-- Menunggu Persetujuan -->
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="glass-card p-4 d-flex align-items-center gap-3 h-100" style="border-left: 4px solid var(--orange-500);">
            <div class="rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 50px;">
                <span class="material-symbols-outlined" style="font-size: 24px;">pending_actions</span>
            </div>
            <div>
                <div class="text-slate-500 fw-semibold text-uppercase" style="font-size: 12px; letter-spacing: 0.5px;">Menunggu Persetujuan</div>
                <div class="fw-bold text-slate-800" style="font-size: 24px; line-height: 1;"><?= $stats['pending_approval'] + $stats['approved'] ?></div>
            </div>
        </div>
    </div>
    
    <!-- Sedang Dikerjakan -->
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="glass-card p-4 d-flex align-items-center gap-3 h-100" style="border-left: 4px solid var(--blue-500);">
            <div class="rounded-circle bg-info text-dark d-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 50px;">
                <span class="material-symbols-outlined" style="font-size: 24px;">build</span>
            </div>
            <div>
                <div class="text-slate-500 fw-semibold text-uppercase" style="font-size: 12px; letter-spacing: 0.5px;">Sedang Dikerjakan</div>
                <div class="fw-bold text-slate-800" style="font-size: 24px; line-height: 1;"><?= $stats['in_progress'] ?></div>
            </div>
        </div>
    </div>
    
    <!-- Selesai -->
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="glass-card p-4 d-flex align-items-center gap-3 h-100" style="border-left: 4px solid var(--green-500);">
            <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 50px;">
                <span class="material-symbols-outlined" style="font-size: 24px;">task_alt</span>
            </div>
            <div>
                <div class="text-slate-500 fw-semibold text-uppercase" style="font-size: 12px; letter-spacing: 0.5px;">Selesai</div>
                <div class="fw-bold text-slate-800" style="font-size: 24px; line-height: 1;"><?= $stats['resolved'] ?></div>
            </div>
        </div>
    </div>
</div>

<!-- Analytic Charts -->
<div class="row g-4 mb-5">
    <!-- Ticket Status Chart -->
    <div class="col-12 col-lg-5">
        <div class="glass-card p-4 h-100 shadow-sm">
            <div class="d-flex align-items-center gap-2 mb-4 pb-2 border-bottom" style="border-color: rgba(255,255,255,0.7) !important;">
                <span class="material-symbols-outlined text-primary">donut_large</span>
                <h5 class="mb-0 fw-bold text-slate-800">Analitik Status Tiket</h5>
            </div>
            <div style="position: relative; height:300px; width:100%">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Department Chart -->
    <div class="col-12 col-lg-7">
        <div class="glass-card p-4 h-100 shadow-sm">
            <div class="d-flex align-items-center gap-2 mb-4 pb-2 border-bottom" style="border-color: rgba(255,255,255,0.7) !important;">
                <span class="material-symbols-outlined text-primary">bar_chart</span>
                <h5 class="mb-0 fw-bold text-slate-800">Analitik Tiket per Departemen</h5>
            </div>
            <div style="position: relative; height:300px; width:100%">
                <canvas id="deptChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/js/chart.min.js') ?>"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Data Status Tiket
        const statusData = {
            labels: ['Menunggu Persetujuan', 'Sedang Dikerjakan', 'Selesai', 'Ditolak/Batal'],
            datasets: [{
                data: [
                    <?= (isset($stats['pending_approval']) ? $stats['pending_approval'] : 0) + (isset($stats['approved']) ? $stats['approved'] : 0) + (isset($stats['pending']) ? $stats['pending'] : 0) + (isset($stats['pending_it']) ? $stats['pending_it'] : 0) ?>,
                    <?= isset($stats['in_progress']) ? $stats['in_progress'] : 0 ?>,
                    <?= isset($stats['resolved']) ? $stats['resolved'] : 0 ?>,
                    <?= (isset($stats['rejected']) ? $stats['rejected'] : 0) + (isset($stats['canceled']) ? $stats['canceled'] : 0) ?>
                ],
                backgroundColor: [
                    '#f59e0b', // warning
                    '#0dcaf0', // info
                    '#10b981', // success
                    '#ef4444'  // danger
                ],
                borderWidth: 0,
                hoverOffset: 4
            }]
        };

        const ctxStatus = document.getElementById('statusChart').getContext('2d');
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: statusData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: { family: 'Inter, sans-serif', size: 13 },
                            padding: 20
                        }
                    }
                }
            }
        });

        // Data Tiket per Departemen
        <?php 
            $deptLabels = [];
            $deptCounts = [];
            if(!empty($dept_stats)) {
                foreach($dept_stats as $dept) {
                    $deptLabels[] = $dept->dept_name ?: 'Unknown Dept';
                    $deptCounts[] = $dept->count;
                }
            }
        ?>
        const deptData = {
            labels: <?= json_encode($deptLabels) ?>,
            datasets: [{
                label: 'Jumlah Tiket',
                data: <?= json_encode($deptCounts) ?>,
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                borderColor: '#3b82f6',
                borderWidth: 1,
                borderRadius: 4
            }]
        };

        const ctxDept = document.getElementById('deptChart').getContext('2d');
        new Chart(ctxDept, {
            type: 'bar',
            data: deptData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: { family: 'Inter, sans-serif' }
                        },
                        grid: { borderDash: [4, 4] }
                    },
                    x: {
                        ticks: { font: { family: 'Inter, sans-serif' } },
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    });
</script>

<?php $this->load->view('admin/layout/footer'); ?>
