<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    links: {
        type: Array,
        default: () => [],
    },
    limit: {
        type: Number,
        default: 10,
    },
});

const visibleLinks = computed(() => {
    if (props.links.length <= props.limit) return props.links;

    const currentIndex = props.links.findIndex(link => link.active);
    const half = Math.floor((props.limit - 3) / 2);
    let start = Math.max(1, currentIndex - half);
    let end = Math.min(props.links.length - 2, start + props.limit - 4);

    if (end - start < props.limit - 4) {
        start = Math.max(1, end - props.limit + 4);
    }

    const visibleLinks = [
        props.links[0],
        ...(start > 1 ? [{ label: '...', url: null }] : []),
        ...props.links.slice(start, end + 1),
        ...(end < props.links.length - 2 ? [{ label: '...', url: null }] : []),
        props.links[props.links.length - 1],
    ];

    return visibleLinks;
});
</script>

<template>
    <div v-if="links.length > 3" class="flex justify-end items-center space-x-1">
        <template v-for="(link, key) in visibleLinks" :key="key">
            <div v-if="link.url === null"
                class="px-4 py-2 text-sm font-medium text-gray-400 bg-gray-800 border border-gray-600 rounded-md cursor-not-allowed"
                v-html="link.label" />
            <Link v-else
                class="px-4 py-2 text-sm text-gray-300 bg-gray-800 border border-gray-600 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                :class="{ 'bg-gray-700 font-bold text-white': link.active }" :href="link.url" v-html="link.label" />
        </template>
    </div>
</template>