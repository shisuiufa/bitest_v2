<template>
    <table-app :items="this.results"></table-app>
</template>

<script>
import TableApp from "../../components/tables/TableApp.vue";

export default {
    name: 'ResultIndexView',
    components: {TableApp},
    data(){
        return {
            results: [],
        }
    },
    mounted() {
        this.getResult();
    },
    methods: {
        getResult(){
            axios.get('/api/results')
                .then(res => {
                   this.results = res.data.data;
                })
                .catch(err => {
                    console.log(err)
                })
        }
    }
}
</script>

<style scoped lang="scss">

</style>
