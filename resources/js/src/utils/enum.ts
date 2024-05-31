import { TestStatus } from "@/models/test";
import { QuestionType} from "@/models/question";
import {Role} from "@/models/user";

export const userRoleLabel = (role: Role): string => {
    switch (role) {
        case undefined:
        case null:
        case Role.Admin:
            return "Администратор";
        case Role.Moderator:
            return "Модератор";
        default:
            return "Пользователь"
    }
}

export const testStatusLabel = (status: TestStatus | null): string => {
    switch (status) {
        case undefined:
        case null:
        case TestStatus.Passed:
            return "Проверен";
        case TestStatus.Pending:
            return "В ожидании";
        case TestStatus.Ongoing:
            return "В процессе";
    }
};

export const questionTypeLabel = (type: QuestionType | null): string => {
    switch (type) {
        case undefined:
        case null:
        case QuestionType.Open:
            return "Открытый";
        case QuestionType.Close:
            return "Закрытый";
    }
};

export const testPublishedLabel = (status: boolean | null): string => {
    switch (Boolean(status)) {
        case undefined:
        case null:
        case true:
            return "Опубликован";
        case false:
            return "Не опубликован";
    }
};

export const answerStatusLabel = (status: boolean | null): string => {
    switch (status) {
        case undefined:
        case null:
            return "На рассмотрении";
        case true:
            return "Верный";
        case false:
            return "Не верный";
    }
};

export const testStatusClass = (status: TestStatus | null): string => {
    switch (status) {
        case undefined:
        case null:
        case TestStatus.Pending:
            return "warning";
        case TestStatus.Passed:
            return "success";
    }
};

export const testPublishedClass = (status: boolean | null): string => {
    switch (Boolean(status)) {
        case undefined:
        case null:
        case true:
            return "success";
        case false:
            return "danger";
    }
};

export const answerStatusClass = (status: boolean | null): string => {
    switch (status) {
        case undefined:
        case null:
            return "warning"
        case true:
            return "success";
        case false:
            return "danger";
    }
};
