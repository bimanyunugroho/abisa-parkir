<script setup>
const props = defineProps({
    id: {
        type: String,
        required: true
    },
    label: {
        type: String,
        required: true
    },
    modelValue: {
        type: [String, Number, Array],
        required: true
    },
    options: {
        type: Array,
        required: true
    },
    error: {
        type: String,
        required: false
    }
});

const emit = defineEmits(['update:modelValue']);

function updateValue(event) {
    emit('update:modelValue', event.target.value);
}
</script>

<template>
    <div>
        <select :id="id" :value="modelValue" @change="updateValue"
            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-700 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-800 dark:text-gray-300">
            <option disabled value="">Pilih {{ label }}</option>
            <option v-for="option in options" :key="option.id" :value="option.id">
                {{ option.name }}
            </option>
        </select>
        <p v-if="error" class="mt-2 text-sm text-red-600 dark:text-red-400">
            {{ error }}
        </p>
    </div>
</template>