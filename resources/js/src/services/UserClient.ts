import { ApiClient } from "./ApiClient";
import {User} from "@/models/user";
export default class UserClient extends ApiClient {
    async update(form: User) {
        const endpoint: string = `/api/profile`;

        return await this.client.post(endpoint, form).then((res) => res.data);
    }

    async updatePassword(current_password: string, password: string, password_confirmation: string) {
        const endpoint: string = `/api/profile/update_password`;

        return await this.client.post(endpoint, {
            current_password,
            password,
            password_confirmation
        }).then((res) => res.data);
    }
}
