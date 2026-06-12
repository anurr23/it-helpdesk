<?php $this->load->view('ticket/layout/header'); ?>

<div class="glass-card-container w-100 d-flex justify-content-center pb-5">
    <div class="glass-card w-100 p-4 p-md-5 d-flex flex-column" style="max-width: 1000px; position: relative; overflow: hidden;">
        
        <!-- Background decoration -->
        <div class="position-absolute rounded-circle" style="width: 300px; height: 300px; background: radial-gradient(circle, rgba(13,110,253,0.1) 0%, rgba(13,110,253,0) 70%); top: -100px; right: -100px; pointer-events: none;"></div>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4 gap-3 position-relative z-1">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="material-symbols-outlined text-primary-custom" style="font-size: 32px; font-variation-settings: 'FILL' 1;">monitoring</span>
                    <h1 class="h3 fw-bold text-slate-800 mb-0">Monitoring Departemen</h1>
                </div>
                <p class="text-slate-500 mb-0">Pantau statistik pengajuan tiket bantuan IT dari seluruh tim di departemen Anda.</p>
            </div>
            
            <!-- Year Filter -->
            <div class="bg-white p-2 rounded-3 shadow-sm border" style="border-color: rgba(255,255,255,0.8) !important; min-width: 200px;">
                <form action="<?= base_url('monitoring-tiket') ?>" method="GET" class="d-flex align-items-center gap-2 m-0" id="filterForm">
                    <span class="material-symbols-outlined text-slate-400 ms-2" style="font-size: 20px;">calendar_month</span>
                    <select name="year" class="form-select border-0 shadow-none text-slate-700 fw-semibold" onchange="document.getElementById('filterForm').submit()" style="background: transparent; cursor: pointer;">
                        <option value="all" <?= $selected_year === 'all' ? 'selected' : '' ?>>Semua Waktu</option>
                        <?php foreach($available_years as $year): ?>
                            <option value="<?= $year ?>" <?= $selected_year == $year ? 'selected' : '' ?>>Tahun <?= $year ?></option>
                        <?php endforeach; ?>
                        <?php if(empty($available_years) && $selected_year !== 'all'): ?>
                            <option value="<?= $selected_year ?>" selected>Tahun <?= $selected_year ?></option>
                        <?php endif; ?>
                    </select>
                </form>
            </div>
        </div>

        <hr style="border-color: rgba(0,0,0,0.1); margin-bottom: 2rem;">

        <!-- Analytics Charts -->
        <div class="row g-4 mb-5">
            <!-- Monthly Trends Chart -->
            <div class="col-lg-8">
                <div class="bg-white p-4 rounded-4 shadow-sm border h-100 position-relative" style="border-color: rgba(255,255,255,0.8) !important;">
                    <div class="d-flex align-items-center gap-2 mb-4">
                        <span class="material-symbols-outlined text-primary" style="font-size: 24px;">trending_up</span>
                        <h5 class="fw-bold text-slate-800 mb-0">Tren Tiket Bulanan</h5>
                    </div>
                    <div style="height: 300px; width: 100%;">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Top Users Chart -->
            <div class="col-lg-4">
                <div class="bg-white p-4 rounded-4 shadow-sm border h-100 position-relative" style="border-color: rgba(255,255,255,0.8) !important;">
                    <div class="d-flex align-items-center gap-2 mb-4">
                        <span class="material-symbols-outlined text-info" style="font-size: 24px;">groups</span>
                        <h5 class="fw-bold text-slate-800 mb-0">Top Pengguna</h5>
                    </div>
                    <?php if(empty($top_users)): ?>
                        <div class="d-flex flex-column align-items-center justify-content-center h-75 text-slate-400">
                            <span class="material-symbols-outlined mb-2" style="font-size: 48px;">sentiment_dissatisfied</span>
                            <p class="mb-0">Belum ada data</p>
                        </div>
                    <?php else: ?>
                        <div style="height: 300px; width: 100%;">
                            <canvas id="topUsersChart"></canvas>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Detail Table -->
        <div class="bg-white rounded-4 shadow-sm border overflow-hidden" style="border-color: rgba(255,255,255,0.8) !important;">
            <div class="px-4 py-3 border-bottom bg-light bg-opacity-50 d-flex align-items-center gap-2">
                <span class="material-symbols-outlined text-slate-500">list_alt</span>
                <h6 class="fw-bold text-slate-800 mb-0">Daftar Tiket Departemen</h6>
            </div>
            
            <div class="p-4 table-responsive">
                <table id="monitoringTable" class="table datatable-glass align-middle w-100 text-nowrap" style="width: 100%;">
                    <thead>
                        <tr>
                            <th class="text-slate-500 fw-semibold text-uppercase px-3 py-3" style="font-size: 12px; letter-spacing: 0.5px;">Waktu & ID</th>
                            <th class="text-slate-500 fw-semibold text-uppercase px-3 py-3" style="font-size: 12px; letter-spacing: 0.5px;">Pemohon</th>
                            <th class="text-slate-500 fw-semibold text-uppercase px-3 py-3" style="font-size: 12px; letter-spacing: 0.5px;">Permintaan</th>
                            <th class="text-slate-500 fw-semibold text-uppercase px-3 py-3" style="font-size: 12px; letter-spacing: 0.5px;">Status</th>
                            <th class="text-slate-500 fw-semibold text-uppercase px-3 py-3 text-center" style="font-size: 12px; letter-spacing: 0.5px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($tickets)): ?>
                            <?php foreach($tickets as $ticket): ?>
                            <tr>
                                <td class="px-3 py-3">
                                    <div class="fw-semibold text-slate-900" style="font-size: 14px;"><?= date('d M Y, H:i', strtotime($ticket->created_at)) ?></div>
                                    <div class="text-slate-500 font-monospace" style="font-size: 12px;">#IT-<?= strtoupper(substr($ticket->id, 0, 6)) ?></div>
                                </td>
                                <td class="px-3 py-3">
                                    <div class="fw-semibold text-slate-900" style="font-size: 14px;"><?= htmlspecialchars($ticket->user_name) ?></div>
                                </td>
                                <td class="px-3 py-3" style="max-width: 250px;">
                                    <div class="text-truncate text-slate-900 fw-bold mb-1" style="font-size: 14px;">
                                        <?= htmlspecialchars($ticket->title) ?>
                                    </div>
                                    <p class="mb-0 text-slate-600 text-truncate" title="<?= htmlspecialchars($ticket->description) ?>" style="font-size: 13px;">
                                        <?= htmlspecialchars($ticket->description) ?>
                                    </p>
                                </td>
                                <td class="px-3 py-3">
                                    <?php 
                                        $badge_class = '';
                                        $status_text = '';
                                        $status_icon = '';
                                        
                                        switch($ticket->status) {
                                            case 'pending': 
                                                $badge_class = 'bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25'; 
                                                $status_text = 'Menunggu Atasan';
                                                $status_icon = 'schedule';
                                                break;
                                            case 'pending_it': 
                                                $badge_class = 'bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25'; 
                                                $status_text = 'Menunggu IT';
                                                $status_icon = 'hourglass_top';
                                                break;
                                            case 'in_progress': 
                                                $badge_class = 'bg-info bg-opacity-10 text-info border border-info border-opacity-25'; 
                                                $status_text = 'Sedang Ditangani';
                                                $status_icon = 'build';
                                                break;
                                            case 'resolved': 
                                                $badge_class = 'bg-success bg-opacity-10 text-success border border-success border-opacity-25'; 
                                                $status_text = 'Selesai';
                                                $status_icon = 'check_circle';
                                                break;
                                            case 'rejected': 
                                                $badge_class = 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25'; 
                                                $status_text = 'Ditolak';
                                                $status_icon = 'cancel';
                                                break;
                                        }
                                    ?>
                                    <span class="badge rounded-pill <?= $badge_class ?> d-inline-flex align-items-center gap-1 px-3 py-2 shadow-sm" style="font-weight: 600; font-size: 12px;">
                                        <span class="material-symbols-outlined" style="font-size: 14px;"><?= $status_icon ?></span>
                                        <?= $status_text ?>
                                    </span>
                                </td>
                                <td class="px-3 py-3 text-center">
                                    <a href="<?= base_url('detail-tiket/' . $ticket->id) ?>" class="btn btn-sm btn-primary-custom d-inline-flex align-items-center justify-content-center gap-1 w-100" style="font-size: 12px; border-radius: 6px;">
                                        <span class="material-symbols-outlined" style="font-size: 16px;">visibility</span> Detail
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- Chart.js -->
<script src="<?= base_url('assets/js/chart.min.js') ?>"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize DataTable
        if ($.fn.DataTable) {
            $('#monitoringTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
                },
                "pageLength": 10,
                "ordering": false,
                "responsive": false,
                "dom": '<"d-flex flex-column flex-md-row justify-content-between align-items-center mb-3 gap-3"f>rt<"d-flex flex-column flex-md-row justify-content-between align-items-center mt-3 gap-3"ip>'
            });
        }

        // Setup Charts
        // Custom Tooltip Styling
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.color = '#64748b'; // slate-500
        
        const tooltipOptions = {
            backgroundColor: 'rgba(255, 255, 255, 0.95)',
            titleColor: '#1e293b', // slate-800
            bodyColor: '#475569', // slate-600
            borderColor: 'rgba(226, 232, 240, 1)', // slate-200
            borderWidth: 1,
            padding: 12,
            boxPadding: 6,
            usePointStyle: true,
            titleFont: { size: 14, weight: 'bold', family: "'Inter', sans-serif" },
            bodyFont: { size: 13, family: "'Inter', sans-serif" },
            boxWidth: 8,
            boxHeight: 8,
            cornerRadius: 8,
            displayColors: true,
            callbacks: {
                labelColor: function(context) {
                    return {
                        borderColor: context.dataset.borderColor || context.dataset.backgroundColor,
                        backgroundColor: context.dataset.borderColor || context.dataset.backgroundColor
                    };
                }
            }
        };

        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: { display: false },
                tooltip: tooltipOptions
            }
        };

        // Monthly Trends Chart Data
        <?php
            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
            $monthly_data = [];
            for ($i = 1; $i <= 12; $i++) {
                $monthly_data[] = isset($monthly_stats[$i]) ? $monthly_stats[$i] : 0;
            }
        ?>
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        
        // Premium Gradient for Area
        const gradientBlue = monthlyCtx.createLinearGradient(0, 0, 0, 300);
        gradientBlue.addColorStop(0, 'rgba(0, 61, 155, 0.5)'); // primary blue, more opaque at top
        gradientBlue.addColorStop(1, 'rgba(0, 61, 155, 0.0)'); // transparent at bottom

        // Custom plugin for drop shadow on line
        const shadowPlugin = {
            id: 'shadowPlugin',
            beforeDraw: (chart) => {
                if (chart.config.type !== 'line') return;
                const ctx = chart.ctx;
                ctx.save();
                ctx.shadowColor = 'rgba(0, 61, 155, 0.3)';
                ctx.shadowBlur = 15;
                ctx.shadowOffsetX = 0;
                ctx.shadowOffsetY = 8;
            },
            afterDraw: (chart) => {
                chart.ctx.restore();
            }
        };

        new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: <?= json_encode($months) ?>,
                datasets: [{
                    label: 'Jumlah Tiket',
                    data: <?= json_encode($monthly_data) ?>,
                    borderColor: '#003d9b',
                    backgroundColor: gradientBlue,
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#003d9b',
                    pointBorderWidth: 3,
                    pointRadius: 4,
                    pointHoverRadius: 7,
                    pointHoverBackgroundColor: '#003d9b',
                    pointHoverBorderColor: '#ffffff',
                    pointHoverBorderWidth: 3,
                    fill: true,
                    tension: 0.45 // Even smoother curve
                }]
            },
            plugins: [shadowPlugin],
            options: {
                ...commonOptions,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { 
                            stepSize: 1, 
                            padding: 10,
                            font: { size: 12 }
                        },
                        grid: { 
                            color: 'rgba(226, 232, 240, 0.8)', // very subtle line
                            borderDash: [5, 5], 
                            drawBorder: false,
                            tickLength: 0
                        },
                        border: { display: false }
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { 
                            padding: 10,
                            font: { size: 12 }
                        },
                        border: { display: false }
                    }
                }
            }
        });

        // Top Users Chart Data
        <?php if(!empty($top_users)): ?>
            <?php
                $user_labels = [];
                $user_data = [];
                foreach($top_users as $u) {
                    $name = $u->name;
                    $user_labels[] = strlen($name) > 15 ? substr($name, 0, 12) . '...' : $name;
                    $user_data[] = (int)$u->count;
                }
            ?>
            const topUsersCtx = document.getElementById('topUsersChart').getContext('2d');
            
            // Premium gradients for bars
            const barGradients = [
                ['rgba(0, 61, 155, 0.9)', 'rgba(0, 61, 155, 0.6)'], // Theme Blue
                ['rgba(20, 184, 166, 0.9)', 'rgba(20, 184, 166, 0.6)'], // Teal
                ['rgba(139, 92, 246, 0.9)', 'rgba(139, 92, 246, 0.6)'], // Purple
                ['rgba(245, 158, 11, 0.9)', 'rgba(245, 158, 11, 0.6)'], // Amber
                ['rgba(236, 72, 153, 0.9)', 'rgba(236, 72, 153, 0.6)']  // Pink
            ];
            
            const backgroundColors = barGradients.map(colors => {
                let gradient = topUsersCtx.createLinearGradient(0, 0, 0, 300);
                gradient.addColorStop(0, colors[0]);
                gradient.addColorStop(1, colors[1]);
                return gradient;
            });

            new Chart(topUsersCtx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($user_labels) ?>,
                    datasets: [{
                        label: 'Tiket Diajukan',
                        data: <?= json_encode($user_data) ?>,
                        backgroundColor: backgroundColors,
                        borderWidth: 0,
                        borderRadius: 8,
                        barPercentage: 0.6, // slimmer bars look more premium
                        hoverBackgroundColor: barGradients.map(colors => colors[0])
                    }]
                },
                options: {
                    ...commonOptions,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { 
                                stepSize: 1,
                                padding: 10
                            },
                            grid: { 
                                color: 'rgba(226, 232, 240, 0.8)',
                                borderDash: [5, 5], 
                                drawBorder: false,
                                tickLength: 0
                            },
                            border: { display: false }
                        },
                        x: {
                            grid: { display: false, drawBorder: false },
                            ticks: { padding: 10 },
                            border: { display: false }
                        }
                    }
                }
            });
        <?php endif; ?>
    });
</script>

<?php $this->load->view('ticket/layout/footer'); ?>
