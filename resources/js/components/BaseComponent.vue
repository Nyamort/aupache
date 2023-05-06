<template>
    <div>
        <h1>Configuration serveur - Internet Evolution</h1>
        <router-view></router-view>
    </div>
</template>

<script>
import axios from "axios";
import SitesComponent from "./SitesComponent.vue";

export default {
    name: "BaseComponent",
    components: {SitesComponent},
}
</script>

<style scoped>

</style>
