<script setup>
import { usePage } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TextShow from '@/Components/TextShow.vue';
import ActionLink from '@/Components/ActionLink.vue';

const { props } = usePage();
const { title, desc, company } = props;
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
                        <!-- Nama Perusahaan -->
                        <TextShow label="Nama Perusahaan" :value="company.name" />

                        <!-- Nomor Telepon -->
                        <TextShow label="No. Telephone" :value="company.phone_number" />

                        <!-- Email -->
                        <TextShow label="Email" :value="company.email" />

                        <!-- Alamat -->
                        <TextShow label="Alamat" :value="company.address" />

                        <!-- Tombol -->
                        <div class="flex items-center justify-end mt-4 space-x-2">
                            <ActionLink :href="route('companies.index')" label="Kembali" color="secondary" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>