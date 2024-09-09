<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Head title="Abisa Parking System" />
    <div class="bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
        <div
            class="relative min-h-screen flex flex-col items-center justify-center selection:bg-blue-500 selection:text-white"
        >
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                    <div class="flex lg:justify-center lg:col-start-2">
                        <svg
                            class="h-12 w-auto text-blue-500 lg:h-16"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M19 9H5C3.89543 9 3 9.89543 3 11V20C3 21.1046 3.89543 22 5 22H19C20.1046 22 21 21.1046 21 20V11C21 9.89543 20.1046 9 19 9Z"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                            <path
                                d="M8 9V5C8 3.93913 8.42143 2.92172 9.17157 2.17157C9.92172 1.42143 10.9391 1 12 1C13.0609 1 14.0783 1.42143 14.8284 2.17157C15.5786 2.92172 16 3.93913 16 5V9"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>
                    </div>
                </header>

                <main class="mt-6">
                    <div class="text-center">
                        <h1 class="text-4xl font-bold text-gray-900 dark:text-white sm:text-5xl">Abisa Parking System</h1>
                        <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">Efficient parking management</p>
                    </div>

                    <div class="mt-12 text-center">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="rounded-md bg-blue-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500"
                        >
                            Dashboard
                        </Link>

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="rounded-md bg-indigo-500 px-3 py-2 mx-1 text-sm font-semibold text-white shadow-sm hover:bg-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500"
                            >
                                Log in
                            </Link>

                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="rounded-md border-2 border-indigo-500 px-3 py-2 mx-1 text-sm font-semibold text-white shadow-sm hover:border-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500"
                            >
                                Register
                            </Link>


                        </template>
                    </div>
                </main>

                <footer class="mt-16 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                    <p>&copy; 2024 Abisa Parking System. All rights reserved.</p>
                </footer>
            </div>
        </div>
    </div>
</template>