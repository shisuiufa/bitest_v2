import type { ApiClientContainer } from "@/services/ApiClientContainer";

import TestClient from "@/services/TestClient";
import ResultClient from "@/services/ResultClient";
import UserClient from "@/services/UserClient";
import TestStatisticsClient from "@/services/TestStatisticsClient";
import AnswerClient from "@/services/AnswerClient";

export function useLaravel(): ApiClientContainer {
    return {
        test: new TestClient(),
        result: new ResultClient(),
        user: new UserClient(),
        testStatistics: new TestStatisticsClient(),
        answer: new AnswerClient(),
    };
}
