<script setup>

import { router, useForm, Head } from '@inertiajs/vue3';
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
    router.get(route('transactions.index'), { search: form.search }, {
        preserveScroll: true,
        preserveState: true,
    });
}, 300);

watch(search, (value) => {
    form.search = value;
    debouncedSearch();
});

// Proses Parkir Masuk
const formParkirMasuk = useForm({
    license_plate: '',
    parking_area_id: '',
    parking_rate_id: '',
    user_id: props.user,
    entry_time: new Date().toISOString().slice(0, 19).replace('T', ' '),
    status: 'ACTIVE'
});

const isProcessing = ref(false);

function handleSubmit() {
    isProcessing.value = true;

    router.post(route('transactions.store'), formParkirMasuk, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            toast.success('Transaksi berhasil disimpan!');
            formParkirMasuk.reset();
            isProcessing.value = false;
        },
        onError: (errors) => {
            console.error('Server errors:', errors);
            formParkirMasuk.errors = errors;
            toast.error('Oopss... Terjadi kesalahan. Silakan periksa form.');
            isProcessing.value = false;
        }
    });
}
// End


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

// Parkir Keluar Inputs
const parkirKeluar = ref({
    no_ticket: '',
    license_plate: ''
});

watch(() => parkirKeluar.value.no_ticket, (newValue) => {
    if (newValue) {
        parkirKeluar.value.license_plate = '';
    }
});

watch(() => parkirKeluar.value.license_plate, (newValue) => {
    if (newValue) {
        parkirKeluar.value.no_ticket = '';
    }
});


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

        <div class="pt-12 pb-0">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="dark:text-gray-200 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Form Parkir Masuk -->
                            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
                                <h2 class="text-lg font-bold mb-4">Parkir Masuk</h2>
                                <form @submit.prevent="handleSubmit">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="md:col-span-2 mb-4">
                                            <label class="block text-sm font-medium text-slate-100 mb-1">No.
                                                Plat</label>
                                            <TextInputUppercase v-model="formParkirMasuk.license_plate" type="text"
                                                placeholder="Masukkan No.Plat" class="w-full" />
                                        </div>
                                        <div class="mb-4">
                                            <label
                                                class="block text-sm font-medium text-slate-100 mb-1">Kendaraan</label>
                                            <SelectedInput id="parking_rate_id" label="Kendaraan"
                                                v-model="formParkirMasuk.parking_rate_id" :options="parking_rates"
                                                :error="formParkirMasuk.errors.parking_rate_id" />
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-slate-100 mb-1">Area
                                                Parkir</label>
                                            <SelectedInput id="parking_area_id" label="Area Parkir"
                                                v-model="formParkirMasuk.parking_area_id" :options="parking_areas"
                                                :error="formParkirMasuk.errors.parking_area_id" />
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <SubmitButton :processing="isProcessing" :disabled="!formParkirMasuk.isDirty"
                                            @click="handleSubmit" label="Submit" />
                                    </div>
                                </form>
                            </div>

                            <!-- Parkir Keluar -->
                            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
                                <h2 class="text-lg font-bold mb-4">Parkir Keluar</h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-slate-100 mb-1">NO. TIKET</label>
                                        <TextInputUppercase v-model="parkirKeluar.no_ticket" type="text"
                                            placeholder="Masukkan No.Tiket" class="w-full sm:w-64" />
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-slate-100 mb-1">NO. PLAT</label>
                                        <TextInputUppercase v-model="parkirKeluar.license_plate" type="text"
                                            placeholder="Masukkan No.Plat" class="w-full sm:w-64" />
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <CheckButton :processing="isProcessing"
                                        :disabled="!parkirKeluar.no_ticket && !parkirKeluar.license_plate"
                                        label="Check Parkir" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h2 class="text-lg font-bold mb-4">History Data Parkir</h2>

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
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-slate-100 uppercase tracking-wider">
                                            Actions</th>
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
                                                        {{ transaction.exit_time ?? '-' }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="text-slate-400 text-sm font-semibold">
                                                        Durasi
                                                    </div>
                                                    <div class="text-slate-200 text-sm font-semibold">
                                                        {{ transaction.duration ?? '-' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ transaction.parking_area.name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ transaction.parking_rate.vehicle.name
                                            }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="{
                                                'inline-block px-3 py-1 text-sm font-semibold rounded-full': true,
                                                'text-blue-800 bg-blue-100': transaction.status === 'ACTIVE',
                                                'text-green-800 bg-green-100': transaction.status === 'COMPLETE'
                                            }">
                                                {{ transaction.status }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap space-x-2">
                                            <ActionLink :href="route('transactions.show', transaction.slug)"
                                                color="primary" label="show" />
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