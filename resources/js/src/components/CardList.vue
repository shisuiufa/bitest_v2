<template>
    <div class="cards row g-4">
        <div class="col-12 col-lg-4" v-for="test in tests" :key="test.id">
            <card-item class="cards__item" :test="test"></card-item>
        </div>
    </div>
</template>

<script>
import CardItem from "@/components/CardItem.vue";
export default {
    name: "CardList",
    components: {
        CardItem,
    },
    props: {
        tests: {
            type: Array,
            required: true,
        },
    },
};
</script>

<style scoped lang="scss"></style>
