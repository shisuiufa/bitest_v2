import PublicLayout from "@/layouts/PublicLayout.vue";
import CreatedTestLayout from "@/layouts/CreatedTestLayout.vue";

import AuthLayout from "@/layouts/AuthLayout.vue";
import LoginView from "@/views/Auth/IndexView.vue";
import ResetPasswordView from "@/views/Auth/ResetPasswordView.vue";

import NotFoundView from "@/views/NotFound.vue";

import HomeView from "@/views/HomeView.vue";
import ProfileView from "@/views/ProfileView.vue";
import TestView from "@/views/TestView.vue";

import CreatedTestView from "@/views/Test/IndexView.vue";
import CreateTestView from "@/views/Test/CreateView.vue";
import EditTestView from "@/views/Test/EditView.vue";
import TestPreviewView from "@/views/Test/PreviewView.vue";
import TestPassView from "@/views/Test/PassView.vue";
import StatisticsView from "@/views/Test/StatisticsView.vue";

import ResultIndexView from "@/views/Result/IndexView.vue";
import ResultShowView from "@/views/Result/ShowView.vue";

import AdminIndexView from "@/views/Admin/IndexView.vue";
import {Role} from "@/models/user.ts";

const routes = [
    {
        component: AuthLayout,
        redirect: { name: "login" },
        meta: { middleware: ["guest"] },
        children: [
            {
                path: "/login",
                component: LoginView,
                name: "login",
                meta: { breadcrumb: "Авторизация" },
            },
            {
                path: "/password/reset/:token",
                component: ResetPasswordView,
                name: 'reset-password',
                meta: { breadcrumb: "Сброс пароля" },
            },
        ]
    },
    {
        path: "/",
        component: PublicLayout,
        redirect: { name: "home" },
        meta: { breadcrumb: "Главная", middleware: [Role.User]},
        children: [
            {
                path: "",
                component: HomeView,
                name: "home",
            },
            {
                path: "/tests/:id",
                redirect: { name: "preview-test" },
                component: TestView,
                name: "tests",
                meta: { breadcrumb: "Тест" },
                children: [
                    {
                        path: "",
                        component: TestPreviewView,
                        name: "preview-test",
                        meta: { breadcrumb: "Просмотр" },
                    },
                    {
                        path: "pass/:testUserId",
                        component: TestPassView,
                        name: "pass-test",
                        meta: { breadcrumb: "Выполнение" },
                    },
                ],
            },
            {
                path: "/created-tests",
                component: CreatedTestLayout,
                redirect: { name: "created-tests" },
                meta: { breadcrumb: "Мои тесты", middleware: [Role.Moderator, Role.Admin]},
                children: [
                    {
                        path: "",
                        component: CreatedTestView,
                        name: "created-tests",
                    },
                    {
                        path: "create",
                        component: CreateTestView,
                        name: "create-test",
                        meta: { breadcrumb: "Создание теста" },
                    },
                    {
                        path: ":id/edit",
                        component: EditTestView,
                        name: "edit-test",
                        meta: { breadcrumb: "Редактирование теста" },
                    },
                    {
                        path: ":id/statistics",
                        component: StatisticsView,
                        name: "statistics",
                        meta: { breadcrumb: "Статистика" },
                    },
                ],
            },
            {
                path: "/profile",
                component: ProfileView,
                name: "profile",
                meta: { breadcrumb: "Личный кабинет" },
            },
            {
                path: "/authors/:id",
                component: ProfileView,
                name: "authors",
                meta: { breadcrumb: "Личный кабинет" },
            },
            {
                path: "/result",
                redirect: { name: "all-result" },
                meta: { breadcrumb: "Результаты" },
                children: [
                    {
                        path: "",
                        component: ResultIndexView,
                        name: "all-result",
                    },
                    {
                        path: ":id",
                        component: ResultShowView,
                        name: "show-result",
                    },
                ],
            },
        ],
    },
    {
        path: '/admin',
        component: PublicLayout,
        meta: { middleware: [Role.Admin] },
        name: "admin"
    },
    {
        path: "/:pathMatch(.*)*",
        component: NotFoundView,
        name: "not-found",
        meta: { breadcrumb: "Страница не найдена", middleware: [Role.User] },
    },
];

export default routes;
