<template>
    <div class="test">
        <AccordionMenu :event="'create'"></AccordionMenu>
    </div>
</template>

<script>
    import AccordionMenu from "@/components/AccordionMenu.vue";
    export default {
        name: "CreateView",
        components: {
            AccordionMenu
        },
    }
</script>

<style scoped lang="scss">

</style>
