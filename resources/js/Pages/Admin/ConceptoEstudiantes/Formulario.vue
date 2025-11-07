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
const indexConcepto = ref(0);
watch(
    () => props.oSeccion,
    async (newValue) => {
        seccion.value = newValue;
        cargarListaConceptos();
    }
);

watch(
    () => props.open_dialog,
    async (newValue) => {
        indexConcepto.value = 0;
        dialog.value = newValue;
        if (dialog.value) {
            cargarListaConceptos();
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

const disminuir = () => {
    indexConcepto.value--;
};
const aumentar = () => {
    indexConcepto.value++;
};

const tituloDialog = computed(() => {
    return accion.value == 0
        ? `<i class="fa fa-list-alt"></i> Conceptos`
        : `<i class="fa fa-edit"></i> Editar Cuestionario`;
});

const listConceptos = ref([]);
const cargarListaConceptos = () => {
    axios
        .get(route("conceptos.listado"), {
            params: {
                seccion: seccion.value?.value,
            },
        })
        .then((response) => {
            listConceptos.value = response.data.conceptos;
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
    conceptos: [],
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
        id="modal-form-concepto"
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
                    <template v-if="listConceptos.length > 0">
                        <div class="row">
                            <div class="col-12">
                                <h2
                                    class="text-center h1"
                                    v-text="listConceptos[indexConcepto].titulo"
                                ></h2>
                            </div>
                            <div class="col-12">
                                <div
                                    v-html="
                                        listConceptos[indexConcepto].descripcion
                                    "
                                ></div>
                            </div>
                            <div
                                class="col-12 text-center"
                                v-if="listConceptos[indexConcepto].url"
                            >
                                <a
                                    :href="listConceptos[indexConcepto].url"
                                    target="_blank"
                                    class="btn btn-danger"
                                    >Video de youtube</a
                                >
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12"></div>
                        </div>
                    </template>
                    <template v-else>
                        <div class="row">
                            <div class="col-12">
                                <p class="h3">No se encontrarón registros</p>
                            </div>
                        </div>
                    </template>
                </div>
                <div
                    class="modal-footer justify-content-center"
                    v-if="listConceptos.length > 0"
                >
                    <button
                        class="btn btn-default"
                        v-if="indexConcepto > 0"
                        @click.prevent="disminuir"
                    >
                        <i class="fa fa-caret-left"></i> Anterior
                    </button>
                    <button
                        class="btn btn-default"
                        v-if="indexConcepto < listConceptos.length - 1"
                        @click.prevent="aumentar"
                    >
                        <i class="fa fa-caret-right"></i> Siguiente
                    </button>
                </div>
                <div class="modal-footer">
                    <a
                        href="javascript:;"
                        class="btn btn-white"
                        @click="cerrarDialog()"
                        ><i class="fa fa-times"></i> Cerrar</a
                    >
                </div>
            </div>
        </div>
    </div>
</template>
