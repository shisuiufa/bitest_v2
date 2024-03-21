<template>
    <tr>
        <td>
            <div class="table__media media">
                <div class="media__img">
                    <img :src="test.image" alt="">
                </div>
                <div class="media__text">
                    {{ test.title }}
                </div>
            </div>
        </td>
        <td>
            <span class="badge badge-dot mr-4">
                <template v-if="!test.published">
                    <i class="bg-danger"></i>
                    Не опубликован
                </template>
                <template v-else>
                    <i class="bg-success"></i>
                    Опубликован
                </template>
            </span>
        </td>
        <td>
            <div class="d-flex gap-2 align-items-center">
                <span>60%</span>
                <div class="progress">
                    <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="60" aria-valuemin="0"
                         aria-valuemax="100" style="width: 60%;"></div>
                </div>
            </div>
        </td>
        <td>
            <div tabindex="0" @blur="showMenu = false;" class="table__dropdown dropdown">
                <div class="dropdown__btn" @click="this.showMenu = !showMenu">
                    <i class="bi bi-three-dots"></i>
                </div>
                <div class="dropdown__menu" v-if="showMenu">
                    <ul class="dropdown__list">
                        <li class="dropdown__item"
                            @click.prevent="
                            this.$router.push({name: 'edit-test' , params: {'id': this.test.id } })">
                            Изменить
                        </li>
                        <li class="dropdown__item" @click.prevent="this.$router.push({name: 'home'})">
                            Проверить
                        </li>
                        <li class="dropdown__item" @click.prevent="this.$emit('delete-test', this.test.id)">
                            Удалить
                        </li>
                    </ul>
                </div>
            </div>
        </td>
    </tr>
</template>

<script>
export default {
    name: "TableTestsItem",
    props: {
        test: {
            required: true
        }
    },
    data() {
        return {
            showMenu: false,
        }
    },
    emits: ['delete-test'],
}
</script>

<style lang="scss">

</style>
