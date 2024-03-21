<template>
    <div tabindex="0" @blur="show = false;" class="profile-nav">
        <div @click="show = !show" :class="show ? 'profile-nav__header_active': null" class="profile-nav__header">
            <div class="profile-nav__wrap-img">
                <img :src="this.user?.avatar ? this.user.avatar : '/images/avatar.png'"  alt="" class="profile-nav__img">
            </div>
            <i class="bi bi-chevron-down profile-nav__icon"></i>
        </div>
        <div v-if="show" class="profile-nav__menu">
            <ul class="profile-nav__list">
                <li @click="this.navigateToProfile()" class="profile-nav__item">
                    <div class="profile-nav__link">
                        <i class="bi bi-person"></i>
                        Мой профиль
                    </div>
                </li>
                <li @click="this.logoutUser()" class="profile-nav__item">
                    <div class="profile-nav__link">
                        <i class="bi bi-box-arrow-right"></i>
                        Выйти
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import {mapActions, mapGetters} from "vuex";
    export default {
        name: "ProfileNav",
        computed: {
          ...mapGetters(['user'])
        },
        data(){
          return {
              show: false,
          }
        },
        methods: {
            ...mapActions(['logout']),
            navigateToProfile() {
                this.$router.push('/profile');
                this.show = false;
            },
            logoutUser(){
                this.logout();
                this.$router.push({ name :"login"})
            }
        }

    }
</script>

<style scoped lang="scss">
.profile-nav {
    position: relative;
    &__header {
        padding: 10px 15px;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        transition: all .3s ease;
        cursor: pointer;
        &:hover, &_active{
            background-color: var(--button-bg);
        }
        &_active{
            .profile-nav__icon {
                transform: rotate(180deg)
            }
        }
    }
    &__wrap-img{
        width: 35px;
        height: 35px;
        overflow: hidden;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    &__img {
        width: 20px;
        height: 20px;
        object-fit: cover;
    }
    &__icon {
        color: #aeb7c2;
        font-size: 12px;
        transition: all 0.3s ease;
    }
    &__menu {
        position: absolute;
        top: calc(var(--header-height) + 10px);
        right: 0;
        width: 200px;
        border-radius: 16px;
        background-color: var(--body-bg);
        overflow: hidden;
        border: 1px solid var(--border-color);
    }
    &__list {
        margin: 0;
        list-style: none;
        padding: 10px 12px;
    }
    &__link{
        color: var(--main-color);
        text-decoration: none;
        display: flex;
        gap: 5px;
        transition: all .3s ease;
        font-size: 15px;
        cursor: pointer;
        padding: 10px 12px;
        border-radius: 8px;
        margin-bottom: 5px;
        &:hover{
            background-color: var(--button-bg);
        }
    }
}
</style>
