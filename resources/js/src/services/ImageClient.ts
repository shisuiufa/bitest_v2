import { ApiClient } from "./ApiClient";
export default class ImageClient extends ApiClient {
    async store(file: file){
        const endpoint: string = `/api/upload-image`;
        const headers = {
            "Content-Type": "multipart/form-data",
        };

        return await this.client.post(endpoint, { image: file }, {headers})
    }
}
