<template>
    <div class="row">
        <div class="col-12 mb-2">
            <div class="d-flex align-items-center justify-content-end gap-2">
                <label class="cursor-pointer" for="published">Картинка</label>
                <InputSwitch inputId="published" v-model="this.question.hasImage"/>
            </div>
        </div>
        <div v-if="this.question.hasImage" class="col-md-5 col-12 mb-2 mb-md-0">
            <SelectImage class="w-100" style="height: 350px"  v-model="this.question.image"/>
        </div>
        <div :class="this.question.hasImage ? 'col-12 col-md-7' : 'col-12'" class="">
            <div class="d-flex flex-column gap-2">
                <div class="d-flex flex-column gap-2">
                    <label for="name">Вопрос</label>
                    <InputText id="name" v-model="this.question.name" placeholder="Вопрос"/>
                </div>
                <div class="d-flex flex-column gap-2">
                    <label for="type">Тип вопроса</label>
                    <Dropdown v-model="this.question.question_type_id" :options="this.types"
                              optionLabel="name" optionValue="value"
                              inputId="type"
                              :placeholder="this.types[0].name"/>
                </div>
                <div v-if="this.question.question_type_id === this.QuestionType.Close" class="d-flex flex-column gap-2">
                    <label>Варианты</label>
                    <InputGroup v-for="(option, idx) in this.question.options" :key="idx">
                        <InputGroupAddon>
                            <Checkbox @change="this.selectOption(option)" v-model="option.value" :binary="true"/>
                        </InputGroupAddon>
                        <InputText v-model="option.name" :placeholder="'Вариант ' + (idx + 1)"/>
                        <Button class="radius-right" v-if="idx > 1" icon="pi pi-times" severity="danger" @click="this.deleteOption(option)"/>
                    </InputGroup>
                    <div class="d-flex justify-content-center">
                        <Button icon="pi pi-plus" rounded class="p-primary p-button-sm" @click="this.addOption()"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import SelectImage from "@/components/UI/SelectImage.vue";
import {QuestionType} from "@/models/question.ts";
import {questionTypeLabel} from "@/utils/enum.ts";

export default {
    name: "CreateQuestionForm",
    components: {SelectImage},
    props: ['question'],
    data() {
        return {
            QuestionType: QuestionType,
            types: [
                {
                    name: questionTypeLabel(QuestionType.Close),
                    value: QuestionType.Close
                },
                {
                    name: questionTypeLabel(QuestionType.Open),
                    value: QuestionType.Open
                },
            ]
        }
    },
    watch: {
        'question.question_type_id': function (newType) {
            const options = [{name: "", value: true}, {name: "", value: false}];
            if( newType === QuestionType.Close && this.question.options.length === 0) {
                this.question.options = options;
            }
        },
        'question.hasImage': function (newValue) {
            if (!newValue) {
                this.question.image = '';
            }
        },
    },
    methods: {
        addOption() {
            this.question.options.push({
                name: "",
                value: false
            })
        },
        deleteOption(option) {
            this.question.options = this.question.options.filter(opt => opt !== option);

            if (this.checkFalseOption() <= 0) {
                this.question.options[this.question.options.length - 1].value = false;
            }
        },
        selectOption(option) {
            if (!option.value) {
                return;
            }

            if (this.checkFalseOption() === 0) {
                option.value = false;
            }
        },
        checkFalseOption() {
            return this.question.options.reduce((count, opt) => {
                return opt.value === false ? count + 1 : count;
            }, 0);
        }
    },

    emits: ["update:question"],
}
</script>
