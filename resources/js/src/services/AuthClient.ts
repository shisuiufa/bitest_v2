import { ApiClient } from "./ApiClient";
import {RegisterUser, User} from "../models/user";
export default class AuthClient extends ApiClient {
    async getCsrfCookie(): Promise<void> {
        await this.client.get('/sanctum/csrf-cookie');
    }
    async login(data: object | null): Promise<void> {
        const endpoint: string = 'login'

        await this.getCsrfCookie();

        await this.client.post(endpoint, data)
    }

    async logout(): Promise<void> {
        await this.getCsrfCookie();

        await this.client.post('logout')
    }

    async register(data: RegisterUser): Promise<User> {
        const endpoint = '/register'

        await this.getCsrfCookie();

        return await this.client.post(endpoint, data).then((response: { data: User }) => response.data)
    }

    async sendResetLinkEmail(email: string){
        const endpoint = '/password/email'

        await this.getCsrfCookie();

        return await this.client.post(endpoint, {email}).then((response: { data: User }) => response.data)
    }

    async resetPassword(form: object){
        const endpoint = '/password/reset'

        await this.getCsrfCookie();

        return await this.client.post(endpoint, form).then((response: { data: User }) => response.data)
    }


}
