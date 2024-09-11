<script setup>
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import axios from 'axios';
import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
import 'flatpickr/dist/themes/airbnb.css';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SelectedInput from '@/Components/SelectedInput.vue';

const props = defineProps({
    title: String,
    desc: String,
    vehicles: Array
});

const form = useForm({
    vehicles: [],
    startDate: '',
    endDate: '',
    reportType: ''
});

const isProcessing = ref(false);


const generateReport = () => {

};

const exportReport = () => {
    isProcessing.value = true;
    axios.post(route('reports.process.excell'), form.data(), {
        responseType: 'blob',
    }).then(response => {
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', `transactions_${form.startDate}_${form.endDate}.xlsx`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        isProcessing.value = false;
        toast.success('Report downloaded successfully!');
    }).catch(error => {
        console.error('Export error:', error);
        toast.error('Failed to download report. Please try again.');
        isProcessing.value = false;
    });
};

const downloadReport = () => {
    isProcessing.value = true;
    axios.post(route('reports.process.pdf'), form.data(), {
        responseType: 'blob',
    }).then(response => {
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', `transactions_${form.startDate}_${form.endDate}.pdf`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        isProcessing.value = false;
        toast.success('Report downloaded successfully!');
    }).catch(error => {
        console.error('Export error:', error);
        toast.error('Failed to download report. Please try again.');
        isProcessing.value = false;
    });
};
</script>

<template>

    <Head :title="title" />

    <AuthenticatedLayout>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 space-y-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Filter Laporan</h3>
                        <form @submit.prevent="generateReport" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="startDate"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal
                                        Mulai</label>
                                        <flat-pickr
                                            v-model="form.startDate"
                                            :config="config"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                            placeholder="Pilih Tanggal Mulai Parkir"
                                            name="date"/>
                                </div>
                                <div class="space-y-2">
                                    <label for="endDate"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal
                                        Akhir</label>
                                        <flat-pickr
                                            v-model="form.endDate"
                                            :config="config"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                            placeholder="Pilih Tanggal Akhir Parkir"
                                            name="date"/>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipe
                                    Laporan</label>
                                <div class="flex items-center space-x-6">
                                    <label class="inline-flex items-center">
                                        <input type="radio" value="rekap" v-model="form.reportType"
                                            class="form-radio text-indigo-600" />
                                        <span class="ml-2 text-gray-700 dark:text-gray-300">Rekap</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" value="detail" v-model="form.reportType"
                                            class="form-radio text-indigo-600" />
                                        <span class="ml-2 text-gray-700 dark:text-gray-300">Detail</span>
                                    </label>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <SelectedInput id="vehicles" label="Tipe Kendaraan" v-model="form.vehicles"
                                    :options="vehicles" :error="form.errors.vehicles" />
                            </div>

                            <div
                                class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0 sm:space-x-4">
                                <div class="flex space-x-2">
                                    <button type="button" @click="exportReport" :disabled="isProcessing"
                                        class="bg-green-600 text-white rounded-md px-4 py-2 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 transition-colors duration-200">
                                        {{ isProcessing ? 'Exporting...' : 'Export Excel' }}
                                    </button>
                                    <button type="button" @click="downloadReport" :disabled="isProcessing"
                                        class="bg-purple-600 text-white rounded-md px-4 py-2 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-opacity-50 transition-colors duration-200">
                                        {{ isProcessing ? 'Downloading...' : 'Download PDF' }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>