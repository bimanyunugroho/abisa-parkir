<script setup>

import { router, useForm, Head } from '@inertiajs/vue3';
import { debounce } from 'lodash-es';
import { ref, watch } from 'vue';
import { useToast } from 'vue-toastification';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import Pagination from '@/Components/Pagination.vue';
import ActionLink from '@/Components/ActionLink.vue';
import NonActiveButton from '@/Components/NonActiveButton.vue';
import ActiveButton from '@/Components/ActiveButton.vue';

const props = defineProps({
    title: String,
    desc: String,
    users: Object,
    filters: Object,
});

const toast = useToast();
const search = ref(props.filters.search || '');
const form = useForm({
    search: props.filters.search || '',
});

const debouncedSearch = debounce(() => {
    router.get(route('access_users.index'), { search: form.search }, {
        preserveScroll: true,
        preserveState: true,
    });
}, 300);

watch(search, (value) => {
    form.search = value;
    debouncedSearch();
});

const formNonActive = useForm({
    user: 0,
});

function handleNonActiveUser(slug) {
    if (confirm('Apakah anda ingin meng-nonaktifkan user ini ?')) {
        router.put(route('access_users.non_active', slug), {}, {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => toast.success('User berhasil dinonaktifkan!')
        });
    }
}

function handleActiveUser(slug) {
    if (confirm('Apakah anda ingin meng-aktifkan user ini ?')) {
        router.put(route('access_users.active', slug), {}, {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => toast.success('User berhasil diaktifkan!')
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
                            <TextInput v-model="search" type="text" placeholder="Search users..."
                                class="w-full sm:w-64" />
                        </div>

                        <div class="overflow-x-auto">
                            <table v-if="users.data && users.data.length"
                                class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-slate-100 uppercase tracking-wider">
                                            #</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-slate-100 uppercase tracking-wider">
                                            Nama</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-slate-100 uppercase tracking-wider">
                                            Role</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-slate-100 uppercase tracking-wider">
                                            Email</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-slate-100 uppercase tracking-wider">
                                            Status</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-slate-100 uppercase tracking-wider">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-600">
                                    <tr v-for="(user, index) in users.data" :key="user.slug">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ index + 1 +
                                            (users.current_page - 1)
                                            *
                                            users.per_page }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ user.name ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ user.role ? (typeof user.role === 'object' ? user.role.name : user.role)
                                            : '-' }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">{{ user.email ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="{
                                                'inline-block px-3 py-1 text-sm font-semibold rounded-full': true,
                                                'text-green-800 bg-green-100': user.status === '1',
                                                'text-red-800 bg-red-100': user.status === '0'
                                            }">
                                                {{ user.status === '1' ? 'Active' : 'Non Active' }}
                                            </span>
                                        </td>


                                        <td class="px-6 py-4 whitespace-nowrap space-x-2">
                                            <ActionLink :href="route('access_users.edit', user.slug)" color="success"
                                                label="Akses" />
                                            <NonActiveButton v-if="user.status === '1'"
                                                @click="() => handleNonActiveUser(user.slug)" />
                                            <ActiveButton v-else @click="() => handleActiveUser(user.slug)" />
                                        </td>

                                    </tr>
                                </tbody>
                            </table>
                            <p v-else class="text-gray-500 dark:text-gray-400">No user found</p>
                        </div>
                        <Pagination v-if="users && users.links" :links="users.links" :limit="10" class="mt-6" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>