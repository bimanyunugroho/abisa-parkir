<script setup>
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { Chart, registerables } from 'chart.js';

const { props } = usePage();
const { title, desc } = props;

Chart.register(...registerables);

// Data dummy
const parkingData = ref({
    totalSpots: 100,
    occupied: 75,
    available: 25,
    revenue: {
        today: 1500000,
        yesterday: 1350000,
    },
    averageDuration: 120, // dalam menit
    peakHour: '14:00 - 15:00',
    monthlySubscribers: 50,
});

const currentMonth = new Date().toLocaleString('id-ID', { month: 'long', year: 'numeric' });

onMounted(() => {
    const occupancyCtx = document.getElementById('occupancyChart');
    new Chart(occupancyCtx, {
        type: 'doughnut',
        data: {
            labels: ['Terisi', 'Tersedia'],
            datasets: [{
                data: [parkingData.value.occupied, parkingData.value.available],
                backgroundColor: ['#4F46E5', '#10B981'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            },
            cutout: '70%',
        }
    });

    const revenueCtx = document.getElementById('revenueChart');
    new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: ['Kemarin', 'Hari Ini'],
            datasets: [{
                label: 'Pendapatan',
                data: [parkingData.value.revenue.yesterday, parkingData.value.revenue.today],
                backgroundColor: ['#FFA500', '#4F46E5'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function (value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });
});
</script>

<template>

    <Head :title="title" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ desc }}</h2>
                <div class="text-right">
                    <p class="font-semibold text-gray-800 dark:text-gray-200">{{ currentMonth }}</p>
                </div>
            </div>
        </template>

        <div class="py-12 bg-gray-100 dark:bg-gray-900">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Occupancy Chart -->
                    <div
                        class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg transition duration-300 ease-in-out hover:shadow-xl">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Okupansi Parkir</h3>
                            <div class="h-64">
                                <canvas id="occupancyChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Total Spots Card -->
                    <div
                        class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg transition duration-300 ease-in-out hover:shadow-xl">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Total Spot Parkir
                            </h3>
                            <p class="text-4xl font-bold text-indigo-600 dark:text-indigo-400">{{ parkingData.totalSpots
                                }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Kapasitas total parkir</p>
                        </div>
                    </div>

                    <!-- Revenue Chart -->
                    <div
                        class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg transition duration-300 ease-in-out hover:shadow-xl">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Pendapatan</h3>
                            <div class="h-64">
                                <canvas id="revenueChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Average Duration Card -->
                    <div
                        class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg transition duration-300 ease-in-out hover:shadow-xl">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Durasi Rata-rata
                                Parkir</h3>
                            <p class="text-4xl font-bold text-orange-600 dark:text-orange-400">{{
                                parkingData.averageDuration }}
                                menit</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Rata-rata lama parkir</p>
                        </div>
                    </div>

                    <!-- Peak Hour Card -->
                    <div
                        class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg transition duration-300 ease-in-out hover:shadow-xl">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Jam Tersibuk</h3>
                            <p class="text-4xl font-bold text-purple-600 dark:text-purple-400">{{ parkingData.peakHour
                                }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Periode parkir tersibuk</p>
                        </div>
                    </div>

                    <!-- Monthly Subscribers Card -->
                    <div
                        class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg transition duration-300 ease-in-out hover:shadow-xl">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Pelanggan Bulanan
                            </h3>
                            <p class="text-4xl font-bold text-teal-600 dark:text-teal-400">{{
                                parkingData.monthlySubscribers }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Jumlah pelanggan berlangganan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>