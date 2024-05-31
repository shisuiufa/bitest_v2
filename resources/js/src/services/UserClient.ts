import { ApiClient } from "./ApiClient";
import {User} from "@/models/user";
export default class UserClient extends ApiClient {
    async index(){
        const endpoint: string = `/api/admin/users`;

        return await this.client.get(endpoint).then((res) => res.data)
    }

    async show(userId: string | number) {
        const endpoint: string = `/api/admin/users/${userId}`;

        return await this.client.get(endpoint).then((res) => res.data)
    }

    async store(user: User){
        const endpoint: string = `/api/admin/users`;

        return await this.client.post(endpoint, user).then((res) => res.data)
    }

    async update(user: User){
        const endpoint: string = `/api/admin/users/${user.id}`;

        return await this.client.put(endpoint, user).then((res) => res.data)
    }

}
