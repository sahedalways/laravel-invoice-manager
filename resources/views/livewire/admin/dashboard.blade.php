@section('title', siteSetting()->site_title)

<div class="mt-5">

    <div class="row" wire:poll.60s>

        <div class="col-12">
            <div class="row g-4 mb-4">
                <x-dashboard-card title="Today’s Sales" :value="'$' . number_format($todaySales, 2)" icon="fa-dollar-sign" />
                <x-dashboard-card title="Total Sales" :value="'$' . number_format($totalSales, 2)" icon="fa-coins" />
                <x-dashboard-card title="Total Products" :value="$totalProducts" icon="fa-box" />
                <x-dashboard-card title="Total Customers" :value="$totalCustomers" icon="fa-users" />
                <x-dashboard-card title="Completed Orders" :value="$completedOrders" icon="fa-check-circle" />
                <x-dashboard-card title="Returned Orders" :value="$returnedOrders" icon="fa-undo" />
                <x-dashboard-card title="Pending Orders" :value="$pendingOrders" icon="fa-clock" />
                <x-dashboard-card title="Processing Orders" :value="$processingOrders" icon="fa-spinner" />
            </div>
        </div>

        <!-- Chart Below Cards -->
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="fw-500 mb-0">Sales Overview</h5>
                </div>
                <div class="card-body" wire:ignore>
                    <canvas id="orders-chart" height="300px"></canvas>
                </div>
            </div>

            <!-- Status Badges -->
            <div class="d-flex justify-content-between mt-2">
                <span class="badge badge-md badge-dot ms-2"><i class="bg-secondary"></i> Pending</span>
                <span class="badge badge-md badge-dot"><i class="bg-warning"></i> Processing</span>
                <span class="badge badge-md badge-dot"><i class="bg-success"></i> Completed</span>
                <span class="badge badge-md badge-dot me-2"><i class="bg-danger"></i> Returned</span>
            </div>
        </div>
    </div>

    <input type="hidden" id="chartdata" value="{{ json_encode($ordersChartData) }}">

</div>

@push('js')
    <script>
        "use strict";

        const ctx = document.getElementById("orders-chart").getContext("2d");
        const chartData = JSON.parse(document.getElementById("chartdata").value);

        new Chart(ctx, {
            type: "doughnut",
            data: {
                labels: ['Pending', 'Processing', 'Completed', 'Returned'],
                datasets: [{
                    label: "Orders",
                    data: chartData,
                    backgroundColor: ['#8392ab', '#faae42', '#2dce89', '#f5365c'],
                    borderWidth: 2,
                    cutout: 60,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                interaction: {
                    intersect: true,
                    mode: 'index'
                },
            },
        });
    </script>
@endpush
