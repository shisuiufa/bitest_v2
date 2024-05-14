import { ApiClient } from "./ApiClient";
export default class ResultClient extends ApiClient {
    async index(params: object | null) {
        const endpoint: string = `/api/results`;

        return await this.client
            .get(endpoint, { params: params })
            .then((res) => res.data);
    }

    async show(testId: string | number) {
        const endpoint: string = `/api/results/${testId}`;

        return await this.client.get(endpoint).then((res) => res.data);
    }
}
