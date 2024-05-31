import type TestClient from "@/services/TestClient";
import type ResultClient from "@/services/ResultClient";
import type UserClient from "@/services/UserClient";
import type TestStatisticsClient from "@/services/TestStatisticsClient";
import type AnswerClient from "@/services/AnswerClient";
import type AuthClient from "@/services/AuthClient";
import type ImageClient from "@/services/ImageClient";
import type ProfileClient from "@/services/ProfileClient";

export interface ApiClientContainer {
    test: TestClient;
    result: ResultClient;
    user: UserClient;
    profile: ProfileClient,
    testStatistics: TestStatisticsClient;
    answer: AnswerClient;
    auth: AuthClient
    image: ImageClient
}
