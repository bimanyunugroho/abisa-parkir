<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true
    },
    color: {
        type: String,
        default: 'orange'
    },
    label: {
        type: String,
        required: true
    }
});

const colorClasses = {
    secondary: 'text-slate-600 border-slate-600 hover:bg-slate-600 hover:text-white dark:text-slate-400 dark:border-slate-400 dark:hover:bg-slate-400 dark:hover:text-slate',
    primary: 'text-blue-600 border-blue-600 hover:bg-blue-600 hover:text-white dark:text-blue-400 dark:border-blue-400 dark:hover:bg-blue-400 dark:hover:text-slate',
    warning: 'text-orange-600 border-orange-600 hover:bg-orange-600 hover:text-white dark:text-orange-400 dark:border-orange-400 dark:hover:bg-orange-400 dark:hover:text-slate',
    danger: 'text-red-600 border-red-600 hover:bg-red-600 hover:text-white dark:text-red-400 dark:border-red-400 dark:hover:bg-red-400 dark:hover:text-slate',
    success: 'text-green-600 border-green-600 hover:bg-green-600 hover:text-white dark:text-green-400 dark:border-green-400 dark:hover:bg-green-400 dark:hover:text-slate'
};
</script>

<template>
    <Link :href="href" :class="['action-button', colorClasses[color]]">
        {{ label }}
    </Link>
</template>

<style scoped>
.action-button {
    display: inline-block;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    font-weight: 500;
    text-align: center;
    background-color: transparent;
    border: 1px solid;
    border-radius: 0.375rem;
    transition: transform 0.3s ease-out, background-color 0.3s ease-out, color 0.3s ease-out;
    cursor: pointer;
}

.action-button:hover {
    transform: translateY(-0.25rem);
}
</style>
