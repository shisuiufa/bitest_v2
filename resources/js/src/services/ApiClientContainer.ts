import type TestClient from "@/services/TestClient";
import type ResultClient from "@/services/ResultClient";
import type UserClient from "@/services/UserClient";
import type TestStatisticsClient from "@/services/TestStatisticsClient";
import type AnswerClient from "@/services/AnswerClient";

export interface ApiClientContainer {
    test: TestClient;
    result: ResultClient;
    user: UserClient;
    testStatistics: TestStatisticsClient;
    answer: AnswerClient;
}
