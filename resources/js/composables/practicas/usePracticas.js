import { onMounted, ref } from "vue";

const oPractica = ref({
    id: 0,
    nivel: "",
    seccion: "",
    descripcion: "",
    codigo: "",
    lineas: "",
    _method: "POST",
});

export const usePracticas = () => {
    const setPractica = (item = null) => {
        if (item) {
            oPractica.value.id = item.id;
            oPractica.value.nivel = item.nivel;
            oPractica.value.seccion = item.seccion;
            oPractica.value.descripcion = item.descripcion;
            oPractica.value.codigo = item.codigo;
            oPractica.value.lineas = item.lineas;
            oPractica.value._method = "PUT";
            return oPractica;
        }
        return false;
    };

    const limpiarPractica = () => {
        oPractica.value.id = 0;
        oPractica.value.nivel = "";
        oPractica.value.seccion = "";
        oPractica.value.descripcion = "";
        oPractica.value.codigo = "";
        oPractica.value.lineas = "";
        oPractica.value._method = "POST";
    };

    onMounted(() => {});

    return {
        oPractica,
        setPractica,
        limpiarPractica,
    };
};
