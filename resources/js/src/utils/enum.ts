import { TestStatus } from "@/models/test";

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
