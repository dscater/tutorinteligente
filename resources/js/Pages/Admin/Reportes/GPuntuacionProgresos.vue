<script setup>
import { useApp } from "@/composables/useApp";
import { computed, nextTick, onMounted, ref } from "vue";
import { Head, usePage } from "@inertiajs/vue3";
import Highcharts from "highcharts";
import exporting from "highcharts/modules/exporting";
import accessibility from "highcharts/modules/accessibility";
const { setLoading } = useApp();
exporting(Highcharts);
accessibility(Highcharts);
const cargarListas = () => {
    getEstudiantes();
};

const getEstudiantes = () => {
    axios.get(route("estudiantes.listado")).then((response) => {
        listEstudiantes.value = response.data.estudiantes;
        listEstudiantes.value.unshift({
            id: "todos",
            full_name: "TODOS",
        });
    });
};

onMounted(() => {
    cargarListas();
    setTimeout(() => {
        setLoading(false);
    }, 300);
});

const form = ref({
    estudiante_id: "todos",
});

const generando = ref(false);
const txtBtn = computed(() => {
    if (generando.value) {
        return "Generando Gráfico...";
    }
    return "Generar Gráfico";
});

const listEstudiantes = ref([]);

const generarGrafico = async () => {
    generando.value = true;
    axios
        .get(route("reportes.r_gpuntuacion_progresos"), {
            params: form.value,
        })
        .then((response) => {
            nextTick(() => {
                const containerId = `container`;
                const container = document.getElementById(containerId);
                // Verificar que el contenedor exista y tenga un tamaño válido
                if (container) {
                    renderChart(
                        containerId,
                        response.data.categories,
                        response.data.data
                    );
                } else {
                    console.error(`Contenedor ${containerId} no válido.`);
                }
            });
            // Create the chart
            generando.value = false;
        });
};
const renderChart = (containerId, categories, data) => {
    const rowHeight = 80;
    const minHeight = 200;
    const calculatedHeight = Math.max(minHeight, categories.length * rowHeight);
    Highcharts.chart(containerId, {
        chart: {
            type: "bar",
            height: calculatedHeight,
        },
        title: {
            align: "center",
            text: `PUNTUACIÓN Y PROGRESO`,
        },
        subtitle: {
            align: "center",
            text: ``,
        },
        accessibility: {
            announceNewData: {
                enabled: true,
            },
        },
        xAxis: {
            categories: categories,
        },
        yAxis: {
            title: {
                text: "TOTAL",
            },
        },
        legend: {
            enabled: true,
        },
        plotOptions: {
            series: {
                dataLabels: {
                    enabled: true,
                    // format: "{point.y}",
                    style: {
                        fontSize: "11px",
                        fontWeight: "bold",
                    },
                },
            },
        },
        tooltip: {},

        series: data,
    });
};
</script>
<template>
    <Head title="Reporte Puntuación y Progreso"></Head>
    <!-- BEGIN breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:;">Inicio</a></li>
        <li class="breadcrumb-item active">Reportes > Puntuación y Progreso</li>
    </ol>
    <!-- END breadcrumb -->
    <!-- BEGIN page-header -->
    <h1 class="page-header">Reportes > Puntuación y Progreso</h1>
    <!-- END page-header -->
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-body">
                    <form @submit.prevent="generarGrafico">
                        <div class="row">
                            <div class="col-md-12 text-center mt-3">
                                <button
                                    class="btn btn-primary"
                                    block
                                    @click="generarGrafico"
                                    :disabled="generando"
                                    v-text="txtBtn"
                                ></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12 mt-3">
            <div id="container"></div>
        </div>
    </div>
</template>
