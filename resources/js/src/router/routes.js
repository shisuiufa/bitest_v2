import PublicLayout from "@/layouts/PublicLayout.vue";
import CreatedTestLayout from "@/layouts/CreatedTestLayout.vue";

import LoginView from "@/views/Auth/LoginView.vue";

import NotFoundView from "@/views/NotFound.vue";

import HomeView from "@/views/HomeView.vue";
import ProfileView from "@/views/ProfileView.vue";
import TestView from "@/views/TestView.vue";

import CreatedTestView from "@/views/Test/IndexView.vue";
import CreateTestView from "@/views/Test/CreateView.vue";
import EditTestView from "@/views/Test/EditView.vue";
import TestPreviewView from "@/views/Test/PreviewView.vue";
import TestPassView from "@/views/Test/PassView.vue";

const routes = [
    {
        path: '/login',
        component: LoginView,
        name: 'login',
        meta: {breadcrumb: 'Авторизация', middleware: 'guest'},
    },
    {
        path: '/',
        component: PublicLayout,
        meta: {middleware: 'auth'},
        children: [
            {
                path: '',
                component: HomeView,
                name: 'home',
                meta: {breadcrumb: 'Главная'},
            },
            {
                path: '/tests/:id',
                redirect: {name: 'preview-test'},
                component: TestView,
                name: 'tests',
                meta: {breadcrumb: 'Тест'},
                children: [
                    {
                        path: '',
                        component: TestPreviewView,
                        name: 'preview-test',
                        meta: {breadcrumb: 'Просмотр'},
                    },
                    {
                        path: 'pass/:testUserId',
                        component: TestPassView,
                        name: 'pass-test',
                        meta: {breadcrumb: 'Выполнение'},
                    }
                ]
            },
            {
                path: '/created-tests',
                component: CreatedTestLayout,
                redirect: {name: 'created-tests'},
                meta: {breadcrumb: 'Мои тесты'},
                children: [
                    {
                        path: '',
                        component: CreatedTestView,
                        name: 'created-tests',
                    },
                    {
                        path: 'create',
                        component: CreateTestView,
                        name: 'create-test',
                        meta: {breadcrumb: 'Создать'},
                    },
                    {
                        path: ':id/edit',
                        component: EditTestView,
                        name: 'edit-test',
                    }
                ]
            },
            {
                path: '/profile',
                component: ProfileView,
                name: 'profile',
                meta: {breadcrumb: 'Личный кабинет'},
            },
            {
                path: '/authors/:id',
                component: ProfileView,
                name: 'authors',
                meta: {breadcrumb: 'Личный кабинет'},
            }
        ],
    },
    {
        path: '/:pathMatch(.*)*',
        component: NotFoundView,
        name: 'not-found',
        meta: {breadcrumb: 'Страница не найдена'},
    }
]

export default routes;
