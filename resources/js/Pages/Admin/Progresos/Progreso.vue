<script setup>
import { nextTick, onMounted, ref } from "vue";

const props = defineProps({
    user_id: {
        type: Number,
        default: 0,
    },
});
const oProgreso = ref(null);
const total = ref(0);
const total_user = ref(0);
const getProgresoEstudiante = () => {
    axios
        .post(route("practica_estudiantes.getProgresoEstudiante"), {
            user_id: props.user_id,
        })
        .then((response) => {
            oProgreso.value = response.data.progreso;
            total.value = response.data.total;
            total_user.value = response.data.total_user;
        });
};

onMounted(() => {
    nextTick(() => {
        getProgresoEstudiante();
    });
});
</script>
<template>
    <div class="barra">
        <div
            class="progreso"
            :style="[
                {
                    width: oProgreso?.progreso + '%',
                },
            ]"
        >
            <span class="txtProgreso">{{ oProgreso?.progreso }}%</span>
            <span class="info">{{ total_user }} / {{ total }}</span>
        </div>
    </div>
</template>
<style scoped></style>
