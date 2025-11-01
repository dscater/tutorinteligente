<script>
const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
    {
        title: "Reporte Usuarios",
        disabled: false,
        url: "",
        name_url: "",
    },
];
</script>

<script setup>
import { useApp } from "@/composables/useApp";
import { computed, onMounted, ref } from "vue";
import { Head, usePage } from "@inertiajs/vue3";

const { setLoading } = useApp();

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
        return "Generando Reporte...";
    }
    return "Generar Reporte";
});

const listEstudiantes = ref([]);

const generarReporte = () => {
    generando.value = true;
    const url = route("reportes.r_puntuacion_progresos", form.value);
    window.open(url, "_blank");
    setTimeout(() => {
        generando.value = false;
    }, 500);
};
</script>
<template>
    <Head title="Reporte Puntuación, Progresos y Seguimiento"></Head>
    <!-- BEGIN breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:;">Inicio</a></li>
        <li class="breadcrumb-item active">
            Reportes > Puntuación, Progresos y Seguimiento
        </li>
    </ol>
    <!-- END breadcrumb -->
    <!-- BEGIN page-header -->
    <h1 class="page-header">Reportes > Puntuación, Progresos y Seguimiento</h1>
    <!-- END page-header -->
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-body">
                    <form @submit.prevent="generarReporte">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Seleccionar estudiante*</label>
                                <el-select
                                    v-model="form.estudiante_id"
                                    filterable
                                >
                                    <el-option
                                        v-for="item in listEstudiantes"
                                        :KEY="item.id"
                                        :value="item.id"
                                        :label="item.full_name"
                                    >
                                    </el-option>
                                </el-select>
                            </div>
                            <div class="col-md-12 text-center mt-3">
                                <button
                                    class="btn btn-primary"
                                    block
                                    @click="generarReporte"
                                    :disabled="generando"
                                    v-text="txtBtn"
                                ></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
