<script setup>
import { ref } from 'vue';
import { Head, useForm, usePage, router } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SubmitButton from '@/Components/SubmitButton.vue';
import ActionLink from '@/Components/ActionLink.vue';
import SelectedInput from '@/Components/SelectedInput.vue';

const { props } = usePage();
const { title, desc, roles, user, errors: pageErrors } = props;

const toast = useToast();

const form = useForm({
    role_id: user.role_id || ''
});

Object.keys(pageErrors).forEach(key => {
    if (pageErrors[key]) {
        form.errors[key] = pageErrors[key];
    }
});

const isProcessing = ref(false);

function handleSubmit() {
    isProcessing.value = true;

    router.put(route('access_users.update', user.slug), form, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            toast.success('Setting akses user berhasil diberikan!');
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
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- User Details Column -->
                            <div class="bg-gray-50 dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                                <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">User Details</h3>
                                <div class="space-y-4">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white text-xl font-bold">
                                            {{ user.name.charAt(0).toUpperCase() }}
                                        </div>
                                        <div class="ml-4">
                                            <p class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ user.name }}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ user.email }}</p>
                                        </div>
                                    </div>
                                    <div class="border-t border-gray-200 dark:border-gray-600 pt-4">
                                        <p class="text-sm text-gray-600 dark:text-gray-300">
                                            <span class="font-medium">Current Role:</span>
                                            <span class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                                {{ user.role ? user.role.name : 'No role assigned' }}
                                            </span>
                                        </p>
                                    </div>
                                    <!-- Add more user details here if needed -->
                                </div>
                            </div>

                            <!-- Role Assignment Column -->
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                                <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Assign Role</h3>
                                <form @submit.prevent="handleSubmit">
                                    <div class="mb-4">
                                        <label for="role_id"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Select New Role
                                        </label>
                                        <SelectedInput id="role_id" label="Role User" v-model="form.role_id"
                                            :options="roles" :error="form.errors.role_id" />
                                    </div>

                                    <div class="flex items-center justify-end mt-6 space-x-3">
                                        <ActionLink :href="route('access_users.index')" label="Cancel"
                                            color="secondary" />
                                        <SubmitButton :processing="isProcessing" :disabled="!form.isDirty"
                                            @click="handleSubmit" label="Update Role" />
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>