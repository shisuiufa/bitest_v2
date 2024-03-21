<template>
    <div class="test" v-if="this.test">
        <accordion-menu :test="this.test" :event="'edit'"></accordion-menu>
    </div>
</template>

<script>
import AccordionMenu from "@/components/AccordionMenu.vue";
import axios from "axios";

export default {
    name: "EditView",
    components: {
        AccordionMenu,
    },
    data() {
        return {
            test: null,
        }
    },
    mounted() {
        const testId = this.$route.params.id;
        this.getTest(testId);
    },
    methods: {
        getTest(testId) {
            axios.get(`/api/moder/tests/${testId}`)
                .then(res => {
                    this.test = res.data.data;
                    console.log(this.test)
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
