<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    modelValue: {
        type: [Number, String],
        default: '',
    },
    placeholder: {
        type: String,
        default: '',
    },
    min: {
        type: Number,
        default: null,
    },
    max: {
        type: Number,
        default: null,
    },
});

const emit = defineEmits(['update:modelValue']);

const inputValue = ref('');
const rawValue = ref(props.modelValue);

const formatNumber = (num) => {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
};

const unformatNumber = (str) => {
    return str.replace(/\./g, '');
};

const updateValue = (event) => {
    let value = unformatNumber(event.target.value);

    // Only allow numeric input
    if (!/^\d*$/.test(value)) {
        event.target.value = inputValue.value;
        return;
    }

    if (value === '') {
        inputValue.value = '';
        rawValue.value = null;
        emit('update:modelValue', null);
        return;
    }

    let numValue = parseInt(value, 10);

    if (props.min !== null && numValue < props.min) {
        numValue = props.min;
    }

    if (props.max !== null && numValue > props.max) {
        numValue = props.max;
    }

    rawValue.value = numValue;
    inputValue.value = formatNumber(numValue);
    emit('update:modelValue', numValue);
};

const handleKeyDown = (event) => {
    // Allow: backspace, delete, tab, escape, enter, period
    if ([46, 8, 9, 27, 13, 190].indexOf(event.keyCode) !== -1 ||
        // Allow: Ctrl+A
        (event.keyCode === 65 && event.ctrlKey === true) ||
        // Allow: home, end, left, right
        (event.keyCode >= 35 && event.keyCode <= 39)) {
        return;
    }
    // Ensure that it is a number and stop the keypress
    if ((event.shiftKey || (event.keyCode < 48 || event.keyCode > 57)) && (event.keyCode < 96 || event.keyCode > 105)) {
        event.preventDefault();
    }
};

watch(() => props.modelValue, (newValue) => {
    rawValue.value = newValue;
    inputValue.value = newValue === null ? '' : formatNumber(newValue);
});

// Initial formatting
inputValue.value = props.modelValue === null ? '' : formatNumber(props.modelValue);
</script>

<template>
    <input type="text" :value="inputValue" @input="updateValue" @keydown="handleKeyDown" :placeholder="placeholder"
        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" />
</template>