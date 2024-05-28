<template>
    <div class="row">
        <div class="col-12">
            <div class="d-flex flex-column gap-2">
                <div class="d-block d-md-flex justify-content-between">
                    <div class="order-2 order-md-1 d-flex align-items-center justify-content-md-end gap-2 mb-2 mb-md-0">
                        <label class="cursor-pointer" for="published">Опубликовать</label>
                        <InputSwitch inputId="published" v-model="this.form.published"/>
                    </div>
                    <div class="order-1 order-md-2 d-flex flex-wrap gap-2 justify-content-between justify-content-md-end mb-2 mb-md-0">
                        <Button v-if="this.enableCache" label="Очистить" class="p-primary" @click="this.$emit('clear')" />
                        <Button label="Сохранить" class="p-primary" @click="this.$emit('submit')" />
                    </div>
                </div>
                <SelectImage class="w-100" style="height: 350px;" v-model="this.form.image"/>
                <div class="d-flex flex-column gap-2">
                    <label for="title">Заголовок</label>
                    <InputText id="title" v-model="this.form.title" placeholder="Заголовок теста"/>
                </div>
                <div class="d-flex flex-column gap-2">
                    <label for="desc">Описание</label>
                    <Textarea id="desc" v-model="this.form.desc" placeholder="Описание теста" rows="5" cols="30"/>
                </div>
                <div class="d-flex flex-column gap-2">
                    <label for="timeComplete">Время на выполнение теста</label>
                    <Dropdown v-model="this.form.time_complete" :options="this.timeComplete"
                              optionLabel="name" optionValue="value"
                              inputId="timeComplete"
                              placeholder="Неограниченно"/>
                </div>
                <div class="d-flex flex-column gap-2">
                    <label for="attempts">Количество попыток</label>
                    <Dropdown v-model="this.form.attempts" :options="this.attempts"
                              optionLabel="name" optionValue="value"
                              inputId="attempts"
                              placeholder="Неограниченно"/>
                </div>
                <div class="d-flex flex-column gap-2">
                    <label for="limitQuestions">Отображение вопросов</label>
                    <Dropdown v-model="this.form.limit_questions" :options="this.limitQuestions"
                              optionLabel="name" optionValue="value"
                              inputId="limitQuestions"
                              placeholder="Все"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import SelectImage from "@/components/UI/SelectImage.vue";
import {mapActions} from "vuex";

export default {
    name: "CreateTestForm",
    components: {SelectImage},
    props: ['form', 'enableCache'],
    data() {
        return {
            timeComplete: [
                {name: "Неограниченно", value: null},
                {name: "15 минут", value: 900},
                {name: "30 минут", value: 1800},
                {name: "1 час", value: 3600},
                {name: "2 час", value: 7200},
            ],
            attempts: [
                {name: "Неограниченно", value: null},
                {name: "1 попытка", value: 1},
                {name: "2 попытки", value: 2},
                {name: "3 попытки", value: 3},
            ],
            limitQuestions: [
                {name: "Все", value: null},
                {name: "10 вопросов", value: 10},
                {name: "20 вопросов", value: 20},
                {name: "30 вопросов", value: 30},
            ]
        }
    },
    methods: {
        ...mapActions(["updateInfo"]),
    },
    watch: {
        form: {
            handler(newValue) {
                if(this.enableCache){
                    const formForCache = { info :{...newValue} };
                    this.updateInfo([formForCache, 0])
                }
                this.$emit('update:modelValue', newValue)
            },
            deep: true
        }
    },
    emits: ["update:modelValue", "submit", "clear"],
}
</script>
