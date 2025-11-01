<script setup>
import { useApp } from "@/composables/useApp";
import { Head, Link, usePage } from "@inertiajs/vue3";
import { initDataTable } from "@/composables/datatable.js";
import { ref, onMounted, onBeforeUnmount } from "vue";
const { props: props_page } = usePage();
const { setLoading } = useApp();
onMounted(() => {
    setTimeout(() => {
        setLoading(false);
    }, 300);
});
const search = ref("");
const listPuntuaciones = ref([]);
const intervalBuscar = ref(null);
const buscarPuntuacion = () => {
    clearInterval(intervalBuscar.value);
    intervalBuscar.value = setTimeout(() => {
        cargarPuntuaciones();
    });
};

const cargarPuntuaciones = () => {
    axios
        .get(route("puntuacions.getPuntuacions"), {
            params: {
                search: search.value,
            },
        })
        .then((response) => {
            listPuntuaciones.value = response.data;
        });
};

const reiniciarPuntaje = (user_id) => {
    Swal.fire({
        title: "¿Quieres reiniciar el puntaje?",
        html: ``,
        showCancelButton: true,
        confirmButtonColor: "#0f9e3e",
        confirmButtonText: "Si, reiniciar",
        cancelButtonText: "No, cancelar",
        denyButtonText: `No, cancelar`,
    }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            axios
                .post(route("puntuacions.reiniciar"), {
                    user_id,
                })
                .then((response) => {
                    cargarPuntuaciones();
                    Swal.fire({
                        icon: "success",
                        title: "Correcto",
                        text: `Proceso realizado`,
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: `Aceptar`,
                    });
                });
        }
    });
};
onMounted(async () => {
    cargarPuntuaciones();
});
onBeforeUnmount(() => {});
</script>
<template>
    <Head title="Puntuaciones"></Head>

    <!-- BEGIN breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:;">Inicio</a></li>
        <li class="breadcrumb-item active">Puntuaciones</li>
    </ol>
    <!-- END breadcrumb -->
    <!-- BEGIN page-header -->
    <h1 class="page-header">Puntuaciones</h1>
    <!-- END page-header -->

    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="input-group">
                <input
                    type="text"
                    class="form-control"
                    placeholder="Nro. de C.I."
                    v-model="search"
                    @keyup="buscarPuntuacion"
                />
                <button
                    class="append btn btn-default"
                    @click.prevent="cargarPuntuaciones"
                >
                    <i class="fa fa-search"></i>
                </button>
            </div>
            <p v-if="search.trim() != ''" class="text-white">
                Filtrando por: <span>{{ search }}</span>
            </p>
        </div>
        <div class="col-md-12" v-for="item in listPuntuaciones">
            <!-- BEGIN panel -->
            <div class="panel panel-inverse">
                <!-- BEGIN panel-body -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row mb-1">
                                <div class="col-md-3 col-sm-4 text-right">
                                    <b>Puntaje:</b>
                                </div>
                                <div class="col-md-9 col-sm-8">
                                    <span
                                        class="badge bg-navy"
                                        style="font-size: 1.1em"
                                        >{{ item.puntuacion ?? 0 }}</span
                                    >
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
                        <div class="col-md-4">
                            <button
                                class="btn btn-success"
                                @click="reiniciarPuntaje(item.id)"
                            >
                                <i class="fa fa-sync"></i> Reinciar
                            </button>
                        </div>
                    </div>
                </div>
                <!-- END panel-body -->
            </div>
            <!-- END panel -->
        </div>
    </div>
</template>
