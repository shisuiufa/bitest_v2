import { ApiClient } from "./ApiClient";
export default class TestStatisticsClient extends ApiClient {
    async index(testId: number | string, params: object | null) {
        const endpoint: string = `/api/moder/tests/${testId}/statistics`;

        return await this.client
            .get(endpoint, { params: params })
            .then((res) => res.data);
    }

    async show(testId: number | string, userId: number | string, params: object | null){
        const endpoint: string = `/api/moder/tests/${testId}/statistics/${userId}`;

        return await this.client
            .get(endpoint, {params: params})
            .then((res) => res.data)
    }

    async totalAttempts(testId: number | string, userId: number | string) {
        const endpoint: string = `/api/moder/tests/${testId}/statistics/${userId}/attempts`;

        return await this.client
            .get(endpoint)
            .then((res) => res.data)
    }

    async exportExcel(testId: number | string){
        const endpoint: string = `/api/moder/tests/${testId}/statistics/export`;

        return await this.client
            .get(endpoint, { responseType: 'blob' })
            .then((res) => res.data)
    }
}
