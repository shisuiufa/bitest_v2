export default {
    actions: {
        updateUserAnswer(ctx, [testId, questionId, openAnswer, selectedAnswers]) {
            const test = {
                id: testId,
                questions: [
                    {
                        id: questionId,
                        userAnswer: {
                            openAnswer: openAnswer,
                            selectedAnswers: selectedAnswers,
                        }
                    }
                ],
            };

            ctx.commit('updateUserAnswer', test);
        },
        clearCache(ctx) {
            ctx.commit('clearCache');
        }
    },
    mutations: {
        updateUserAnswer(state, test) {
            const indexToUpdate = state.tests.findIndex(item => item.id === test.id);

            if (indexToUpdate >= 0) {
                const indexQuestion = state.tests[indexToUpdate].questions.findIndex(question => question.id === test.questions[0].id)

                if (indexQuestion >= 0) {
                    if (test.questions[0].userAnswer.openAnswer.length === 0 &&
                        test.questions[0].userAnswer.selectedAnswers.length === 0) {
                        state.tests[indexToUpdate].questions.splice(indexQuestion, 1);
                    } else {
                        state.tests[indexToUpdate].questions[indexQuestion] = test.questions[0];
                    }
                } else {
                    state.tests[indexToUpdate].questions.push(test.questions[0])
                }
            } else {
                state.tests.push(test);
            }
        },
        clearCache(state) {
            state.tests = [];
        }
    },
    state: {
        tests: [],
    },
    getters: {
        getAnswersByQuestionId: (state) => ([testId, questionId]) => {
            const testIndex = state.tests.findIndex(item => item.id === testId);

            if (testIndex >= 0) {
                return state.tests[testIndex].questions.find(question => question.id === questionId)
            }
        },
        getUserTestResponse(state) {
            return state.tests;
        },
        getUserAnswersByTestId: (state) => (testId) => {
            const foundTest = state.tests.find(item => item.id === testId);

            return foundTest.questions
        }
    }
}
