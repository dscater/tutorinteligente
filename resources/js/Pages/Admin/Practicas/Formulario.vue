<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { usePracticas } from "@/composables/practicas/usePracticas";
import { watch, ref, computed, defineEmits, onMounted, nextTick } from "vue";
import * as monaco from "monaco-editor";
const props = defineProps({
    open_dialog: {
        type: Boolean,
        default: false,
    },
    accion_dialog: {
        type: Number,
        default: 0,
    },
});

const { oPractica, limpiarPractica } = usePracticas();
const accion = ref(props.accion_dialog);
const dialog = ref(props.open_dialog);
let form = useForm(oPractica.value);
watch(
    () => props.open_dialog,
    async (newValue) => {
        dialog.value = newValue;
        if (dialog.value) {
            cargarMonaco();
            cargarListas();
            document
                .getElementsByTagName("body")[0]
                .classList.add("modal-open");
            form = useForm(oPractica.value);
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

const listSecciones = ref([]);
const listNivels = ref([]);
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

const tituloDialog = computed(() => {
    return accion.value == 0
        ? `<i class="fa fa-plus"></i> Nueva Práctica`
        : `<i class="fa fa-edit"></i> Editar Práctica`;
});

const enviarFormulario = () => {
    // form.codigo = editor.getValue();
    console.log(form.codigo);
    let url =
        form["_method"] == "POST"
            ? route("practicas.store")
            : route("practicas.update", form.id);

    form.post(url, {
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
            limpiarPractica();
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

let editor = null;
const totalLineas = ref(0);
const cargarMonaco = async () => {
    await nextTick();
    if (editor) {
        editor.dispose();
        editor = null;
    }
    editor = monaco.editor.create(document.getElementById("editor"), {
        value: form.id ? form.codigo : "",
        language: "java",
        theme: "vs-dark",
        automaticLayout: true,
        minimap: { enabled: false },
    });

    // Contar líneas iniciales
    const codigo = editor.getValue();
    totalLineas.value = codigo.split("\n").length;
    form.lineas = totalLineas.value;

    editor.onDidChangeModelContent(() => {
        const codigo = editor.getValue();
        form.codigo = codigo;
        totalLineas.value = codigo.split("\n").length;
        form.lineas = totalLineas.value;
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
                            <div class="col-md-6">
                                <label class="required">Selecionar Nivel</label>
                                <el-select
                                    class="w-100"
                                    :class="{
                                        'parsley-error': form.errors?.nivel,
                                    }"
                                    v-model="form.nivel"
                                    placeholder="- Seleccione -"
                                    filterable
                                >
                                    <el-option
                                        v-for="item in listNivels"
                                        :value="item.value"
                                        :label="item.label"
                                    ></el-option>
                                </el-select>
                                <ul
                                    v-if="form.errors?.nivel"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.nivel }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <label class="required"
                                    >Selecionar sección</label
                                >
                                <el-select
                                    class="w-100"
                                    :class="{
                                        'parsley-error': form.errors?.seccion,
                                    }"
                                    v-model="form.seccion"
                                    placeholder="- Seleccione -"
                                    filterable
                                >
                                    <el-option
                                        v-for="item in listSecciones"
                                        :value="item.value"
                                        :label="item.label"
                                    ></el-option>
                                </el-select>
                                <ul
                                    v-if="form.errors?.seccion"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.seccion }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-12 mt-2">
                                <label class="required">Descripción</label>
                                <el-input
                                    type="textarea"
                                    :class="{
                                        'parsley-error':
                                            form.errors?.descripcion,
                                    }"
                                    v-model="form.descripcion"
                                    autosize
                                >
                                </el-input>
                                <ul
                                    v-if="form.errors?.descripcion"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.descripcion }}
                                    </li>
                                </ul>
                            </div>
                            <div class="p-12 space-y-4">
                                <h1 class="text-xl font-bold">Código</h1>
                                <div
                                    id="editor"
                                    class="border rounded"
                                    style="height: 400px"
                                ></div>

                                <div class="row">
                                    <p class="text-sm">
                                        Líneas: {{ totalLineas }}
                                    </p>
                                </div>

                                <ul
                                    v-if="form.errors?.codigo"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.codigo }}
                                    </li>
                                </ul>

                                <ul
                                    v-if="form.errors?.lineas"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.lineas }}
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
