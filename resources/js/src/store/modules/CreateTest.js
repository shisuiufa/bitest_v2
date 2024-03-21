import router from "../../router/router.js";
import axios from "axios";

export default {
    actions: {
        updateInfo(ctx, info) {
            ctx.commit('updateInfo', info);
        },
        updateQuestions(ctx, questions) {
            ctx.commit('updateQuestions', questions)
        },
        updateAccesses(ctx, accesses) {
            ctx.commit('updateAccesses', accesses)
        },
        clearStateTest(ctx) {
            ctx.commit('clearStateTest', '');
        },
        validatedForm(ctx, data) {
            const missingFields = [];

            if (!data[0].title) {
                missingFields.push("Заполните название теста");
            }

            if (!data[0].image) {
                missingFields.push("Необходимо выбрать картинку для теста");
            }

            if (data[1] === 'create' && !data[0].questions || data[1] === 'create' && data[0].questions.length === 0) {
                missingFields.push("Заполните вопросы для теста");
            }

            if (missingFields.length > 0) {
                ctx.commit("setMissingFields", missingFields);
                return false;
            } else {
                return true;
            }
        },
        createTest(ctx) {
            let info = ctx.getters.cachedTest.find(item => {
                if (item.create) {
                    return item.create
                }
            });
            info = info.create;

            const userId = ctx.getters.user.id;

            ctx.dispatch('validatedForm', [info, 'create'])
                .then((result) => {
                    if (result) {
                        const test = {
                            title: info.title,
                            image: info.image,
                            timeComplete: info.time_complete,
                            attempts: info.attempts,
                            limitQuestions: info.limit_questions,
                            published: info.published,
                            userId: userId,
                        };
                        axios.post('/api/moder/tests',
                            {
                                'info': test,
                                'questions': info.questions,
                            })
                            .then((res) => {
                                ctx.commit("setMissingFields", null);
                                ctx.commit("clearStateTest", info.id);
                                router.push({name: 'created-tests'});
                                console.log(ctx.getters.cachedTest)
                            })
                            .catch((err) => {
                                console.log(err)
                            })
                    }
                });
        },
        updateTest(ctx, testId) {
            let info = ctx.getters.cachedTest.find(item => {
                return Object.values(item)[0].id === testId;
            });

            info = info.edit;

            ctx.dispatch('validatedForm', [info, 'edit'])
                .then((result) => {
                    if (result) {
                        const test = {
                            title: info.title,
                            image: info.image,
                            timeComplete: info.time_complete,
                            attempts: info.attempts,
                            limitQuestions: info.limit_questions,
                            published: info.published,
                        };
                        axios.post(`/api/moder/tests/${testId}`,
                            {
                                'info': test,
                                'questions': info?.questions,
                            })
                            .then((res) => {
                                ctx.commit("setMissingFields", null);
                                ctx.commit("clearStateTest", testId);
                                router.push({name: 'created-tests'});
                                console.log(ctx.getters.cachedTest)
                            }).catch((err) => {
                            console.log(err)
                        })
                    }
                });

        },
    },
    mutations: {
        updateInfo(state, info) {
            const keyToUpdate = info[0];
            const valueToUpdate = info[1];
            const indexToUpdate = state.test.findIndex(item =>
                Object.keys(item)[0] === keyToUpdate && !Object.values(item)[0].id
                || Object.keys(item)[0] === keyToUpdate
                && Object.values(item)[0].id === valueToUpdate.id);

            if (indexToUpdate !== -1) {
                const existingItem = state.test[indexToUpdate][keyToUpdate];
                const updatedItem = {[keyToUpdate]: valueToUpdate};

                if (existingItem.questions) {
                    updatedItem[keyToUpdate].questions = existingItem.questions;
                }

                state.test.splice(indexToUpdate, 1, updatedItem);
            } else {
                state.test.push({[keyToUpdate]: valueToUpdate});
            }

        },
        updateQuestions(state, data) {
            const keyToUpdate = data[0];
            const valueToUpdate = data[1];
            const testId = data[2];
            const indexToUpdate = state.test.findIndex(item =>
                Object.keys(item)[0] === keyToUpdate && !Object.values(item)[0].id
                || Object.keys(item)[0] === keyToUpdate
                && Object.values(item)[0].id === testId);

            if (indexToUpdate !== -1) {
                state.test[indexToUpdate][keyToUpdate].questions = valueToUpdate;
            }
        },
        updateAccesses(state, accesses) {
            // state.accesses = accesses;
        },
        setMissingFields(state, missingFields) {
            state.missingFields = missingFields;
        },
        clearStateTest(state, testId) {
            let indexToRemove = state.test.findIndex(item => {
                return Object.values(item)[0].id === testId;
            });
            if (indexToRemove !== -1) {
                state.test.splice(indexToRemove, 1);
            }
            state.missingFields = [];
        },
        clearAllTest(state) {
            state.test = [];
        }
    },
    state: {
        missingFields: [],
        test: [],
    },
    getters: {
        missingFields(state) {
            return state.missingFields;
        },
        cachedTest(state) {
            return state.test;
        }
    }
}
