export default {
    actions: {
        setUserAnswer(ctx, [testId, data]){
            const questions = data.map(item => {
                return {
                    ...item,
                    userAnswer: JSON.parse(item.userAnswer)
                };
            });

            const test = {
                id: parseInt(testId),
                questions: questions,
            }

            ctx.commit("setUserTest", test);
        },
        updateUserAnswer(
            ctx,
            [testId, questionId, openAnswer, selectedAnswers],
        ) {
            const test = {
                id: testId,
                questions: [
                    {
                        id: questionId,
                        userAnswer: {
                            openAnswer: openAnswer,
                            selectedAnswers: selectedAnswers,
                        },
                    },
                ],
            };

            ctx.commit("updateUserAnswer", test);
        },
        clearAnswersTest(ctx, testId) {
            ctx.commit("clearAnswersTest", testId);
        },
    },
    mutations: {
        setUserTest(state,test) {
            const indexToSet = state.tests.findIndex(
                (item) => item.id === test.id,
            );

            if (indexToSet >= 0) {
                state.tests[indexToSet] = test;
            } else {
                state.tests.push(test);
            }
        },
        updateUserAnswer(state, test) {
            const indexToUpdate = state.tests.findIndex(
                (item) => item.id === test.id,
            );
            if (indexToUpdate >= 0) {
                const indexQuestion = state.tests[
                    indexToUpdate
                    ].questions.findIndex(
                    (question) => question.id === test.questions[0].id,
                );
                if (indexQuestion >= 0) {
                    if (
                        test.questions[0].userAnswer?.openAnswer?.length === 0 &&
                        test.questions[0].userAnswer?.selectedAnswers?.length ===
                        0
                    ) {
                        state.tests[indexToUpdate].questions.splice(
                            indexQuestion,
                            1,
                        );
                    } else {
                        state.tests[indexToUpdate].questions[indexQuestion] =
                            test.questions[0];
                    }
                } else {
                    if (
                        test.questions[0].userAnswer?.openAnswer?.length > 0 ||
                        test.questions[0].userAnswer?.selectedAnswers?.length > 0
                    ) {
                        state.tests[indexToUpdate].questions.push(
                            test.questions[0],
                        );
                    }
                }
            } else {
                state.tests.push(test);
            }
        },
        clearAnswersTest(state, testId) {
            const indexToUpdate = state.tests.findIndex(
                (item) => item.id === Number(testId),
            );
            if (indexToUpdate !== -1) {
                state.tests.splice(indexToUpdate, 1);
            }
        },
    },
    state: {
        tests: [],
    },
    getters: {
        getAnswersByQuestionId:
            (state) =>
                ([testId, questionId]) => {
                    const testIndex = state.tests.findIndex(
                        (item) => item.id === testId,
                    );
                    if (testIndex >= 0) {
                        return state.tests[testIndex].questions.find(
                            (question) => question.id === questionId,
                        );
                    }
                },
        getUserTestResponse(state) {
            return state.tests;
        },
        getUserAnswersByTestId: (state) => (testId) => {
            const foundTest = state.tests.find((item) => item.id === testId);

            return foundTest?.questions;
        },
    },
};
