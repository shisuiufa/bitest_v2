import axios, { AxiosInstance } from 'axios';


export abstract class ApiClient {
    private readonly client: AxiosInstance;

    protected constructor() {
        this.client = axios.create();
    }
}
