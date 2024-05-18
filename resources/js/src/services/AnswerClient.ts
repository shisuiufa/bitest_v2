import { ApiClient } from "./ApiClient";
export default class AnswerClient extends ApiClient {
    async update(answerId: number | string, correct: boolean) {
        const endpoint: string = `/api/moder/answers/${answerId}`;

        return await this.client
            .post(endpoint, correct)
            .then((res) => res.data);
    }
}
