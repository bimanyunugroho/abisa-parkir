<script setup>
import { ref, watch } from 'vue';
import { Head, useForm, router, Link } from '@inertiajs/vue3';
import { debounce } from 'lodash-es';
import { useToast } from 'vue-toastification';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import Pagination from '@/Components/Pagination.vue';
import ActionLink from '@/Components/ActionLink.vue';
import DeleteButton from '@/Components/DeleteButton.vue';

const props = defineProps({
    title: String,
    desc: String,
    permissions: Object,
    filters: Object,
});

const toast = useToast();
const search = ref(props.filters.search || '');

const form = useForm({
    search: props.filters.search || '',
});


const debouncedSearch = debounce(() => {
    router.get(route('permissions.index'), { search: form.search }, {
        preserveState: true,
        preserveScroll: true,
    });
}, 300);

watch(search, (value) => {
    form.search = value;
    debouncedSearch();
});

function handleDeletePermission(slug) {
    if (confirm('Apakah anda ingin menghapus data ini ?')) {
        router.delete(route('permissions.destroy', slug), {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => toast.success('Data Akses Permission berhasil dihapus!'),
        });
    }
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
                        <div class="mb-4 flex items-center justify-between">
                            <TextInput v-model="search" type="text" placeholder="Search permissions..."
                                class="w-full sm:w-64" />
                            <ActionLink :href="route('permissions.create')" color="primary" label="Add" />
                        </div>

                        <div class="overflow-x-auto">
                            <table v-if="permissions.data && permissions.data.length"
                                class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-slate-100 uppercase tracking-wider">
                                            #</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-slate-100 uppercase tracking-wider">
                                            Permission</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-slate-100 uppercase tracking-wider">
                                            Waktu Input</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-slate-100 uppercase tracking-wider">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-600">
                                    <tr v-for="(permission, index) in permissions.data" :key="permission.slug">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ index + 1 + (permissions.current_page - 1)
                                            *
                                            permissions.per_page }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ permission.name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ permission.created_at }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap space-x-2">
                                            <ActionLink :href="route('permissions.edit', permission.slug)" color="warning"
                                                label="Edit" />
                                            <DeleteButton @click="() => handleDeletePermission(permission.slug)" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <p v-else class="text-gray-500 dark:text-gray-400">No permissions found</p>
                        </div>
                        <Pagination v-if="permissions && permissions.links" :links="permissions.links" :limit="10" class="mt-6" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>