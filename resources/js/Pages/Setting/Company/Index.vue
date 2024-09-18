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
    companies: Object,
    filters: Object,
});

const toast = useToast();
const search = ref(props.filters.search || '');

const form = useForm({
    search: props.filters.search || '',
});


const debouncedSearch = debounce(() => {
    router.get(route('companies.index'), { search: form.search }, {
        preserveState: true,
        preserveScroll: true,
    });
}, 300);

watch(search, (value) => {
    form.search = value;
    debouncedSearch();
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
                        <div class="mb-4 flex items-center justify-between">
                            <TextInput v-model="search" type="text" placeholder="Search companies..." class="w-full sm:w-64" />
                            <div v-if="companies.data && companies.data.length === 0">
                                <ActionLink :href="route('companies.create')" color="primary" label="Add" />
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table v-if="companies.data && companies.data.length"
                                class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-slate-100 uppercase tracking-wider">
                                            #</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-slate-100 uppercase tracking-wider">
                                            Perusahaan</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-slate-100 uppercase tracking-wider">
                                            No.Telephone</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-slate-100 uppercase tracking-wider">
                                            Email</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-slate-100 uppercase tracking-wider">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-600">
                                    <tr v-for="(company, index) in companies.data" :key="company.slug">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ index + 1 + (companies.current_page - 1)
                                            *
                                            companies.per_page }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ company.name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ company.phone_number }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ company.email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap space-x-2">
                                            <ActionLink :href="route('companies.show', company.slug)" color="primary"
                                                label="Detail" />
                                            <ActionLink :href="route('companies.edit', company.slug)" color="warning"
                                                label="Edit" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <p v-else class="text-gray-500 dark:text-gray-400">No companies found</p>
                        </div>
                        <Pagination v-if="companies && companies.links" :links="companies.links" :limit="10" class="mt-6" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>