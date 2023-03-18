<template>
    <DataTable :value="sites" tableStyle="min-width: 50rem">
        <Column field="name" header="Nom"></Column>
        <Column field="url" header="URL">
            <template #body="slotProps">
                <a :href="slotProps.data.url" target="_blank">{{ slotProps.data.url }}</a>
            </template>
        </Column>
        <Column field="actif" header="Actif">
            <template #body="slotProps">
                <Tag :value="slotProps.data.actif ? 'actif' : 'inactif'" :severity="slotProps.data.actif ? 'success' : 'danger'" />
            </template>
        </Column>
        <Column field="php" header="Version PHP">
            <template #body="slotProps">
                <Tag :value="slotProps.data.php " :severity="'info'" />
            </template>
        </Column>
    </DataTable>

</template>

<script>
    import axios from "axios";

    export default {
        props: {
            env: {
                type: String,
                default: "dev"
            }
        },
        data() {
            return {
                sites: null
            };
        },
        mounted() {
            axios.get("/api/envs/"+this.env+"/sites/").then(response => {
                this.sites = response.data;
            });
        }
    }
</script>
