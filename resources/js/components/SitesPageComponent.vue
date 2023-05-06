<template>
    <div>
        <div class="flex flex-column gap-3">
            <div class="flex justify-content-between">
                <Dropdown v-model="selectedEnv" :options="envs" optionLabel="name" placeholder="Environment" class="w-full md:w-14rem" />
                <router-link to="/settings" class="p-button-secondary">
                    <Button label="Paramètres" icon="pi pi-cog" class="p-button-secondary"  />
                </router-link>
            </div>
            <SitesComponent v-if="selectedEnv" :env="selectedEnv.name" />
        </div>
    </div>
</template>

<script>
import SitesComponent from "./SitesComponent.vue";
import axios from "axios";

export default {
    name: "SitesPageComponent",
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