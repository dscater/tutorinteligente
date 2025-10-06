import { defineStore } from "pinia";

export const useConfiguracionStore = defineStore("configuracion", {
    state: () => ({
        oConfiguracion: {
            nombre_sistema: "TUTORINTELIGENTE",
            alias: "TI",
            // appends
            url_logo: "",
        },
    }),
    actions: {
        setInstiticion(value) {
            this.oConfiguracion = value;
        },
    },
});
