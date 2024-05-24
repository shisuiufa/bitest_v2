import { ApiClient } from "./ApiClient";
export default class TestClient extends ApiClient {
    async index(params: object) {
        const endpoint: string = `/api/tests`;

        return await this.client.get(endpoint, {params: params})
            .then((res) => res.data);
    }
    async show(testId: string | number) {
        const endpoint: string = `/api/tests/${testId}`;

        return await this.client.get(endpoint).then((res) => res.data);
    }
    async start(testId: string | number) {
        const endpoint: string = `/api/tests/${testId}/test_user`;

        return await this.client.post(endpoint).then((res) => res.data);
    }
    async getTestUser(testId: string | number, testUserId: string | number) {
        const endpoint: string = `/api/tests/${testId}/test_user/${testUserId}`;

        return await this.client.get(endpoint).then((res) => res.data);
    }
    async updateTestUser(testId: string | number, testUserId: string | number) {
        const endpoint: string = `/api/tests/${testId}/test_user/${testUserId}`;

        return await this.client.put(endpoint).then((res) => res.data);
    }
    async updateAnswers(
        testId: string | number,
        testUserId: string | number,
        data: object | null,
    ) {
        const endpoint: string = `/api/tests/${testId}/test_user/${testUserId}/answers`;

        return await this.client.post(endpoint, data).then((res) => res.data);
    }
    async getTestsAuthor(authorId: string | number, params: object) {
        const endpoint: string = `/api/moder/tests/created-by/${authorId}`;

        return await this.client
            .get(endpoint, { params: params })
            .then((res) => res.data);
    }

    async getCreatedTest(testId: string | number){
        const endpoint: string = `/api/moder/tests/${testId}`;

        return await this.client
            .get(endpoint)
            .then((res) => res.data);
    }

    async update(testId:string | number, info: object, questions: array){
        const endpoint: string = `/api/moder/tests/${testId}`;

        return await this.client.post(endpoint, {...info, questions})
    }

    async store(info: object, questions: array){
        const endpoint: string = `/api/moder/tests`;

        return await this.client.post(endpoint, {...info, questions})
    }

    async destroy(testId: string | number) {
        const endpoint: string = `/api/moder/tests/${testId}`;

        return await this.client.delete(endpoint).then((res) => res.data);
    }
}
