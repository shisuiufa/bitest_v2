import router from "../../router/router.js";
import axios from "axios";

export default {
    actions: {
        updateInfo(ctx, [info, testId]) {
            ctx.commit("updateInfo", [info, testId]);
        },
        updateQuestions(ctx, questions) {
            ctx.commit("updateQuestions", questions);
        },
        updateAccesses(ctx, accesses) {
            ctx.commit("updateAccesses", accesses);
        },
        clearStateTest(ctx, testId) {
            ctx.commit("clearStateTest", testId);
        },
        clearAll(ctx){
            ctx.commit("clearAllTest")
        },
        validatedForm(ctx, data) {
            const missingFields = [];

            if (!data[0].title) {
                missingFields.push("Заполните название теста");
            }

            if (!data[0].image) {
                missingFields.push("Необходимо выбрать картинку для теста");
            }

            if (
                (data[1] === "create" && !data[0].questions) ||
                (data[1] === "create" && data[0].questions.length === 0)
            ) {
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
            let info = ctx.getters.cachedTest.find((item) => {
                if (item.create) {
                    return item.create;
                }
            });
            info = info.create;

            const userId = ctx.getters.user.id;

            ctx.dispatch("validatedForm", [info, "create"]).then((result) => {
                if (result) {
                    const test = {
                        title: info.title,
                        desc: info.desc,
                        image: info.image,
                        timeComplete: info.time_complete,
                        attempts: info.attempts,
                        limitQuestions: info.limit_questions,
                        published: info.published,
                        userId: userId,
                    };
                    axios
                        .post("/api/moder/tests", {
                            info: test,
                            questions: info.questions,
                        })
                        .then(() => {
                            ctx.commit("setMissingFields", null);
                            ctx.commit("clearStateTest", info.id);
                            router.push({ name: "created-tests" });
                            console.log(ctx.getters.cachedTest);
                        })
                        .catch((err) => {
                            console.log(err);
                        });
                }
            });
        },
        updateTest(ctx, testId) {
            let info = ctx.getters.cachedTest.find((item) => {
                return Object.values(item)[0].id === testId;
            });

            info = info.edit;

            ctx.dispatch("validatedForm", [info, "edit"]).then((result) => {
                if (result) {
                    const test = {
                        title: info.title,
                        desc: info.desc,
                        image: info.image,
                        timeComplete: info.time_complete,
                        attempts: info.attempts,
                        limitQuestions: info.limit_questions,
                        published: info.published,
                    };
                    axios
                        .post(`/api/moder/tests/${testId}`, {
                            info: test,
                            questions: info?.questions,
                        })
                        .then(() => {
                            ctx.commit("setMissingFields", null);
                            ctx.commit("clearStateTest", testId);
                            router.push({ name: "created-tests" });
                            console.log(ctx.getters.cachedTest);
                        })
                        .catch((err) => {
                            console.log(err);
                        });
                }
            });
        },
    },
    mutations: {
        updateInfo(state, [info, testId]) {
            const existingTest = state.tests.find(obj => obj.hasOwnProperty(testId));

            if (existingTest) {
                existingTest[testId] = { ...existingTest[testId], ...info };
            } else {
                const newObject = { [testId]: info };
                state.tests.push(newObject);
            }
        },
        updateQuestions(state, [data, testId]) {
            const existingTest = state.tests.find(obj => obj.hasOwnProperty(testId));
            if (existingTest) {
                existingTest[testId] = { ...existingTest[testId], ...data };
            } else {
                const newObject = { [testId]: data };
                state.tests.push(newObject);
            }
        },
        setMissingFields(state, missingFields) {
            state.missingFields = missingFields;
        },
        clearStateTest(state, testId) {
            state.tests = state.tests.filter(test => !test.hasOwnProperty(testId));
        },
        clearTest(state, testId) {
            state.tests = state.tests.filter(obj => !obj.hasOwnProperty(testId));
            state.missingFields = [];
        },
        clearAllTest(state) {
            state.tests = [];
        },
    },
    state: {
        missingFields: [],
        tests: [],
    },
    getters: {
        missingFields(state) {
            return state.missingFields;
        },
        cachedTest(state) {
            return state.tests;
        },
        getTestById: (state) => (testId) => {
            const test = state.tests.find(obj => obj.hasOwnProperty(testId));

            return test ? test[testId] : null;
        },
    },
};
