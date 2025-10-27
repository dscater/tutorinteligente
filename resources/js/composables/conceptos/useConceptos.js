import { onMounted, ref } from "vue";

const oConcepto = ref({
    id: 0,
    seccion: "",
    titulo: "",
    descripcion: "",
    url: "",
    _method: "POST",
});

export const useConceptos = () => {
    const setConcepto = (item = null) => {
        if (item) {
            oConcepto.value.id = item.id;
            oConcepto.value.seccion = item.seccion;
            oConcepto.value.titulo = item.titulo;
            oConcepto.value.descripcion = item.descripcion;
            oConcepto.value.url = item.url;
            oConcepto.value._method = "PUT";
            return oConcepto;
        }
        return false;
    };

    const limpiarConcepto = () => {
        oConcepto.value.id = 0;
        oConcepto.value.seccion = "";
        oConcepto.value.titulo = "";
        oConcepto.value.descripcion = "";
        oConcepto.value.url = "";
        oConcepto.value._method = "POST";
    };

    onMounted(() => {});

    return {
        oConcepto,
        setConcepto,
        limpiarConcepto,
    };
};
