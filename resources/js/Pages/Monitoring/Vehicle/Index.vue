<script setup>
import { router, useForm, Head, usePage } from '@inertiajs/vue3';
import { debounce } from 'lodash-es';
import { ref, watch, onMounted, onUnmounted } from 'vue';
import { useToast } from 'vue-toastification';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import TextInputUppercase from '@/Components/TextInputUppercase.vue';
import Pagination from '@/Components/Pagination.vue';
import ActionLink from '@/Components/ActionLink.vue';
import CheckButton from '@/Components/CheckButton.vue';
import SubmitButton from '@/Components/SubmitButton.vue';
import SelectedInput from '@/Components/SelectedInput.vue';
import ParkingDetailModal from '@/Components/ParkingDetailModal.vue';
import moment from 'moment-timezone';
import LicensePlat from '@/Components/LicensePlat.vue';

const props = defineProps({
    title: String,
    desc: String,
    transactions: Object,
    parking_areas: Object,
    parking_rates: Object,
    user: Number,
    filters: Object
});

const toast = useToast();
const search = ref(props.filters.search || '');
const form = useForm({
    search: props.filters.search || '',
});

const debouncedSearch = debounce(() => {
    router.get(route('monitoring_vehicle.index'), { search: form.search }, {
        preserveScroll: true,
        preserveState: true,
    });
}, 300);

watch(search, (value) => {
    form.search = value;
    debouncedSearch();
});

const timezoneAsia = ref(moment().tz('Asia/Jakarta').format('YYYY-MM-DD HH:mm:ss'));

const calculateDuration = (entryTime) => {
    const entry = moment(entryTime);
    const now = moment();
    const duration = moment.duration(now.diff(entry));

    const days = Math.floor(duration.asDays());
    const hours = duration.hours();
    const minutes = duration.minutes();
    const seconds = duration.seconds();

    return `${days}d ${hours}h ${minutes}m ${seconds}s`;
};

</script>

<template>

    <Head :title="title" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ desc }}</h2>
                <div class="text-right">
                    <p class="font-semibold text-gray-800 dark:text-gray-200">{{ currentDateTime }}</p>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="mb-4 flex items-center justify-between">
                            <TextInput v-model="search" type="text" placeholder="Search No.Plat/No.Tiket..."
                                class="w-full sm:w-64" />
                        </div>

                        <div class="overflow-x-auto">
                            <table v-if="transactions.data && transactions.data.length"
                                class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-slate-100 uppercase tracking-wider">
                                            #</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-slate-100 uppercase tracking-wider">
                                            No.Tiket</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-slate-100 uppercase tracking-wider">
                                            No.Plat</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-slate-100 uppercase tracking-wider">
                                            Waktu Parkir</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-slate-100 uppercase tracking-wider">
                                            Area Parkir</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-slate-100 uppercase tracking-wider">
                                            Tipe Kendaraan</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-slate-100 uppercase tracking-wider">
                                            Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-600">
                                    <tr v-for="(transaction, index) in transactions.data" :key="transaction.slug">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ index + 1 +
                                            (transactions.current_page - 1) *
                                            transactions.per_page }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ transaction.no_ticket }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ transaction.license_plate }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex">
                                                <div class="mr-4">
                                                    <div class="text-green-400 text-sm font-semibold">
                                                        {{ transaction.entry_time }}
                                                    </div>
                                                    <hr class="my-1 border-slate-600" />
                                                    <div class="text-red-400 text-sm font-semibold">
                                                        {{ timezoneAsia }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="text-slate-400 text-sm font-semibold">
                                                        Durasi Parkir
                                                    </div>
                                                    <div class="text-slate-200 text-sm font-semibold">
                                                        {{ calculateDuration(transaction.entry_time) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ transaction.parking_area.name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ transaction.parking_rate.vehicle.name
                                            }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full text-red-800 bg-red-100">
                                                OVERSTAY
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <p v-else class="text-gray-500 dark:text-gray-400">No Vehicle found</p>
                        </div>
                        <Pagination v-if="transactions && transactions.links" :links="transactions.links" :limit="10"
                            class="mt-6" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>