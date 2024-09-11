<script setup>
import { Head, Link } from '@inertiajs/vue3';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ActionLink from '@/Components/ActionLink.vue';

const props = defineProps({
    title: String,
    desc: String,
    role: Object,
    user_count: Number
});

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
                        <div class="flex justify-between">
                            <div class="mb-4">
                                <h1 class="text-lg font-semibold mb-2">Role</h1>
                                <p class="text-4xl font-bold inline-flex items-center px-2.5 py-0.5 rounded-full bg-blue-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100">{{ role.name }}</p>
                            </div>

                            <div class="mb-4">
                                <h1 class="text-lg font-semibold mb-2">Jumlah User</h1>
                                <p class="text-4xl font-semibold inline-flex items-center px-2.5 py-0.5 rounded-full bg-blue-100 text-violet-800 dark:bg-violet-800 dark:text-violet-100">{{ user_count }} User</p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h1 class="text-lg font-semibold mb-2">Permissions</h1>
                            <div class="flex flex-wrap gap-2">
                                <span v-for="permission in role.permissions" :key="permission.id"
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                    {{ permission.name }}
                                </span>
                            </div>
                        </div>


                        <div class="flex items-center justify-end mt-4">
                            <ActionLink :href="route('rbacs.index')" label="Back" color="secondary" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>