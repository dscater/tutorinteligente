<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { watch, ref, computed, defineEmits, onMounted, nextTick } from "vue";
import * as monaco from "monaco-editor";
const props = defineProps({
    oPractica: {
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
const UserPractica = useForm({
    practica_id: 0,
    codigo: "",
    correcto: "",
});
const accion = ref(props.accion_dialog);
const dialog = ref(props.open_dialog);
const practica = ref(props.oPractica);
const codigoCorrecto = ref(props.oPractica?.codigo.trim().split("\n"));

watch(
    () => props.oPractica,
    async (newValue) => {
        practica.value = newValue;
        UserPractica.practica_id = newValue.id;
        codigoCorrecto.value = newValue.codigo;
        totalLineas.value = codigoCorrecto.value.split("\n").length;
        await verificaUserPractica();
        setTimeout(() => {
            cargarMonaco();
        }, 300);
    }
);

watch(
    () => props.open_dialog,
    async (newValue) => {
        dialog.value = newValue;
        if (dialog.value) {
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

const { flash } = usePage().props;

const tituloDialog = computed(() => {
    return accion.value == 0
        ? `<i class="fa fa-plus"></i> Resolver Práctica`
        : `<i class="fa fa-edit"></i> Editar Práctica`;
});

const emits = defineEmits(["cerrar-dialog", "envio-formulario"]);

watch(dialog, (newVal) => {
    if (!newVal) {
        emits("cerrar-dialog");
    }
});

const cerrarDialog = () => {
    dialog.value = false;
    document.getElementsByTagName("body")[0].classList.remove("modal-open");
    totalLineas.value = 0;
    correctas.value = 0;
    UserPractica.reset();
};

const contenedorEditor = ref(null);
let editor = null;
const totalLineas = ref(0);
const cargarMonaco = async () => {
    await nextTick();
    if (editor) {
        editor.deltaDecorations(currentDecorations, []);
        editor.dispose();
        editor = null;
    }

    editor = monaco.editor.create(contenedorEditor.value, {
        value: UserPractica.codigo ? UserPractica.codigo : "",
        language: "java",
        theme: "vs-dark",
        automaticLayout: true,
        minimap: { enabled: false },
    });

    // SI EXISTE EL REGISTRO DEL USER
    if (UserPractica.codigo) {
        totalLineas.value = UserPractica.codigo.split("\n").length;
        // VALIDAR CÓDIGO LÍNEA A LÍNEA -> convertir a array de líneas
        const lineasUsuario = UserPractica.codigo.split("\n");
        const lineasCorrectos = codigoCorrecto.value.split("\n");
        let count = 0;
        lineasUsuario.forEach((linea, i) => {
            const esperado = (lineasCorrectos?.[i] || "").trim();
            if (linea.trim() === esperado && esperado !== "") count++;
        });
        correctas.value = count;
        // Resaltar visualmente las líneas correctas o incorrectas
        UserPractica.correcto = 0;
        if (correctas.value == totalLineas.value) {
            UserPractica.correcto = 1;
        }
        resaltarLineas(lineasUsuario);
    }

    // DETECTAR CAMBIOS EDITOR
    editor.onDidChangeModelContent(() => {
        UserPractica.codigo = editor.getValue();
        totalLineas.value = UserPractica.codigo.split("\n").length;

        // VALIDAR CÓDIGO LÍNEA A LÍNEA -> convertir a array de líneas
        const lineasUsuario = UserPractica.codigo.split("\n");
        const lineasCorrectos = codigoCorrecto.value.split("\n");
        let count = 0;
        lineasUsuario.forEach((linea, i) => {
            const esperado = (lineasCorrectos?.[i] || "").trim();
            if (linea.trim() === esperado && esperado !== "") count++;
        });
        correctas.value = count;
        // Resaltar visualmente las líneas correctas o incorrectas
        UserPractica.correcto = 0;
        if (correctas.value == totalLineas.value) {
            UserPractica.correcto = 1;
        }
        resaltarLineas(lineasUsuario);
    });
};

let currentDecorations = [];
const resaltarLineas = (lineasUsuario) => {
    const decorations = [];
    const lineasCorrectos = codigoCorrecto.value.split("\n");

    lineasUsuario.forEach((linea, i) => {
        const esperado = (lineasCorrectos[i] || "").trim();
        const esCorrecta = linea.trim() === esperado && esperado !== "";

        decorations.push({
            range: new monaco.Range(i + 1, 1, i + 1, 1),
            options: {
                isWholeLine: true,
                className: esCorrecta ? "bg-linea-correcto" : "bg-linea-error",
            },
        });
    });
    currentDecorations = editor.deltaDecorations(
        currentDecorations,
        decorations
    );
};

const enviarFormulario = () => {
    let url = route("practica_estudiantes.store");
    UserPractica.post(url, {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            dialog.value = false;
            Swal.fire({
                icon: "success",
                title: "Correcto",
                text: `${flash.bien ? flash.bien : "Proceso realizado"}`,
                confirmButtonColor: "#3085d6",
                confirmButtonText: `Aceptar`,
            });
            emits("envio-formulario");
        },
        onError: (err) => {
            console.log("ERROR");
            Swal.fire({
                icon: "info",
                title: "Error",
                text: `${
                    flash.error
                        ? flash.error
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

const verificaUserPractica = () => {
    axios
        .get(route("practica_estudiantes.getPractica", practica.value.id ?? 0))
        .then((response) => {
            const existePractica = response.data;
            if (existePractica && existePractica.id) {
                UserPractica.practica_id = existePractica.practica_id;
                UserPractica.codigo = existePractica.codigo;
                UserPractica.correcto = existePractica.correcto;
            }
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
                    <form @submit.prevent="enviarFormulario()">
                        <div class="row">
                            <div class="col-12">
                                <p>{{ practica?.descripcion }}</p>
                            </div>
                            <div class="p-12 space-y-4">
                                <h4 class="text-xl font-bold">Código</h4>
                                <div
                                    ref="contenedorEditor"
                                    class="border rounded"
                                    style="height: 400px"
                                ></div>

                                <div class="row">
                                    <p class="text-sm mb-0">
                                        Línea correctas: {{ correctas }}
                                    </p>
                                    <p class="text-sm font-weight-bold">
                                        Total Líneas: {{ totalLineas }}
                                    </p>
                                </div>
                                <ul
                                    v-if="UserPractica.errors?.codigo"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ UserPractica.errors?.codigo }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </form>
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
                        <i class="fa fa-save"></i>
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
