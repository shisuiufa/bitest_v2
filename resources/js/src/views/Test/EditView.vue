<template>
    <Card>
        <template #content>
            <TabView :scrollable="true">
                <TabPanel header="Основная информация">
                    <CreateTestForm :enableCache="false"
                                    v-model:form="this.form"
                                    @clear="clearTest"
                                    @submit="handleSubmit"
                                    class="p-0 px-md-5"/>
                </TabPanel>
                <TabPanel header="Вопросы">
                    <QuestionCreatedList :enableCache="false"
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
import {useLaravel} from "@/composables/useLaravel.ts";
import * as toast from "@/composables/useNotifications.ts";
import router from "../../router/router.js";

const {test} = useLaravel();
export default {
    data() {
        return {
            form: {},
            questions: [],
        }
    },
    components: {
        QuestionCreatedList,
        CreateTestForm,
    },
    created() {
        this.getData();
    },
    name: "CreateView",
    methods: {
        async deleteQuestion(idx) {
            this.questions.splice(idx, 1);
        },
        async handleSubmit() {
            await test.update(this.$route.params.id, this.form, this.questions)
                .then(() => {
                    toast.success('Тест обновлен!');
                    router.push({ name: "created-tests" });
                })
                .catch((err) => {
                    toast.error('Ошибка!', err.response.data.message);
                })
        },
        async getData() {
            await test.getCreatedTest(this.$route.params.id)
                .then((res) => {
                    const { questions, ...formData } = res.data;
                    this.form = formData;
                    this.questions = questions.map(question => ({
                        ...question,
                        hasImage: !!question.image
                    }));
                    toast.success('Данные теста загружены!');
                })
                .catch((err) => {
                    toast.error('Ошибка!', err.response.data.message);
                })
        }
    }
};
</script>
