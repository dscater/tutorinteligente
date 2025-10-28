import { onMounted, ref } from "vue";

const oCuestionario = ref({
    id: 0,
    seccion: "",
    pregunta: "",
    resp1: "",
    resp2: "",
    resp3: "",
    resp4: "",
    correcta: "",
    _method: "POST",
});

export const useCuestionarios = () => {
    const setCuestionario = (item = null) => {
        if (item) {
            oCuestionario.value.id = item.id;
            oCuestionario.value.seccion = item.seccion;
            oCuestionario.value.pregunta = item.pregunta;
            oCuestionario.value.resp1 = item.resp1;
            oCuestionario.value.resp2 = item.resp2;
            oCuestionario.value.resp3 = item.resp3;
            oCuestionario.value.resp4 = item.resp4;
            oCuestionario.value.correcta = item.correcta;
            oCuestionario.value._method = "PUT";
            return oCuestionario;
        }
        return false;
    };

    const limpiarCuestionario = () => {
        oCuestionario.value.id = 0;
        oCuestionario.value.seccion = "";
        oCuestionario.value.pregunta = "";
        oCuestionario.value.resp1 = "";
        oCuestionario.value.resp2 = "";
        oCuestionario.value.resp3 = "";
        oCuestionario.value.resp4 = "";
        oCuestionario.value.correcta = "";
        oCuestionario.value._method = "POST";
    };

    onMounted(() => {});

    return {
        oCuestionario,
        setCuestionario,
        limpiarCuestionario,
    };
};
