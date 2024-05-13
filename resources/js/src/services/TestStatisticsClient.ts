import { ApiClient } from "./ApiClient";
export default class TestStatisticsClient extends ApiClient {
    async index(testId: number | string, params: object | null){
        const endpoint: string = `/api/moder/tests/${testId}/statistics`

        return await this.client.get(endpoint, {params: params}).then((res) => res.data)
    }
    async show(testId: number | string, userId: number | string){

    }
}

