<template>
    <Accordion :activeIndex="this.questions.length - 1" expandIcon="pi pi-plus" collapseIcon="pi pi-minus">
        <AccordionTab v-for="(question, idx) in this.questions" :key="question.id">
            <template #header>
                <span class="d-flex align-items-center justify-content-between gap-2 w-100">
                    <h5>Вопрос {{ idx + 1 + (question.name ? ' - ' + question.name : '') }}</h5>
                     <button class="p-panel-header-icon p-link me-3"
                             @click.stop="showMenu($event, idx)"
                             aria-haspopup="true"
                             :aria-controls="'overlay_menu_' + idx">
                        <span class="pi pi-cog"></span>
                    </button>
                    <Menu :ref="'menu_' + idx" :id="'overlay_menu_' + idx" :model="getItems(idx)" popup/>
                </span>
            </template>
            <CreateQuestionForm v-model:question="this.questions[idx]"/>
        </AccordionTab>
    </Accordion>
    <div class="d-flex justify-content-end mt-2">
        <Button label="Добавить вопрос" class="p-primary" @click="this.addQuestion()"/>
    </div>
</template>

<script>
import CreateQuestionForm from "@/components/form/CreateQuestionForm.vue";
import {QuestionType} from "@/models/question.ts";
import { mapActions } from "vuex";
export default {
    name: "QuestionCreatedList",
    components: {CreateQuestionForm},
    props: ['questions', 'enableCache'],
    methods: {
        addQuestion() {
            this.questions.push({
                hasImage: false,
                image: '',
                name: '',
                question_type_id: QuestionType.Close,
                options: [
                    {
                        name: "",
                        value: true
                    },
                    {
                        name: "",
                        value: false
                    },
                ],
            })
        },
        deleteQuestion(idx) {
            this.$emit('delete-question', idx)
        },
        getItems(idx) {
            return [
                {
                    label: 'Удалить',
                    icon: 'pi pi-times',
                    command: () => this.deleteQuestion(idx)
                }
            ];
        },
        showMenu(event, idx) {
            const menuRef = this.$refs['menu_' + idx];
            if (menuRef && menuRef[0]) {
                menuRef[0].toggle(event);
            }
        },
        ...mapActions(["updateQuestions"]),
    },
    watch: {
        questions: {
            handler(newValue) {
                if(this.enableCache){
                    const questionsForCache = {
                        questions: newValue.map(question => ({ ...question}))
                    };
                    this.updateQuestions([questionsForCache, 0])
                }
                this.$emit('update:modelValue', newValue)
            },
            deep: true
        }
    },
    emits: ['delete-question', 'update:modelValue']
}
</script>
