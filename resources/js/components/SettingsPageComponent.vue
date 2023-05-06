<template>
    <div>
        <h3>PHP</h3>
        <div>
            <multi-select display="chip" v-model="versionsSelected" :options="versions" :maxSelectedLabels="3"></multi-select>
            <Button label="Enregistrer" icon="pi pi-save" class="p-button-secondary" @click="savePhpVersions" />
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "Settings",
    data() {
        return {
            versions: [],
            versionsSelected: [],
            defaultVersions: []
        };
    },
    mounted() {
        axios.get("/api/php").then(response => {
            this.versions = response.data.downloadable;
            this.versionsSelected = response.data.installed;
            this.defaultVersions = response.data.installed;
        });
    },
    methods: {
        savePhpVersions() {
            let installed = this.versionsSelected.filter(version => !this.defaultVersions.includes(version));
            let uninstalled = this.defaultVersions.filter(version => !this.versionsSelected.includes(version));

            installed.forEach(version => {
                axios.post("/api/php/"+version+"/install").then(response => {
                    console.log(response.data);
                });
            });

            uninstalled.forEach(version => {
                axios.post("/api/php/"+version+"/uninstall").then(response => {
                    console.log(response.data);
                });
            });
        }
    }
}
</script>

<style scoped>

</style>