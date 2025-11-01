<script setup>
import { useApp } from "@/composables/useApp";
import { Head, Link, usePage } from "@inertiajs/vue3";
import { initDataTable } from "@/composables/datatable.js";
import { ref, onMounted, onBeforeUnmount } from "vue";
import Progreso from "./Progreso.vue";
const { props: props_page } = usePage();
const { setLoading } = useApp();
onMounted(() => {
    setTimeout(() => {
        setLoading(false);
    }, 300);
});
const search = ref("");
const listProgresos = ref([]);
const intervalBuscar = ref(null);
const buscarProgreso = () => {
    clearInterval(intervalBuscar.value);
    intervalBuscar.value = setTimeout(() => {
        cargarProgresos();
    });
};

const cargarProgresos = () => {
    axios
        .get(route("progresos.getProgresos"), {
            params: {
                search: search.value,
            },
        })
        .then((response) => {
            listProgresos.value = response.data;
        });
};

onMounted(async () => {
    cargarProgresos();
});
onBeforeUnmount(() => {});
</script>
<template>
    <Head title="Progresos y Seguimiento"></Head>

    <!-- BEGIN breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:;">Inicio</a></li>
        <li class="breadcrumb-item active">Progresos y Seguimiento</li>
    </ol>
    <!-- END breadcrumb -->
    <!-- BEGIN page-header -->
    <h1 class="page-header">Progresos y Seguimiento</h1>
    <!-- END page-header -->

    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="input-group">
                <input
                    type="text"
                    class="form-control"
                    placeholder="Nro. de C.I."
                    v-model="search"
                    @keyup="buscarProgreso"
                />
                <button
                    class="append btn btn-default"
                    @click.prevent="cargarProgresos"
                >
                    <i class="fa fa-search"></i>
                </button>
            </div>
            <p v-if="search.trim() != ''" class="text-white">
                Filtrando por: <span>{{ search }}</span>
            </p>
        </div>
        <div class="col-md-12" v-for="item in listProgresos">
            <!-- BEGIN panel -->
            <div class="panel panel-inverse">
                <!-- BEGIN panel-body -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row mb-1">
                                <div class="col-md-3 col-sm-4 text-right">
                                    <b>Progreso:</b>
                                </div>
                                <div class="col-md-9 col-sm-8">
                                    <Progreso :user_id="item.id"></Progreso>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-sm-4 text-right">
                                    <b>Nombre:</b>
                                </div>
                                <div class="col-md-9 col-sm-8">
                                    {{ item.full_name }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-sm-4 text-right">
                                    <b>C.I.:</b>
                                </div>
                                <div class="col-md-9 col-sm-8">
                                    {{ item.full_ci }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4"></div>
                    </div>
                </div>
                <!-- END panel-body -->
            </div>
            <!-- END panel -->
        </div>
    </div>
</template>
