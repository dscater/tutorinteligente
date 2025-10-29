<script setup>
import { useForm, usePage, Link } from "@inertiajs/vue3";
import { watch, ref, computed, defineEmits, onMounted, nextTick } from "vue";
const props = defineProps({
    oSeccion: {
        type: Object,
        default: null,
    },
    open_dialog: {
        type: Boolean,
        default: false,
    },
    accion_dialog: {
        type: Number,
        default: 0,
    },
});

const correctas = ref(0);
const accion = ref(props.accion_dialog);
const dialog = ref(props.open_dialog);
const seccion = ref(props.oSeccion);

watch(
    () => props.oSeccion,
    async (newValue) => {
        seccion.value = newValue;
        cargarListaCuestionariosSeccion();
    }
);

watch(
    () => props.open_dialog,
    async (newValue) => {
        dialog.value = newValue;
        if (dialog.value) {
            cargarListaCuestionariosSeccion();
            document
                .getElementsByTagName("body")[0]
                .classList.add("modal-open");
        }
    }
);
watch(
    () => props.accion_dialog,
    (newValue) => {
        accion.value = newValue;
    }
);

const tituloDialog = computed(() => {
    return accion.value == 0
        ? `<i class="fa fa-plus"></i> Lista de cuestionarios`
        : `<i class="fa fa-edit"></i> Editar Cuestionario`;
});

const listCuestionariosSeccion = ref([]);
const cargarListaCuestionariosSeccion = () => {
    axios
        .get(route("cuestionarios.listado"), {
            params: {
                seccion: seccion.value?.value,
                random: true,
            },
        })
        .then((response) => {
            listCuestionariosSeccion.value = response.data.cuestionarios;

            // Inicializar array de cuestionarios
            FormCuestionario.cuestionarios = listCuestionariosSeccion.value.map(
                (item) => ({
                    cuestionario_id: item.id,
                    valor: "",
                })
            );
        });
};

const emits = defineEmits(["cerrar-dialog", "envio-formulario"]);

watch(dialog, (newVal) => {
    if (!newVal) {
        emits("cerrar-dialog");
    }
});

const cerrarDialog = () => {
    dialog.value = false;
    document.getElementsByTagName("body")[0].classList.remove("modal-open");
};

let FormCuestionario = useForm({
    cuestionarios: [],
});

const enviarFormulario = () => {
    let url = route("cuestionario_estudiantes.store");
    FormCuestionario.post(url, {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: (response) => {
            dialog.value = false;
            const success =
                response.props.flash.bien ?? "Proceso realizado con éxito";
            Swal.fire({
                icon: "success",
                title: "Correcto",
                html: success,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            emits("envio-formulario");
        },
        onError: (err) => {
            console.log("ERROR");
            console.log(err);
            const error = err.props.flash.bien ?? "Proceso realizado con éxito";
            Swal.fire({
                icon: "info",
                title: "Error",
                text: `${
                    error
                        ? error
                        : err.error
                        ? err.error
                        : "Hay errores en el formulario"
                }`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
        },
    });
};

onMounted(() => {});
</script>

<template>
    <div
        class="modal fade"
        :class="{
            show: dialog,
        }"
        id="modal-dialog-form"
        :style="{
            display: dialog ? 'block' : 'none',
        }"
    >
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h4 class="modal-title" v-html="tituloDialog"></h4>
                    <button
                        type="button"
                        class="btn-close"
                        @click="cerrarDialog()"
                    ></button>
                </div>
                <div class="modal-body">
                    <template v-if="listCuestionariosSeccion.length > 0">
                        <form @submit.prevent="enviarFormulario()">
                            <div
                                class="row border-bottom mb-3"
                                v-for="(
                                    item, index
                                ) in listCuestionariosSeccion"
                            >
                                <div class="col-12">
                                    <p class="font-weight-bold h5">
                                        {{ index + 1 }}) {{ item.pregunta }}
                                    </p>
                                </div>
                                <div class="col-12">
                                    <div class="row mb-2">
                                        <div class="col-md-3">
                                            <label
                                                class="h-100 d-flex align-center gap-1"
                                                :for="'1cues' + item.id"
                                            >
                                                <input
                                                    type="radio"
                                                    :id="'1cues' + item.id"
                                                    v-model="
                                                        FormCuestionario
                                                            .cuestionarios[
                                                            index
                                                        ]['valor']
                                                    "
                                                    :value="1"
                                                />
                                                {{ item.resp1 }}</label
                                            >
                                        </div>
                                        <div class="col-md-3">
                                            <label
                                                class="h-100 d-flex align-center gap-1"
                                                :for="'2cues' + item.id"
                                            >
                                                <input
                                                    type="radio"
                                                    :id="'2cues' + item.id"
                                                    v-model="
                                                        FormCuestionario
                                                            .cuestionarios[
                                                            index
                                                        ]['valor']
                                                    "
                                                    :value="2"
                                                />
                                                {{ item.resp2 }}</label
                                            >
                                        </div>
                                        <div class="col-md-3">
                                            <label
                                                class="h-100 d-flex align-center gap-1"
                                                :for="'3cues' + item.id"
                                            >
                                                <input
                                                    type="radio"
                                                    :id="'3cues' + item.id"
                                                    v-model="
                                                        FormCuestionario
                                                            .cuestionarios[
                                                            index
                                                        ]['valor']
                                                    "
                                                    :value="3"
                                                />
                                                {{ item.resp3 }}</label
                                            >
                                        </div>
                                        <div class="col-md-3">
                                            <label
                                                class="h-100 d-flex align-center gap-1"
                                                :for="'4cues' + item.id"
                                            >
                                                <input
                                                    type="radio"
                                                    :id="'4cues' + item.id"
                                                    v-model="
                                                        FormCuestionario
                                                            .cuestionarios[
                                                            index
                                                        ]['valor']
                                                    "
                                                    :value="4"
                                                />
                                                {{ item.resp4 }}</label
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </template>
                    <template v-else>
                        <div class="row">
                            <div class="col-12">
                                <p class="h3">No se encontrarón registros</p>
                            </div>
                        </div>
                    </template>
                </div>
                <div class="modal-footer">
                    <a
                        href="javascript:;"
                        class="btn btn-white"
                        @click="cerrarDialog()"
                        ><i class="fa fa-times"></i> Cerrar</a
                    >
                    <button
                        type="button"
                        @click="enviarFormulario()"
                        class="btn btn-primary"
                    >
                        <i class="fa fa-paper-plane"></i>
                        Envíar Cuestionario
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
