
<script setup>
import { ref } from 'vue';
import { Head, useForm, usePage, router } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import TextArea from '@/Components/TextArea.vue';
import SubmitButton from '@/Components/SubmitButton.vue';
import ActionLink from '@/Components/ActionLink.vue';
import PhoneInput from '@/Components/PhoneInput.vue';

const { props } = usePage();
const { title, desc } = props;
const toast = useToast();
const form = useForm({
    name: '',
    address: '',
    phone_number: '',
    email: ''
});

const isProcessing = ref(false);

function handleSubmit() {
    isProcessing.value = true;
    router.post(route('companies.store'), form, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            toast.success('Data Perusahaan berhasil disimpan!');
            form.reset();
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
                            <!-- Nama Perusahaan -->
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                    Nama Perusahaan
                                </label>
                                <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" required autofocus />
                                <p v-if="form.errors.name" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                    {{ form.errors.name }}
                                </p>
                            </div>

                            <!-- Nomor Telepon -->
                            <div class="mb-4">
                                <label for="phone_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                    No.Telephone
                                </label>
                                <PhoneInput v-model="form.phone_number" />
                                <p v-if="form.errors.phone_number" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                    {{ form.errors.phone_number }}
                                </p>
                            </div>

                            <!-- Email -->
                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                    Email
                                </label>
                                <TextInput id="email" v-model="form.email" type="text" class="mt-1 block w-full" required autofocus />
                                <p v-if="form.errors.email" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                    {{ form.errors.email }}
                                </p>
                            </div>

                            <!-- Alamat -->
                            <div class="mb-4">
                                <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                                    Alamat
                                </label>
                                <TextArea id="address" v-model="form.address" type="text" class="mt-1 block w-full" required autofocus />
                                <p v-if="form.errors.address" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                    {{ form.errors.address }}
                                </p>
                            </div>

                            <!-- Tombol -->
                            <div class="flex items-center justify-end mt-4 space-x-2">
                                <SubmitButton :processing="isProcessing" :disabled="!form.isDirty" @click="handleSubmit" label="Create" />
                                <ActionLink :href="route('companies.index')" label="Cancel" color="secondary" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>