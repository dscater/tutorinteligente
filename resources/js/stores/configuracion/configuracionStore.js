import { defineStore } from "pinia";

export const useConfiguracionStore = defineStore("configuracion", {
    state: () => ({
        oConfiguracion: {
            nombre_sistema: "TUTORINTELIGENTE",
            alias: "SO",
            // appends
            url_logo: "",
        },
    }),
    actions: {
        setConfiguracion(value) {
            this.oConfiguracion = value;
        },
    },
});
