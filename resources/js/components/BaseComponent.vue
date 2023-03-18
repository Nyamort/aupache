<template>
    <div>
        <h1>Configuration serveur - Internet Evolution</h1>
        <Dropdown v-model="selectedEnv" :options="envs" optionLabel="name" placeholder="Environment" class="w-full md:w-14rem" />
        <SitesComponent v-if="selectedEnv" :env="selectedEnv.name" />
    </div>
</template>

<script>
import axios from "axios";
import SitesComponent from "./SitesComponent.vue";

export default {
    name: "BaseComponent",
    components: {SitesComponent},

    data() {
        return {
            selectedEnv: null,
            envs: []
        };
    },
    mounted() {
        axios.get("/api/envs").then(response => {
            this.envs = response.data;
        });
    }
}
</script>

<style scoped>

</style>
