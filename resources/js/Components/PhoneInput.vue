<script setup>
import { ref, watch, computed } from 'vue';

const props = defineProps({
    modelValue: String
});

const emit = defineEmits(['update:modelValue']);
const errorMessage = ref('');

// Fungsi untuk hanya memperbolehkan angka di input
function sanitizePhoneNumber(phoneNumber) {
    return phoneNumber.replace(/\D/g, '');
}

// Fungsi untuk memformat nomor telepon dengan spasi setiap 4 digit
function formatPhoneNumber(event) {
    const rawValue = sanitizePhoneNumber(event.target.value);

    let formatted = rawValue
        .slice(0, 13)
        .replace(/(\d{4})(\d{4})(\d{0,4})/, '$1 $2 $3')
        .trim();

    emit('update:modelValue', formatted);
}

// Cegah input selain angka
function onlyAllowNumbers(event) {
    const allowedKeys = ['Backspace', 'Tab', 'ArrowLeft', 'ArrowRight'];
    if (!/^\d$/.test(event.key) && !allowedKeys.includes(event.key)) {
        event.preventDefault();
    }
}

// Observasi perubahan nilai dan validasi
watch(() => props.modelValue, (newValue) => {
    if (newValue && sanitizePhoneNumber(newValue).length < 10) {
        errorMessage.value = 'Nomor telepon tidak valid';
    } else {
        errorMessage.value = '';
    }
});

// Membuat nomor telepon terformat dengan spasi
const formattedPhoneNumber = computed(() => {
    return props.modelValue
        ? props.modelValue
        : '';
});
</script>

<template>
    <div>
        <input :value="formattedPhoneNumber" 
               @input="formatPhoneNumber" 
               @keydown="onlyAllowNumbers" 
               type="text" 
               class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-full"
               :maxlength="14" />
        <p v-if="errorMessage" class="mt-2 text-sm text-red-600 dark:text-red-400">{{ errorMessage }}</p>
    </div>
</template>
