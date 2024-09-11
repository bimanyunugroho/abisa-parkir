<script setup>
import { ref, watch } from 'vue';
import { Head, useForm, usePage, router } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TextNumeric from '@/Components/TextNumeric.vue';
import SubmitButton from '@/Components/SubmitButton.vue';
import ActionLink from '@/Components/ActionLink.vue';
import SelectedInput from '@/Components/SelectedInput.vue';

const { props } = usePage();
const { title, desc, vehicles, errors: pageErrors } = props;

const toast = useToast();

const form = useForm({
    vehicle_id: '',
    time_interval: '',
    cost: '',
});

// Populate form errors if any
Object.keys(pageErrors).forEach(key => {
    if (pageErrors[key]) {
        form.errors[key] = pageErrors[key];
    }
});

const isProcessing = ref(false);

function handleSubmit() {
    isProcessing.value = true;

    const formData = {
        vehicle_id: form.vehicle_id,
        time_interval: parseInt(form.time_interval),
        cost: parseFloat(form.cost)
    };

    router.post(route('parking_rates.store'), form, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            toast.success('Setting parkir berhasil disimpan!');
            form.reset();
            isProcessing.value = false;
        },
        onError: (errors) => {
            console.error('Server errors:', errors);
            form.errors = errors;
            toast.error('Oopss... Terjadi kesalahan. Silakan periksa form.');
            isProcessing.value = false;
        }
    });
}

</script>

<template>

    <Head :title="title" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ desc }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <form @submit.prevent="handleSubmit">
                            <div class="mb-4">
                                <label for="vehicle_id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Tipe Kendaraan
                                </label>
                                <SelectedInput id="vehicle_id" label="Tipe Kendaraan" v-model="form.vehicle_id"
                                    :options="vehicles" :error="form.errors.vehicle_id" />
                            </div>

                            <div class="mb-4">
                                <label for="time_interval"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Waktu Parkir
                                </label>
                                <TextNumeric id="time_interval" v-model="form.time_interval" class="mt-1 block w-full"
                                    placeholder="Waktu parkir per-menit" :min="0" />
                                <p v-if="form.errors.time_interval" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                    {{ form.errors.time_interval }}
                                </p>
                            </div>

                            <div class="mb-4">
                                <label for="cost" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Harga
                                </label>
                                <TextNumeric id="cost" v-model="form.cost" class="mt-1 block w-full"
                                    placeholder="Harga per-menit" :min="0" />
                                <p v-if="form.errors.cost" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                    {{ form.errors.cost }}
                                </p>
                            </div>

                            <div class="flex items-center justify-end mt-4 space-x-2">
                                <SubmitButton :processing="isProcessing" :disabled="!form.isDirty" @click="handleSubmit"
                                    label="Create" />
                                <ActionLink :href="route('parking_rates.index')" label="Cancel" color="secondary" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>