<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';
import { ref, onMounted, computed } from 'vue';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import SubmitButton from '@/Components/SubmitButton.vue';
import ActionLink from '@/Components/ActionLink.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    title: String,
    desc: String,
    permissions: Array,
    role: Object,
});

const toast = useToast();

const form = useForm({
    permissions: [],
});

const selectedPermissions = ref([]);

onMounted(() => {
    selectedPermissions.value = props.role.permissions.map(p => p.id);
    form.permissions = selectedPermissions.value;
});

const isProcessing = ref(false);

function handleSubmit() {
    isProcessing.value = true;
    form.permissions = selectedPermissions.value;
    form.put(route('rbacs.update', props.role.slug), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            toast.success('RBAC berhasil diperbarui!');
            isProcessing.value = false;
        },
        onError: (errors) => {
            toast.error('Terjadi kesalahan.');
            isProcessing.value = false;
        }
    });
}

function togglePermission(permissionId) {
    const index = selectedPermissions.value.indexOf(permissionId);
    if (index === -1) {
        selectedPermissions.value.push(permissionId);
    } else {
        selectedPermissions.value.splice(index, 1);
    }
    form.permissions = selectedPermissions.value;
}

const groupedPermissions = computed(() => {
    const groups = {
        'View': [],
        'Edit': [],
        'Create': [],
        'Delete': [],
        'Generate': [],
        'Others': []
    };

    props.permissions.forEach(permission => {
        const name = permission.name.toLowerCase();
        if (name.includes('view')) groups['View'].push(permission);
        else if (name.includes('edit')) groups['Edit'].push(permission);
        else if (name.includes('create')) groups['Create'].push(permission);
        else if (name.includes('delete')) groups['Delete'].push(permission);
        else if (name.includes('generate')) groups['Generate'].push(permission);
        else groups['Others'].push(permission);
    });

    return Object.fromEntries(Object.entries(groups).filter(([_, perms]) => perms.length > 0));
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
                        <form @submit.prevent="handleSubmit">
                            <div class="mb-4">
                                <InputLabel for="role_name" value="Role Name" />
                                <TextInput id="name" v-model="role.name" type="text" class="mt-1 block w-full" readonly />
                            </div>

                            <div class="mb-4">
                                <InputLabel value="Permissions" />
                                <div class="mt-2 space-y-6 max-h-[60vh] overflow-y-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-800">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Group
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    Permissions
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-900 dark:divide-gray-700">
                                            <tr v-for="(group, groupName) in groupedPermissions" :key="groupName">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ groupName }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">
                                                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                                        <div v-for="permission in group" :key="permission.id" class="flex items-center">
                                                            <input
                                                                type="checkbox"
                                                                :id="'permission-' + permission.id"
                                                                :value="permission.id"
                                                                :checked="selectedPermissions.includes(permission.id)"
                                                                @change="togglePermission(permission.id)"
                                                                class="form-checkbox h-5 w-5 text-blue-600 transition duration-150 ease-in-out rounded-md"
                                                            />
                                                            <InputLabel :for="'permission-' + permission.id" :value="permission.name" class="ml-2 text-sm" />
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <InputError :message="form.errors.permissions" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <SubmitButton :processing="isProcessing" :disabled="!form.isDirty" @click="handleSubmit"
                                    label="Update" />
                                <ActionLink :href="route('rbacs.index')" label="Cancel" color="secondary"
                                    class="mx-2" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>