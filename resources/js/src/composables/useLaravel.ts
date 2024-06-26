import type { ApiClientContainer } from "@/services/ApiClientContainer";

import TestClient from "@/services/TestClient";
import ResultClient from "@/services/ResultClient";
import UserClient from "@/services/UserClient";
import TestStatisticsClient from "@/services/TestStatisticsClient";
import AnswerClient from "@/services/AnswerClient";
import AuthClient from "@/services/AuthClient";
import ImageClient from "@/services/ImageClient";
import ProfileClient from "@/services/ProfileClient";

export function useLaravel(): ApiClientContainer {
    return {
        test: new TestClient(),
        result: new ResultClient(),
        profile: new ProfileClient(),
        user: new UserClient(),
        testStatistics: new TestStatisticsClient(),
        answer: new AnswerClient(),
        auth: new AuthClient(),
        image: new ImageClient()
    };
}
