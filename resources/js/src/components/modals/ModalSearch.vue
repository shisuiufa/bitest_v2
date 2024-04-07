<template>
    <div @click.self="toggleModal('search')" class="modal-search">
        <div @click="toggleModal('search')" class="modal-search__btn-close">
            <i class="bi bi-x-lg"></i>
        </div>
        <div class="modal-search__content">
            <form @submit.prevent="this.submitForm()" class="form-search">
                <div @mouseover="this.animateButton(false)"
                     @mouseout="this.animateButton(true)"
                     class="form-search__wrap">
                    <div @click="this.submitForm()" class="form-search__btn">
                        <i class="bi bi-search"></i>
                    </div>
                    <input @focus="this.focusInput()" v-model="value" type="text" placeholder="Поиск..." class="form-search__input">
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import {mapActions} from "vuex";

export default {
    name: "ModalSearch",
    data(){
        return {
           value: "",
        }
    },
    methods: {
        ...mapActions(['toggleModal']),
        submitForm(){
            this.toggleModal('search');
        },
        animateButton(status){
            const btnClose = document.querySelector('.modal-search__btn-close');
            btnClose.style.opacity = status ? '1' : '0.5';
        },
        focusInput(){
            const inputWrap = document.querySelector('.form-search__wrap');
            inputWrap.classList.add('form-search__wrap_active');
        }
    }
}
</script>

<style scoped lang="scss">
    .modal-search {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        z-index: var(--index-modal);
        backdrop-filter: blur(4px);
        background-color: rgba(0, 0, 0, 0.6);
        display: flex;
        justify-content: center;
        align-items: center;
        &:hover{
            .modal-search__btn-close {
                opacity: 1;
            }
        }
        &__content{
            width: 100%;
            max-width: 500px;
        }
        &__btn-close {
            position: absolute;
            top: 10%;
            right: 10%;
            opacity: 0.5;
            font-size: 40px;
            cursor: pointer;
            border-radius: 10px;
            padding: 10px 20px;
            color: #fff;
            transition: all .3s ease;
        }
    }
    .form-search{
        width: 100%;
        color: var(--main-color);
        &__wrap {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 10px;
            background-color: var(--body-bg);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            transition: all .3s ease;
            overflow: hidden;
            &:hover{
                border: 1px solid var(--border-color-focus);
            }
            &_active {
                border: 1px solid var(--border-color-focus);
            }
        }
        &__input {
            width: 100%;
            border: none;
            outline: none;
            font-size: 17px;
            letter-spacing: 1.3px;
            background-color: inherit;
            color: inherit;
            padding: 10px 10px 10px 0;
        }

        &__btn {
            color: inherit;
            opacity: 0.5;
            font-size: 20px;
            cursor: pointer;
            transition: all .3s ease;
            padding: 10px 0 10px 10px;
            &:hover{
                opacity: 1;
            }
        }
    }
</style>
