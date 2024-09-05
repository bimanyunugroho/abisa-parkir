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
    roles: Object,
    filters: Object,
});

const toast = useToast();
const search = ref(props.filters.search || '');

const form = useForm({
    search: props.filters.search || '',
});


const debouncedSearch = debounce(() => {
    router.get(route('roles.index'), { search: form.search }, {
        preserveState: true,
        preserveScroll: true,
    });
}, 300);

watch(search, (value) => {
    form.search = value;
    debouncedSearch();
});

function handleDeleteRole(slug) {
    if (confirm('Apakah anda ingin menghapus data ini ?')) {
        router.delete(route('roles.destroy', slug), {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => toast.success('Master role berhasil dihapus!'),
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
                            <TextInput v-model="search" type="text" placeholder="Search roles..."
                                class="w-full sm:w-64" />
                            <ActionLink :href="route('roles.create')" color="primary" label="Add" />
                        </div>

                        <div class="overflow-x-auto">
                            <table v-if="roles.data && roles.data.length"
                                class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-slate-100 uppercase tracking-wider">
                                            #</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-slate-100 uppercase tracking-wider">
                                            Role</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-slate-100 uppercase tracking-wider">
                                            Waktu Input</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-slate-100 uppercase tracking-wider">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-600">
                                    <tr v-for="(role, index) in roles.data" :key="role.slug">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ index + 1 + (roles.current_page - 1)
                                            *
                                            roles.per_page }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ role.name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ role.created_at }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap space-x-2">
                                            <ActionLink :href="route('roles.edit', role.slug)" color="warning"
                                                label="Edit" />
                                            <DeleteButton @click="() => handleDeleteRole(role.slug)" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <p v-else class="text-gray-500 dark:text-gray-400">No roles found</p>
                        </div>
                        <Pagination v-if="roles && roles.links" :links="roles.links" :limit="10" class="mt-6" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>