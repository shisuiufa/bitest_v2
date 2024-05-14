<template>
    <div class="create-question" v-if="this.typesQuestion.options.length > 0">
        <div class="row">
            <div class="col-12">
                <div class="mb-3">
                    <div
                        class="d-flex justify-content-between align-items-center"
                    >
                        <div class="form-check d-flex gap-2">
                            <input
                                v-model="this.hasImg"
                                class="form-check-input"
                                value="true"
                                type="checkbox"
                                id="checkImg"
                            />
                            <label class="form-check-label" for="checkImg">
                                Изображение
                            </label>
                        </div>
                        <ui-button
                            class="btn-remove"
                            v-if="selectedQuestion"
                            :class="{ 'ui-button_disabled': isFormInvalid }"
                            @click="deleteQuestion"
                        >
                            Удалить вопрос
                        </ui-button>
                    </div>
                </div>
                <div v-show="this.hasImg" class="mb-3">
                    <select-image
                        class="m-auto select-img"
                        @select-image="(image) => (this.img = image)"
                        :image="this.img"
                    >
                    </select-image>
                </div>
                <div class="mb-3">
                    <custom-input
                        v-model:modelValue="name"
                        :label="'Вопрос'"
                        :type="'text'"
                    >
                    </custom-input>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{
                        this.typesQuestion.label
                    }}</label>
                    <custom-select
                        :item="this.typesQuestion"
                        @select-item="
                            (item) => (this.typesQuestion.selected = item)
                        "
                    >
                    </custom-select>
                </div>
                <div
                    v-if="typesQuestion.selected?.value > 0"
                    class="create-question__options options"
                >
                    <label class="form-label">Варианты ответов</label>
                    <div
                        v-for="(option, index) in options"
                        :key="index"
                        class="options__item"
                    >
                        <input
                            v-if="typesQuestion.selected?.value === 1"
                            :id="'option-radio-' + (index + 1)"
                            :value="index"
                            @change="this.updateCorrectOption(option)"
                            :checked="option.correct"
                            name="option-radio"
                            class="form-check-input"
                            type="radio"
                        />
                        <input
                            v-if="typesQuestion.selected?.value === 2"
                            :id="'option-checkbox-' + (index + 1)"
                            v-model="option.correct"
                            :name="'option-' + index"
                            class="form-check-input"
                            type="checkbox"
                        />
                        <custom-input
                            v-model:modelValue="option.name"
                            type="text"
                        ></custom-input>
                        <ui-button
                            v-if="index > 1"
                            @click="deleteOption(index)"
                            class="options__btn-delete"
                            ><i class="bi bi-x-lg"></i
                        ></ui-button>
                    </div>
                </div>
            </div>
            <div class="col-12 d-flex justify-content-between">
                <ui-button
                    v-if="typesQuestion.selected?.value > 0"
                    @click="addOption"
                >
                    Добавить вариант
                </ui-button>
                <ui-button
                    v-if="selectedQuestion"
                    :class="{ 'ui-button_disabled': isFormInvalid }"
                    @click="editQuestion"
                >
                    Сохранить
                </ui-button>
                <ui-button
                    v-else
                    :class="{ 'ui-button_disabled': isFormInvalid }"
                    @click="addQuestion"
                >
                    Добавить вопрос
                </ui-button>
            </div>
        </div>
    </div>
</template>

<script>
import { ref } from "vue";
import SelectImage from "@/components/UI/SelectImage.vue";
import CustomInput from "@/components/UI/CustomInput.vue";
import CustomSelect from "@/components/UI/CustomSelect.vue";
import UiButton from "@/components/UI/UiButton.vue";
import axios from "axios";

export default {
    name: "QuestionCreationForm",
    components: { UiButton, CustomSelect, CustomInput, SelectImage },
    props: {
        selectedQuestion: {
            type: Object,
            default: null,
        },
        questionsId: {
            type: Array,
        },
    },
    data() {
        return {
            id: ref(null),
            hasImg: false,
            img: "",
            name: ref(null),
            options: [
                { id: 1, name: "", correct: true },
                { id: 2, name: "", correct: false },
            ],
            typesQuestion: {
                label: "Тип ответа",
                selected: ref(null),
                options: [],
            },
        };
    },
    methods: {
        updateCorrectOption(correctOption) {
            for (let i = 0; i < this.options.length; i++) {
                const option = this.options[i];
                option.correct = option.id === correctOption.id;
            }
        },
        getQuestionTypes() {
            axios
                .get("/api/admin/question-type")
                .then((res) => {
                    this.typesQuestion.options = res.data.data;
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        findFirstAvailableId(ids) {
            let id = 1;
            while (ids.includes(id)) {
                id++;
            }
            return id;
        },
        addOption() {
            const optionsId = this.options.map((option) => option.id);
            const newOption = {
                id:
                    this.options.length === 0
                        ? 1
                        : this.findFirstAvailableId(optionsId),
                name: "",
                correct: false,
            };
            this.options.push(newOption);
        },
        deleteOption(index) {
            if (this.options.length > 1) {
                this.options.splice(index, 1);
            }
        },
        addQuestion() {
            if (!this.isFormInvalid) {
                const newId = this.findFirstAvailableId(this.questionsId);
                const question = {
                    id: this.questionsId.length === 0 ? 1 : newId,
                    hasImg: this.hasImg,
                    img: this.img,
                    name: this.name,
                    type: this.typesQuestion.selected.value,
                    options: this.options,
                };
                this.$emit("add-question", question);
                this.resetForm();
            }
        },
        editQuestion() {
            const question = {
                id: this.id,
                hasImg: this.hasImg,
                img: this.img,
                name: this.name,
                type: this.typesQuestion.selected.value,
                options: this.options,
            };
            this.$emit("edit-question", question);
            this.resetForm();
        },
        deleteQuestion() {
            this.$emit("delete-question", this.id);
            this.resetForm();
        },
        resetForm() {
            this.id = null;
            this.name = null;
            this.hasImg = false;
            this.img = "";
            this.typesQuestion.selected = null;
            this.options = [
                { id: 1, name: "", correct: true },
                { id: 2, name: "", correct: false },
            ];
        },
    },
    computed: {
        isFormInvalid() {
            return (
                !this.name ||
                (this.typesQuestion.selected?.value > 0 &&
                    this.options.some((option) => !option.name)) ||
                (this.typesQuestion.selected?.value > 0 &&
                    !this.options.some((option) => option.correct))
            );
        },
    },
    watch: {
        selectedQuestion(newValue) {
            if (newValue !== null) {
                this.id = newValue.id;
                this.hasImg = newValue.hasImg;
                this.img = newValue.img;
                this.typesQuestion.selected = this.typesQuestion.options.find(
                    (item) => item.value === newValue.type,
                );
                this.name = newValue.name;
                this.options = JSON.parse(JSON.stringify(newValue.options));
            } else {
                this.resetForm();
            }
        },
    },
    mounted() {
        this.getQuestionTypes();
    },
    emits: ["add-question", "edit-question", "delete-question"],
};
</script>

<style scoped lang="scss">
.create-question {
    .select-img {
        width: 100%;
        height: 300px;
    }

    .options {
        &__item {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        &__btn-delete {
            border-radius: 8px;
            width: 35px;
            height: 35px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #dc3545;
            opacity: 0.6;

            &:hover {
                opacity: 1;
            }
        }
    }

    .btn-remove {
        font-size: 15px;
        cursor: pointer;
        border: 1px solid red;
        color: #fff;
        font-weight: 500;
        background-color: red;

        &:hover {
            color: var(--main-color);
            background-color: inherit;
        }
    }
}
</style>
