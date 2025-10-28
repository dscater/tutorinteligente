<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { useCuestionarios } from "@/composables/cuestionarios/useCuestionarios";
import { watch, ref, computed, defineEmits, onMounted, nextTick } from "vue";
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

const { oCuestionario, limpiarCuestionario } = useCuestionarios();
const accion = ref(props.accion_dialog);
const dialog = ref(props.open_dialog);
let form = useForm(oCuestionario.value);
watch(
    () => props.open_dialog,
    async (newValue) => {
        dialog.value = newValue;
        if (dialog.value) {
            cargaSecciones();
            document
                .getElementsByTagName("body")[0]
                .classList.add("modal-open");
            form = useForm(oCuestionario.value);
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
const cargaSecciones = () => {
    axios.get(route("secciones.index")).then((response) => {
        listSecciones.value = response.data;
    });
};

const tituloDialog = computed(() => {
    return accion.value == 0
        ? `<i class="fa fa-plus"></i> Nuevo Cuestionario`
        : `<i class="fa fa-edit"></i> Editar Cuestionario`;
});

const enviarFormulario = () => {
    let url =
        form["_method"] == "POST"
            ? route("cuestionarios.store")
            : route("cuestionarios.update", form.id);

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
            limpiarCuestionario();
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
                            <div class="col-md-12">
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
                                <label class="required">Pregunta</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.pregunta,
                                    }"
                                    v-model="form.pregunta"
                                />

                                <ul
                                    v-if="form.errors?.pregunta"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.pregunta }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6 mt-2">
                                <label class="required">Respuesta 1</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.resp1,
                                    }"
                                    v-model="form.resp1"
                                />

                                <ul
                                    v-if="form.errors?.resp1"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.resp1 }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6 mt-2">
                                <label class="required">Respuesta 2</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.resp2,
                                    }"
                                    v-model="form.resp2"
                                />
                                <ul
                                    v-if="form.errors?.resp2"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.resp2 }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6 mt-2">
                                <label class="required">Respuesta 3</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.resp3,
                                    }"
                                    v-model="form.resp3"
                                />

                                <ul
                                    v-if="form.errors?.resp3"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.resp3 }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6 mt-2">
                                <label class="required">Respuesta 4</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.resp4,
                                    }"
                                    v-model="form.resp4"
                                />
                                <ul
                                    v-if="form.errors?.resp4"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.resp4 }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6 mt-2">
                                <label class="required"
                                    >Respuesta Correcta</label
                                >
                                <input
                                    type="number"
                                    class="form-control"
                                    :class="{
                                        'parsley-error': form.errors?.correcta,
                                    }"
                                    placeholder="Ingresar el nro. de respuesta correcta (1-4)"
                                    v-model="form.correcta"
                                />
                                <ul
                                    v-if="form.errors?.correcta"
                                    class="parsley-errors-list filled"
                                >
                                    <li class="parsley-required">
                                        {{ form.errors?.correcta }}
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
