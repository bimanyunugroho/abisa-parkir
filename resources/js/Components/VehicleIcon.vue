<script setup>
import { defineProps } from 'vue';

const props = defineProps({
    vehicleType: {
        type: String,
        required: true,
    },
});

const getVehicleDetails = (type) => {
    switch (type) {
        case 'motor':
            return { src: '/images/motor.svg', label: 'Motor' };
        case 'mobil':
            return { src: '/images/car.svg', label: 'Mobil' };
        case 'truck':
            return { src: '/images/truck.svg', label: 'Truck' };
        case 'sepeda':
            return { src: '/images/bike.svg', label: 'Sepeda' };
        case 'bus':
            return { src: '/images/bus.svg', label: 'Bus' };
        default:
            return { src: '/images/default.svg', label: 'Lainnya' };
    }
};

const { src: iconSrc, label: vehicleLabel } = getVehicleDetails(props.vehicleType);
</script>

<template>
    <div class="flex flex-col items-center">
        <img :src="iconSrc" alt="Vehicle Icon" class="w-52 h-52 vehicle-icon" style="filter: brightness(0) saturate(100%) invert(67%) sepia(8%) saturate(1400%) hue-rotate(190deg) brightness(100%) contrast(100%);" />
    </div>
</template>

<style scoped>
.vehicle-icon {
    transition: transform 0.3s ease, filter 0.3s ease;
    filter: brightness(0) saturate(100%) invert(67%) sepia(8%) saturate(1400%) hue-rotate(190deg) brightness(100%) contrast(100%);
}

.vehicle-icon:hover {
    transform: scale(1.1); /* Slightly increase the size on hover */
    filter: brightness(0) saturate(100%) invert(50%) sepia(20%) saturate(1400%) hue-rotate(190deg) brightness(100%) contrast(100%);
}
</style>