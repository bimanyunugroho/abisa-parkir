<script setup>
const props = defineProps({
    onClick: {
        type: Function,
        required: true
    }
});
</script>

<template>
    <button type="button" @click="onClick"
        class="action-button text-green-600 border-green-600 hover:bg-green-600 hover:text-white dark:text-green-400 dark:border-green-400 dark:hover:bg-green-400 dark:hover:text-slate">
        Aktif User
    </button>
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
    transition: all 0.3s ease-out;
    cursor: pointer;
}

.action-button:hover {
    transform: translateY(-0.25rem);
}
</style>