<template>
    <div class="access">
        <div class="col-12 mb-3">
            <label class="form-label">Для кого</label>
            <custom-select :item="accessFor" @select-item="selectedAccessFor"></custom-select>
        </div>
        <div class="col-12 mb-3">
            <label class="form-label">Пользователи/группы</label>
            <custom-select :item="selectedItems" @select-item="(item) => selectedItems.selected = item">
            </custom-select>
        </div>
        <div class="col-12">
            <ui-button @click="addAccess()" class="access__btn">Добавить</ui-button>
        </div>
    </div>
</template>

<script>
 import CustomSelect from "@/components/UI/CustomSelect.vue";
 import UiButton from "@/components/UI/UiButton.vue";
 import {ref} from "vue";
 import {mapActions, mapGetters} from "vuex";

 export default {
     name: "AccordionAccess",
     components: {UiButton, CustomSelect},
     data(){
         return {
             dataLoaded: false,
             accesses: [],
             accessFor: {
                 selected: ref(null),
                 options: [
                     {id: 1, name: 'Группы', value: 'groups'},
                     {id: 2, name: 'Пользователи', value: 'users'},
                 ],
             },
             selectedItems: {
                 selected: ref(null),
                 options: [
                     {id: 1, name: "Клочков Роман"},
                     {id: 2, name: "Клочков Роман2"}
                 ]
             }
         }
     },
     props: {
         accessesTest: {
             required: true,
         }
     },
     mounted() {
         this.dataLoaded = true;
         this.accesses = this.accessesTest;
     },
     watch: {
         accesses:{
             deep: true,
             handler(newValue, oldValue) {
                 if (this.dataLoaded){
                     this.updateAccesses(newValue)
                 }
             },
         }
     },
     methods: {
         ...mapActions(['updateAccesses']),
         selectedAccessFor(item){
             this.accessFor.selected = item;
         },
         addAccess(){
             const access = {
                 id: this.selectedItems.selected.id,
                 type: this.accessFor.selected.value,
             }
             this.accesses.push(access);
         }
     }
 }
</script>

<style scoped lang="scss">

</style>
