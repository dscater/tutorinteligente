<script setup>
import { useApp } from "@/composables/useApp";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { ref, onMounted, onBeforeUnmount, nextTick } from "vue";
import Formulario from "./Formulario.vue";
// const { mobile, identificaDispositivo } = useMenu();
const { props: props_page } = usePage();
const { setLoading } = useApp();

const accion_dialog = ref(0);
const open_dialog = ref(false);

const form = useForm({
    nivel: "",
    seccion: "",
});
const listSecciones = ref([]);
const listNivels = ref([]);
const oPractica = ref(null);
const cargaSecciones = () => {
    axios
        .get(route("secciones.index"), {
            params: {
                exep: [0],
            },
        })
        .then((response) => {
            listSecciones.value = response.data;
        });
};
const cargarNivels = () => {
    axios.get(route("nivels.index")).then((response) => {
        listNivels.value = response.data;
    });
};

const cargarListas = () => {
    cargaSecciones();
    cargarNivels();
};

const listPracticas = ref([]);

const cargarPracticas = () => {
    if (form.seccion && form.nivel) {
        listPracticas.value = [];
        axios
            .get(route("practicas.listado"), {
                params: {
                    nivel: form.nivel,
                    seccion: form.seccion,
                },
            })
            .then((response) => {
                listPracticas.value = response.data.practicas;
            });
    }
};

const resolver = (item) => {
    accion_dialog.value = 0;
    open_dialog.value = true;
    oPractica.value = item;
};

onMounted(() => {
    cargarListas();
    setTimeout(() => {
        setLoading(false);
    }, 300);
});
</script>
<template>
    <Head title="Prácticas"></Head>

    <!-- BEGIN breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:;">Inicio</a></li>
        <li class="breadcrumb-item active">Prácticas</li>
    </ol>
    <!-- END breadcrumb -->
    <!-- BEGIN page-header -->
    <h1 class="page-header">Prácticas</h1>
    <!-- END page-header -->

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <p>Seleccione el nivel y la sección</p>
                        </div>
                        <div class="col-md-6">
                            <label for="">Nivel</label>
                            <el-select
                                class="w-100"
                                v-model="form.nivel"
                                placeholder="- Seleccione -"
                                filterable
                                @change="cargarPracticas"
                            >
                                <el-option
                                    v-for="item in listNivels"
                                    :value="item.value"
                                    :label="item.label"
                                ></el-option>
                            </el-select>
                        </div>
                        <div class="col-md-6">
                            <label for="">Sección</label>
                            <el-select
                                class="w-100"
                                v-model="form.seccion"
                                placeholder="- Seleccione -"
                                filterable
                                @change="cargarPracticas"
                            >
                                <el-option
                                    v-for="item in listSecciones"
                                    :value="item.value"
                                    :label="item.label"
                                ></el-option>
                            </el-select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-2" v-for="item in listPracticas">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <p class="font-weight-bold">
                                Nivel: {{ item.nivel }}
                            </p>
                            <p class="font-weight-bold">
                                Sección: {{ item.seccion }}
                            </p>
                            <p>{{ item.descripcion }}</p>
                        </div>
                        <div class="col-md-4">
                            <button
                                class="btn btn-success w-100"
                                @click="resolver(item)"
                            >
                                <i class="fa fa-edit"></i> Resolver
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Formulario
            :open_dialog="open_dialog"
            :accion_dialog="accion_dialog"
            :oPractica="oPractica"
            @cerrar-dialog="open_dialog = false"
        ></Formulario>
    </div>
</template>
