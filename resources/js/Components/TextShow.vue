<script setup>
const props = defineProps({
    label: {
        type: String,
        required: true
    },
    value: {
        type: [String, Number],
        default: ''
    },
    id: {
        type: String,
        default: null
    }
});
</script>

<template>
    <div class="mb-4">
        <label :for="id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
            {{ label }}
        </label>
        <div :id="id"
            class="mt-1 block w-full text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-700 p-2 rounded-md">
            {{ value || '-' }}
        </div>
    </div>
</template>