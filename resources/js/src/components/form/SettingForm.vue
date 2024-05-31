<script>
export default {
    name: "SettingForm",
    data(){
        return {
            sendEmail: false,
            email: '',
            openRegister: false,
            allowPasswordReset: false,
            emailVerification: false,
            captchaEnabled: false,
            limitTestCreation: false,
            maxTestsPerDay: 1,
            randomizeQuestions: false,
            restrictAccess: false,
            allowedIPs: '',
            accessHours: '',
            enable2FA: false,
            passwordPolicy: 'medium',
            typesPassword: [
                {
                    name: 'Низкая сложность',
                    value: 'low'
                },
                {
                    name: 'Средняя сложность',
                    value: 'medium'
                },
                {
                    name: 'Высокая сложность',
                    value: 'high'
                }
            ],
            handleLogoUpload: '',
        }
    }
}
</script>

<template>
    <form method="post" class="d-flex flex-column gap-3">
        <Panel header="Настройки Email" toggleable>
            <div class="d-flex gap-2 mb-2">
                <Checkbox v-model="sendEmail" :binary="true" />
                <label class="label" for="name">Включить Email уведомления</label>
            </div>
            <div v-if="sendEmail" class="d-flex flex-column gap-2">
                <label class="label" for="name">Email для отправки уведомлений</label>
                <InputText id="name" type="email"
                           v-model="this.email"
                           placeholder="Введите email адрес"
                />
            </div>
        </Panel>

        <Panel header="Настройки регистрации" toggleable>
            <div class="d-flex gap-2 align-items-center">
                <Checkbox v-model="openRegister" :binary="true" />
                <label class="label" for="openRegister">Разрешить открытую регистрацию</label>
            </div>
            <div class="d-flex gap-2 align-items-center mt-3">
                <Checkbox v-model="allowPasswordReset" :binary="true" />
                <label class="label" for="allowPasswordReset">Доступ к форме восстановления пароля</label>
            </div>
            <div class="d-flex gap-2 align-items-center mt-3">
                <Checkbox v-model="emailVerification" :binary="true" />
                <label class="label" for="emailVerification">Требовать подтверждение email при регистрации</label>
            </div>
            <div class="d-flex gap-2 align-items-center mt-3">
                <Checkbox v-model="captchaEnabled" :binary="true" />
                <label class="label" for="captchaEnabled">Включить CAPTCHA для регистрации</label>
            </div>
        </Panel>

        <Panel header="Настройки тестирования" toggleable>
            <div class="d-flex gap-2 align-items-center">
                <Checkbox v-model="limitTestCreation" :binary="true" />
                <label class="label" for="limitTestCreation">Ограничить создание тестов</label>
            </div>
            <div v-if="limitTestCreation" class="d-flex flex-column gap-2 mt-3">
                <label class="label" for="maxTestsPerDay">Максимум тестов в день</label>
                <InputText id="maxTestsPerDay" type="number"
                           v-model="maxTestsPerDay"
                           placeholder="Введите количество тестов"
                />
            </div>
            <div class="d-flex gap-2 align-items-center mt-3">
                <Checkbox v-model="randomizeQuestions" :binary="true" />
                <label class="label" for="randomizeQuestions">Случайный порядок вопросов</label>
            </div>
        </Panel>

        <Panel header="Настройки доступа" toggleable>
            <div class="d-flex gap-2 align-items-center">
                <Checkbox v-model="restrictAccess" :binary="true" />
                <label class="label" for="restrictAccess">Ограничить доступ</label>
            </div>
            <div v-if="restrictAccess" class="d-flex flex-column gap-2 mt-3">
                <label class="label" for="allowedIPs">Разрешенные IP-адреса</label>
                <InputText id="allowedIPs" type="text"
                           v-model="allowedIPs"
                           placeholder="Введите IP-адреса, разделенные запятыми"
                />
            </div>
            <div v-if="restrictAccess" class="d-flex flex-column gap-2 mt-3">
                <label class="label" for="accessHours">Часы доступа</label>
                <InputText id="accessHours" type="text"
                           v-model="accessHours"
                           placeholder="Введите часы доступа (например, 09:00-18:00)"
                />
            </div>
        </Panel>

        <Panel header="Настройки безопасности" toggleable>
            <div class="d-flex gap-2 align-items-center">
                <Checkbox v-model="enable2FA" :binary="true" />
                <label class="label" for="enable2FA">Включить двухфакторную аутентификацию</label>
            </div>
            <div class="d-flex flex-column gap-2 mt-3">
                <label class="label" for="passwordPolicy">Политика паролей</label>
                <Dropdown v-model="passwordPolicy" :options="typesPassword"
                          optionLabel="name" optionValue="value"
                          inputId="type"
                          :placeholder="this.typesPassword[0].name"/>
            </div>
        </Panel>

        <Panel header="Настройки внешнего вида" toggleable>
            <div class="d-flex flex-column gap-2 mt-3">
                <label class="label" for="logoUpload">Загрузить логотип</label>
                <FileUpload name="demo[]" url="/api/upload" @upload="''" :multiple="true" accept="image/*" :maxFileSize="1000000">
                    <template #empty>
                        <p>Перетащите файлы сюда для загрузки</p>
                    </template>
                </FileUpload>
            </div>
        </Panel>
    </form>
</template>

