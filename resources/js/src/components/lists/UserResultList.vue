<template>
    <div class="row mt-4">
        <UserResultListItem @success="(data) => answerCorrect(data)"
                            @danger="(data) => answerIncorrect(data)"
                            v-for="(item, index) in items"
                            :key="index"
                            :item="item"
                            class="col-12 mb-4 pb-4 border-bottom"/>
    </div>
    <ConfirmPopup/>
</template>

<script>
import UserResultListItem from "./UserResultListItem.vue";
import { useLaravel } from "@/composables/useLaravel.ts";
import * as toast from "@/composables/useNotifications.ts";
const { answer } = useLaravel();
export default {
    name: "UserResultList",
    props: {
        items: {
            type: Array,
            required: true,
        }
    },
    components: {
        UserResultListItem,
    },
    emits: ['update-value'],
    methods: {
        answerCorrect(event) {
            this.$confirm.require({
                target: event[0].currentTarget,
                message: 'Ответ действительно верный?',
                icon: 'pi pi-exclamation-circle',
                acceptIcon: 'pi pi-check',
                rejectIcon: 'pi pi-times',
                rejectClass: 'p-button-secondary p-button-outlined p-button-sm',
                acceptClass: 'p-button-sm',
                rejectLabel: 'Отмена',
                acceptLabel: 'Да',
                accept: async () => {
                    await answer.update(event[1], {correct: true})
                        .then(() => {
                            toast.success('Статус вопроса обновлен!')
                            this.$emit('update-value')
                        })
                        .catch((err) => {
                            toast.error('Произошла ошибка!', err.response.data.message)
                        })
                },
            });
        },
        answerIncorrect(event) {
            this.$confirm.require({
                target: event[0].currentTarget,
                message: 'Ответ действительно неверный?',
                icon: 'pi pi-exclamation-circle',
                acceptIcon: 'pi pi-check',
                rejectIcon: 'pi pi-times',
                rejectClass: 'p-button-secondary p-button-outlined p-button-sm',
                acceptClass: 'p-button-danger p-button-sm',
                rejectLabel: 'Отмена',
                acceptLabel: 'Да',
                accept: async () => {
                    await answer.update(event[1], {correct: false})
                        .then(() => {
                            toast.success('Статус вопроса обновлен!')
                            this.$emit('update-value')
                        })
                        .catch((err) => {
                            toast.error('Произошла ошибка!', err.response.data.message)
                        })
                },
            });
        },
    }
}
</script>
