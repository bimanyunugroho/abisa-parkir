<script setup>
import { ref } from 'vue';
import { Head, useForm, usePage, Link, router } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TextInputUppercase from '@/Components/TextInputUppercase.vue';
import SubmitButton from '@/Components/SubmitButton.vue';

const { props } = usePage();
const { title, desc, vehicle, errors: pageErrors } = props;
const toast = useToast();
const form = useForm({
    name: vehicle.name
});

if (pageErrors.name) {
    form.errors.name = pageErrors.name;
}

const isProcessing = ref(false);
function handleSubmit() {
    isProcessing.value = true;
    router.put(route('vehicles.update', vehicle.slug), form, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            toast.success('Data kendaraan berhasil diubah!');
            isProcessing.value = false;
        },
        onError: (errors) => {
            form.errors = errors;
            toast.error('Ooppss...');
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
                                <label for="name"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kendaraan</label>
                                <TextInputUppercase id="name" v-model="form.name" type="text" class="mt-1 block w-full" required
                                    autofocus placeholder="Motor" />
                                <p v-if="form.errors.name" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                    {{ form.errors.name }}
                                </p>
                            </div>
                            <div class="flex items-center justify-end mt-4 space-x-2">
                                <SubmitButton :processing="isProcessing" :disabled="!form.isDirty" @click="handleSubmit"
                                    label="Update" />
                                <Link :href="route('vehicles.index')"
                                    class="px-4 py-2 text-sm text-gray-300 bg-gray-800 border border-gray-600 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                Cancel
                                </Link>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>