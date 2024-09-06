<script setup>

import { Head } from '@inertiajs/vue3';
import { formatRupiah } from '@/Helpers/functionRupiah';
import { ref, onMounted, onUnmounted } from 'vue';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import VehicleIcon from '@/Components/VehicleIcon.vue';
import ActionLink from '@/Components/ActionLink.vue';


const props = defineProps({
    title: String,
    desc: String,
    transaction: Object
});

// Time runner
const currentDateTime = ref('');
const formatDateTime = () => {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    currentDateTime.value = `${day}-${month}-${year} ${hours}:${minutes}:${seconds}`;
};

let timer;
onMounted(() => {
    formatDateTime();
    timer = setInterval(formatDateTime, 1000);
});
onUnmounted(() => {
    clearInterval(timer);
});
// End

</script>

<template>

    <Head :title="title" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ desc }}</h2>
                <p class="font-semibold text-gray-800 dark:text-gray-200">{{ currentDateTime }}</p>
                <div class="flex items-center space-x-4 text-right">
                    <ActionLink :href="route('transactions.index')" label="Back" color="secondary" />
                </div>
            </div>
        </template>

        <div class="pt-12 pb-0">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="dark:text-gray-200 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Card for Vehicle Info -->
                            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 flex flex-col items-center">
                                <div class="flex flex-col items-center">
                                    <VehicleIcon :vehicleType="transaction.parking_rate.vehicle.slug" />
                                </div>

                                <h3 class="text-xl font-medium text-slate-400">No. Tiket</h3>
                                <p class="text-5xl text-slate-200 font-bold">{{ transaction.no_ticket }}</p>
                                <h3 class="text-xl font-medium mt-4 text-slate-400">No. Plat</h3>
                                <p class="text-3xl text-slate-200 font-bold">{{ transaction.license_plate }}</p>
                            </div>

                            <!-- Card for Parking Details -->
                            <div class="bg-gray-100 dark:bg-gray-800 shadow-lg rounded-lg p-6 flex flex-col">
                                <h2 class="text-lg font-bold mb-4">Detail Parkir</h2>
                                <div class="check-details mt-4 flex-grow">
                                    <div class="mb-3 space-y-2">
                                        <div class="flex justify-between">
                                            <span>Kendaraan:</span>
                                            <span>{{ transaction.parking_rate.vehicle.name }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Area Parkir:</span>
                                            <span>{{ transaction.parking_area.name }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Entry Masuk:</span>
                                            <span>{{ transaction.entry_time }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Entry Keluar:</span>
                                            <span>{{ transaction.exit_time ?? '-' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Setting:</span>
                                            <span>@{{ transaction.parking_rate.time_interval }} min x {{
                                                formatRupiah(transaction.parking_rate.cost) }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Duration Parkir:</span>
                                            <span>{{ transaction.duration ?? '0' }} min</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Cost:</span>
                                            <span>{{ formatRupiah(transaction.total_cost ?? '0') }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Payment:</span>
                                            <span>{{ formatRupiah(transaction.payment ?? '0') }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Change:</span>
                                            <span>{{ formatRupiah(transaction.change_pay ?? '0') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center mt-4">
                                    <span>{{ transaction.user.name }}</span>
                                    <span :class="{
                                        'inline-block px-3 py-1 text-sm font-semibold rounded-full': true,
                                        'text-blue-800 bg-blue-100': transaction.status === 'ACTIVE',
                                        'text-green-800 bg-green-100': transaction.status === 'COMPLETE'
                                    }">
                                        {{ transaction.status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>