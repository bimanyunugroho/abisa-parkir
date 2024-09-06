<script setup>
import { ref, computed, watch } from 'vue';
import { formatRupiah } from '@/Helpers/functionRupiah';
import { router, useForm } from '@inertiajs/vue3';
import VehicleIcon from './VehicleIcon.vue';
import moment from 'moment-timezone';
import { useToast } from 'vue-toastification';

const props = defineProps({
    isVisible: Boolean,
    transaction: Object,
});

const toast = useToast();
const emit = defineEmits(['close']);

const exitTime = computed(() => {
    return moment().tz('Asia/Jakarta').format('YYYY-MM-DD HH:mm:ss');
});

const duration = computed(() => {
    if (!props.transaction || !props.transaction.entry_time) {
        return 0;
    }
    const entryTime = moment(props.transaction.entry_time);
    const exit = moment(exitTime.value);
    const durationInMinutes = exit.diff(entryTime, 'minutes');
    return Math.max(0, durationInMinutes);
});

const totalCost = computed(() => {
    if (!props.transaction || !props.transaction.parking_rate) {
        return 0;
    }
    const intervalCost = props.transaction.parking_rate.cost || 0;
    const intervalMinutes = props.transaction.parking_rate.time_interval || 1;
    return Math.ceil(duration.value / intervalMinutes) * intervalCost;
});

const formattedPayment = ref('');

const form = useForm({
    payment: '',
    exit_time: exitTime.value,
    duration: duration.value,
    total_cost: totalCost.value,
    change_pay: 0,
    status: 'COMPLETE'
});

const changePay = computed(() => {
    const payment = parseFloat(form.payment.replace(/\./g, '')) || 0;
    return Math.max(0, payment - totalCost.value);
});

const isPaymentValid = computed(() => {
    const payment = parseFloat(form.payment.replace(/\./g, '')) || 0;
    return payment >= totalCost.value;
});

function formatNumber(value) {
    return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function updateFormattedPayment(value) {
    formattedPayment.value = formatNumber(value);
    form.payment = formattedPayment.value.replace(/\./g, '');
}

watch(formattedPayment, (newValue) => {
    updateFormattedPayment(newValue);
});

function close() {
    form.reset();
    emit('close');
    setTimeout(() => {
        location.reload();
    }, 500);
}

function updatePayment() {
    if (!props.transaction) {
        console.error('No transaction data available');
        return;
    }

    if (!isPaymentValid.value) {
        alert('Pembayaran harus lebih besar atau sama dengan total biaya parkir.');
        return;
    }

    form.change_pay = changePay.value;
    form.exit_time = exitTime.value;
    form.duration = duration.value;
    form.total_cost = totalCost.value;

    router.put(route('transactions.update', props.transaction.slug), form, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            toast.success('Parkir selesai!');
            close();
            setTimeout(() => {
                location.reload();
            }, 1000);
        },
    });
}
</script>

<template>
    <div v-if="isVisible" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-3xl">
            <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-gray-100">Bayar Parkir</h2>

            <div v-if="transaction" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Card for Vehicle Info -->
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-6 flex flex-col items-center">
                    <div class="flex flex-col items-center mb-4">
                        <VehicleIcon :vehicleType="transaction.parking_rate?.vehicle?.slug || 'default'"
                            class="w-16 h-16" />
                    </div>

                    <h3 class="text-lg font-medium text-gray-600 dark:text-gray-400">No. Tiket</h3>
                    <p class="text-3xl text-gray-800 dark:text-gray-200 font-bold mb-2">{{ transaction.no_ticket ||
                        'N/A' }}</p>
                    <h3 class="text-lg font-medium text-gray-600 dark:text-gray-400">No. Plat</h3>
                    <p class="text-2xl text-gray-800 dark:text-gray-200 font-bold">{{ transaction.license_plate || 'N/A'
                        }}</p>
                </div>

                <!-- Card for Parking Details -->
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-6 flex flex-col">
                    <h3 class="text-lg font-bold mb-4 text-gray-800 dark:text-gray-200">Detail Parkir</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Kendaraan:</span>
                            <span class="font-medium text-gray-800 dark:text-gray-200">{{
                                transaction.parking_rate?.vehicle?.name || 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Area Parkir:</span>
                            <span class="font-medium text-gray-800 dark:text-gray-200">{{ transaction.parking_area?.name
                                || 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Entry Masuk:</span>
                            <span class="font-medium text-gray-800 dark:text-gray-200">{{ transaction.entry_time ||
                                'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Entry Keluar:</span>
                            <span class="font-medium text-gray-800 dark:text-gray-200">{{ exitTime }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Setting:</span>
                            <span class="font-medium text-gray-800 dark:text-gray-200">{{
                                transaction.parking_rate?.time_interval || 0 }} min x {{
                                    formatRupiah(transaction.parking_rate?.cost || 0) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Duration Parkir:</span>
                            <span class="font-medium text-gray-800 dark:text-gray-200">{{ duration }} min</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Cost:</span>
                            <span class="font-medium text-gray-800 dark:text-gray-200">{{ formatRupiah(totalCost)
                                }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="text-center text-red-600 dark:text-red-400 py-4">
                No transaction data available
            </div>

            <!-- Payment Input -->
            <div class="mt-6" v-if="transaction">
                <label for="payment" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total
                    Pembayaran</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">Rp</span>
                    </div>
                    <input type="text" name="payment" id="payment" v-model="formattedPayment"
                        @input="updateFormattedPayment($event.target.value)"
                        class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 pr-12 sm:text-sm border-gray-300 rounded-md"
                        placeholder="0">
                </div>
                <p v-if="!isPaymentValid" class="mt-2 text-sm text-red-400">Pembayaran harus lebih besar atau sama
                    dengan total biaya parkir.</p>
            </div>

            <!-- Display Change -->
            <div class="mt-4" v-if="transaction">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kembalian</label>
                <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ formatNumber(changePay.toString())
                    }}</p>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button @click="updatePayment" :disabled="!transaction || !isPaymentValid"
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed">
                    Selesai
                </button>
                <button @click="close"
                    class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Batal
                </button>
            </div>
        </div>
    </div>
</template>