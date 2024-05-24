import "./bootstrap";
import { createApp } from "vue";
import App from "@/App.vue";
import Router from "@/router/router.js";
import Store from "@/store/index.js";
import ToastService from "primevue/toastservice";
import PrimeVue from "primevue/config";
import Toast from "primevue/toast";
import ConfirmationService from 'primevue/confirmationservice';
import ConfirmPopup from 'primevue/confirmpopup';

const locales = {
    startsWith: "Начинается с",
    contains: "Содержит",
    notContains: "Не содержит",
    endsWith: "Заканчивается на",
    equals: "Равно",
    notEquals: "Не равно",
    noFilter: "Без фильтра",
    lt: "Меньше чем",
    lte: "Меньше или равно",
    gt: "Больше чем",
    gte: "Больше или равно",
    dateIs: "Дата равна",
    dateIsNot: "Дата не равна",
    dateBefore: "Дата до",
    dateAfter: "Дата после",
    clear: "Очистить",
    apply: "Применить",
    matchAll: "Совпадение со всеми",
    matchAny: "Совпадение с любым",
    addRule: "Добавить правило",
    removeRule: "Удалить правило",
    accept: "Принять",
    reject: "Отклонить",
    choose: "Выбрать",
    upload: "Загрузить",
    cancel: "Отмена",
    completed: "Завершено",
    pending: "В ожидании",
    fileSizeTypes: ["Б", "КБ", "МБ", "ГБ", "ТБ", "ПБ", "ЭБ", "ЗБ", "ИБ"],
    dayNames: [
        "Воскресенье",
        "Понедельник",
        "Вторник",
        "Среда",
        "Четверг",
        "Пятница",
        "Суббота",
    ],
    dayNamesShort: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
    dayNamesMin: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
    monthNames: [
        "Январь",
        "Февраль",
        "Март",
        "Апрель",
        "Май",
        "Июнь",
        "Июль",
        "Август",
        "Сентябрь",
        "Октябрь",
        "Ноябрь",
        "Декабрь",
    ],
    monthNamesShort: [
        "Янв",
        "Фев",
        "Мар",
        "Апр",
        "Май",
        "Июн",
        "Июл",
        "Авг",
        "Сен",
        "Окт",
        "Ноя",
        "Дек",
    ],
    chooseYear: "Выберите год",
};

createApp(App)
    .use(Router)
    .use(Store)
    .use(ToastService)
    .use(ConfirmationService)
    .component("Toast", Toast)
    .component('ConfirmPopup', ConfirmPopup)
    .use(PrimeVue, {
        unstyled: false,
        locale: locales,
        ripple: true,
    })
    .mount("#app");
