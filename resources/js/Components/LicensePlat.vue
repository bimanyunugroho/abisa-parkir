<script setup>
import { ref, watch, computed } from 'vue';

const props = defineProps({
    modelValue: {
        type: String,
        default: ''
    }
});

const emit = defineEmits(['update:modelValue']);

const firstPart = ref('');
const secondPart = ref('');
const thirdPart = ref('');

const isValid = computed(() => {
    return firstPart.value.length > 0 &&
        secondPart.value.length > 0 &&
        secondPart.value.length <= 4 &&
        thirdPart.value.length > 0;
});

watch([firstPart, secondPart, thirdPart], () => {
    if (isValid.value) {
        const licensePlate = `${firstPart.value} ${secondPart.value} ${thirdPart.value}`.trim();
        emit('update:modelValue', licensePlate);
    } else {
        emit('update:modelValue', '');
    }
});

const updateFirstPart = () => {
    firstPart.value = firstPart.value.toUpperCase().replace(/[^A-Z]/g, '').slice(0, 2);
};

const updateSecondPart = () => {
    secondPart.value = secondPart.value.replace(/[^0-9]/g, '').slice(0, 4);
};

const updateThirdPart = () => {
    thirdPart.value = thirdPart.value.toUpperCase().replace(/[^A-Z]/g, '').slice(0, 3);
};

</script>

<template>
    <div>
        <div class="flex space-x-0">
            <div
                class="border-gray-300 dark:border-gray-700 w-full dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm p-7 flex items-center justify-center">
                <input type="text" maxlength="2" v-model="firstPart" @input="updateFirstPart"
                    class="dark:border-gray-900 dark:bg-gray-900 dark:text-gray-300 rounded-l-md border-r-0 p-7 shadow-sm w-28 text-3xl font-extrabold focus:outline-none focus:ring-0 border-none"
                    placeholder="AD" autofocus />
                <input type="text" maxlength="4" v-model="secondPart" @input="updateSecondPart"
                    class="dark:border-gray-900 dark:bg-gray-900 dark:text-gray-300 border-r-0 p-7 shadow-sm w-40 text-3xl font-extrabold focus:outline-none focus:ring-0 border-none"
                    placeholder="1234" />
                <input type="text" maxlength="3" v-model="thirdPart" @input="updateThirdPart"
                    class="dark:border-gray-900 dark:bg-gray-900 dark:text-gray-300 rounded-r-md p-7 shadow-sm w-24 text-3xl font-extrabold focus:outline-none focus:ring-0 border-none"
                    placeholder="ABV" />
            </div>
        </div>
        <div v-if="!isValid" class="mt-1 text-red-600 dark:text-red-400">
            Semua Inputan Harus terisi
        </div>
    </div>
</template>