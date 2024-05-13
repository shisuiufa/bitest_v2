<template>
    <Panel class="p-2">
        <div class="test">
            <AccordionMenu :event="'create'"></AccordionMenu>
        </div>
    </Panel>
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
