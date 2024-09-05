<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    label: {
        type: String,
        default: 'Submit'
    },
    processing: {
        type: Boolean,
        default: false
    },
    disabled: {
        type: Boolean,
        default: false
    }
});

const isDisabled = computed(() => props.processing || props.disabled);

const emit = defineEmits(['click']);

const handleClick = () => {
    if (!isDisabled.value) {
        emit('click');
    }
};
</script>

<template>
    <button
        type="button"
        :disabled="isDisabled"
        @click="handleClick"
        class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
        :class="{ 'opacity-25': processing }"
    >
        {{ label }}
    </button>
</template>