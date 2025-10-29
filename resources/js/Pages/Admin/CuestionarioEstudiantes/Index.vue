<script setup>
import { useApp } from "@/composables/useApp";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { ref, onMounted, onBeforeUnmount, nextTick } from "vue";
import Formulario from "./Formulario.vue";
// const { mobile, identificaDispositivo } = useMenu();
const { props: props_page } = usePage();
const auth = ref(props_page.auth);
const { setLoading } = useApp();
const props = defineProps({
    listSecciones: {
        type: Array,
        default: [],
    },
});

const accion_dialog = ref(0);
const open_dialog = ref(false);

const oSeccion = ref(null);
const oPuntuacion = ref(null);

const resolver = (item) => {
    accion_dialog.value = 0;
    open_dialog.value = true;
    oSeccion.value = item;
};

const getPuntuacionUser = () => {
    axios
        .get(
            route("cuestionario_estudiantes.getPuntuacion", auth.value?.user.id)
        )
        .then((response) => {
            oPuntuacion.value = response.data.puntuacion;
        });
};

onMounted(() => {
    getPuntuacionUser();
    setTimeout(() => {
        setLoading(false);
    }, 300);
});
</script>
<template>
    <Head title="Cuestionarios"></Head>

    <!-- BEGIN breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:;">Inicio</a></li>
        <li class="breadcrumb-item active">Cuestionarios</li>
    </ol>
    <!-- END breadcrumb -->
    <!-- BEGIN page-header -->
    <h1 class="page-header">Cuestionarios</h1>
    <!-- END page-header -->

    <div class="row mt-2">
        <div class="col-md-3 offset-md-9 mb-3">
            <div class="card bg-navy">
                <div class="card-body text-center pb-1 pt-1 font-weight-bold">
                    PUNTAJE ACTUAL:<br />
                    <h4 class="mb-0 h2">
                        {{ oPuntuacion ? oPuntuacion.puntuacion : 0 }}
                    </h4>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card mb-3" v-for="item in listSecciones">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <p class="mb-0 font-weight-bold h5">
                                {{ item.label }}
                            </p>
                        </div>
                        <div class="col-md-4">
                            <button
                                class="btn btn-success w-100"
                                @click="resolver(item)"
                            >
                                <i class="fa fa-list"></i> Resolver
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <Formulario
            :open_dialog="open_dialog"
            :accion_dialog="accion_dialog"
            :oSeccion="oSeccion"
            @envio-formulario="getPuntuacionUser"
            @cerrar-dialog="open_dialog = false"
        ></Formulario>
    </div>
</template>
