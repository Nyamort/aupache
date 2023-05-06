<template>
    <div class="content">
        <h3>PHP</h3>
        <div class="phpList">
            <multi-select display="chip" v-model="versionsSelected" :options="versions" :maxSelectedLabels="3"></multi-select>
            <Button label="Enregistrer" icon="pi pi-save" class="p-button-secondary" @click="savePhpVersions" />
        </div>
        <div class="extensions">
            <dropdown @change="loadExtensions" v-model="versionSelected" :options="versionsSelected"></dropdown>
            <div class="extensionsList">
                <div v-for="extension in extensions" :key="extension">
                    <Checkbox v-model="extensionsSelected" :value="extension" :inputId="extension"></Checkbox>
                    <label :for="extension">{{ extension }}</label>
                </div>
            </div>
            <Button label="Enregistrer" icon="pi pi-save" class="p-button-secondary" @click="savePhpExtensions" />
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
            defaultVersions: [],
            versionSelected: null,
            extensions: [],
            extensionsSelected: [],
            defaultExtensions: [],

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
        },
        loadExtensions() {
            if(this.versionSelected == null) return;
            axios.get("/api/php/"+this.versionSelected+"/extensions").then(response => {
                this.extensions = response.data.downloadable;
                this.extensionsSelected = response.data.installed;
                this.defaultExtensions = response.data.installed;
            });
        },
        savePhpExtensions() {
            let extensionsSelected = this.extensionssSelected.value;
            let installed = extensionsSelected.filter(extension => !this.defaultExtensions.includes(extension));
            let uninstalled = this.defaultExtensions.filter(extension => !extensionsSelected.includes(extension));

            installed.forEach(extension => {
                axios.post("/api/php/"+this.versionSelected+"/extensions/"+extension+"/install").then(response => {
                    console.log(response.data);
                });
            });

            uninstalled.forEach(extension => {
                axios.post("/api/php/"+this.versionSelected+"/extensions/"+extension+"/uninstall").then(response => {
                    console.log(response.data);
                });
            });
        }
    }
}
</script>

<style scoped>
    .phpList{
        display: flex;
        flex-direction: row;
        gap: 1rem;
    }

    .extensions{
        display: flex;
        flex-direction: column;
        align-items: start;
        gap: 1rem;
    }

    .extensionsList{
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .content{
        display: flex;
        flex-direction: column;
        gap: 1rem;
        padding: 15px;
    }
</style>