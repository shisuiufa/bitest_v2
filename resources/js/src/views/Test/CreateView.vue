<template>
    <Card>
        <template #content>
            <TabView :scrollable="true">
                <TabPanel header="Основная информация">
                    <CreateTestForm :enableCache="true"
                                    v-model:form="this.form"
                                    @clear="clearTest"
                                    @submit="handleSubmit"
                                    class="p-0 px-md-5"/>
                </TabPanel>
                <TabPanel header="Вопросы">
                    <QuestionCreatedList
                        :enableCache="true"
                        @delete-question="deleteQuestion"
                        v-model:questions="this.questions"/>
                </TabPanel>
            </TabView>
        </template>
    </Card>
</template>

<script>
import CreateTestForm from "@/components/form/CreateTestForm.vue";
import QuestionCreatedList from "@/components/lists/QuestionCreatedList.vue";
import {QuestionType} from "@/models/question.ts";
import {mapActions, mapGetters} from "vuex";
import {useLaravel} from "@/composables/useLaravel.ts";
import * as toast from "@/composables/useNotifications.ts";
import router from "../../router/router.js";

const {test} = useLaravel();
export default {
    data() {
        return {
            form: {
                image: '',
                title: '',
                desc: '',
                published: Boolean(false),
                time_complete: null,
                attempts: null,
                limit_questions: null,
            },
            questions: [
                {
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
                }
            ],
        }
    },
    components: {
        QuestionCreatedList,
        CreateTestForm,
    },
    computed: {
        ...mapGetters(["getTestById"]),
    },
    created() {
        this.getData();
    },
    name: "CreateView",
    methods: {
        ...mapActions(['clearStateTest']),
         deleteQuestion(idx) {
            this.questions.splice(idx, 1);
        },
        async handleSubmit() {
            await test.store(this.form, this.questions)
                .then(() => {
                    this.clearStateTest(0);
                    router.push({ name: "created-tests" });
                    toast.success('Тест успешно создан!')
                })
                .catch((err) => {
                    toast.error('Ошибка', err.response.data.message)
                })
        },
        async clearTest() {
            await this.clearStateTest(0).then(() => {
                toast.success('Данные успешно очищены!');
            });
            await this.getData()
        },
        async getData() {
            if (this.getTestById(0) !== null && this.getTestById(0)['info']) {
                this.form = this.getTestById(0)['info'];
            } else {
                this.form = {
                    image: '',
                    title: '',
                    desc: '',
                    published: Boolean(false),
                    time_complete: null,
                    attempts: null,
                    limit_questions: null,
                }
            }
            if (this.getTestById(0) !== null && this.getTestById(0)['questions']) {
                this.questions = this.getTestById(0)['questions'];
            } else {
                this.questions = [
                    {
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
                    }
                ]
            }
        }
    }
};
</script>
