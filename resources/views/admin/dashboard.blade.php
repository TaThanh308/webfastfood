@extends('layouts.admin')

@section('content')
<div class="container">
    <h3 class="text-center">📊 Báo cáo doanh thu theo tháng</h3>
    
    <div class="form-group">
        <label for="year">Chọn năm:</label>
        <select id="year" class="form-control w-25">
            @for ($i = 2020; $i <= now()->year; $i++)
                <option value="{{ $i }}" {{ now()->year == $i ? 'selected' : '' }}>{{ $i }}</option>
            @endfor
        </select>
    </div>

    <div class="chart-container">
        <canvas id="revenueChart"></canvas>
    </div>

    <h3 class="text-center mt-5">📈 Sản phẩm được mua nhiều nhất</h3>
    <div class="chart-container">
        <canvas id="topSellingChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let ctxRevenue = document.getElementById("revenueChart").getContext("2d");
        let ctxTopSelling = document.getElementById("topSellingChart").getContext("2d");

        let revenueChart, topSellingChart;

        function fetchRevenueData(year) {
            fetch(`/admin/revenue-data?year=${year}`)
                .then(response => response.json())
                .then(data => {
                    let months = Array.from({length: 12}, (_, i) => i + 1);
                    
                    let revenue = months.map(m => {
                        let record = data.revenueData.find(item => item.month == m);
                        return record ? record.revenue : 0;
                    });

                    let topSelling = months.map(m => {
                        let record = data.topSellingProducts.find(item => item.month == m);
                        return record ? record.total_sold : 0;
                    });

                    let productLabels = months.map(m => {
                        let record = data.topSellingProducts.find(item => item.month == m);
                        return record ? record.product_name : "Không có";
                    });

                    if (revenueChart) revenueChart.destroy();
                    revenueChart = new Chart(ctxRevenue, {
                        type: "bar",
                        data: {
                            labels: months.map(m => `Tháng ${m}`),
                            datasets: [{
                                label: "Doanh thu (VND)",
                                data: revenue,
                                backgroundColor: "rgba(54, 162, 235, 0.5)",
                                borderColor: "rgba(54, 162, 235, 1)",
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: { y: { beginAtZero: true } }
                        }
                    });

                    if (topSellingChart) topSellingChart.destroy();
                    topSellingChart = new Chart(ctxTopSelling, {
                        type: "line",
                        data: {
                            labels: months.map((m, index) => `Tháng ${m} (${productLabels[index]})`),
                            datasets: [{
                                label: "Số lượng sản phẩm bán chạy nhất",
                                data: topSelling,
                                backgroundColor: "rgba(255, 99, 132, 0.5)",
                                borderColor: "rgba(255, 99, 132, 1)",
                                borderWidth: 2,
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: { y: { beginAtZero: true } }
                        }
                    });
                });
        }

        document.getElementById("year").addEventListener("change", function () {
            fetchRevenueData(this.value);
        });

        fetchRevenueData(document.getElementById("year").value);
    });
</script>

<style>
    .chart-container {
        width: 80%;
        max-width: 600px;
        height: 400px;
        margin: 20px auto;
    }
    select {
        margin-bottom: 20px;
    }
</style>
@endsection