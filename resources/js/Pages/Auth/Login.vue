<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>

        <Head title="Abisa Parking System - Log in" />

        <div class="w-full max-w-md p-4 space-y-8 bg-white dark:bg-gray-800 rounded-lg">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Welcome Back!</h2>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Log in to your Abisa Parking System account</p>
            </div>

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                {{ status }}
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <div>
                    <InputLabel for="email" value="Email" class="text-gray-900 dark:text-gray-200" />
                    <TextInput id="email" type="email"
                        class="mt-1 block w-full border border-indigo-500 rounded-md px-3 py-2 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                        v-model="form.email" required autofocus autocomplete="username" />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div>
                    <InputLabel for="password" value="Password" class="text-gray-900 dark:text-gray-200" />
                    <TextInput id="password" type="password"
                        class="mt-1 block w-full border border-indigo-500 rounded-md px-3 py-2 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                        v-model="form.password" required autocomplete="current-password" />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center text-sm text-gray-900 dark:text-gray-200">
                        <Checkbox name="remember" v-model:checked="form.remember" />
                        <span class="ml-2">Remember me</span>
                    </label>

                    <Link v-if="canResetPassword" :href="route('password.request')"
                        class="text-sm text-indigo-500 hover:text-indigo-600 dark:text-indigo-400 dark:hover:text-indigo-300 focus:outline-none">
                    Forgot your password?
                    </Link>
                </div>

                <div class="mt-6">
                    <PrimaryButton class="w-full flex justify-center" :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing">
                        Log in
                    </PrimaryButton>
                </div>

                <div class="mt-4 text-center text-sm text-gray-500 dark:text-gray-400">
                    <p>Don't have an account?</p>
                    <Link v-if="canRegister" :href="route('register')"
                        class="text-indigo-500 hover:text-indigo-600 dark:text-indigo-400 dark:hover:text-indigo-300">
                    Register here
                    </Link>
                </div>
            </form>
        </div>
    </GuestLayout>
</template>
