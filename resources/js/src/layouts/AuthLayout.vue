<template>
    <main class="wrap-auth">
        <Panel>
            <div class="auth mx-auto">
                <div class="auth__img">
                    <img src="/public/images/auth.png" alt="CheckZone"/>
                </div>
                <RouterView/>
            </div>
        </Panel>
    </main>

</template>

<script>
export default {
    name: "AuthLayout",
};
</script>

<style lang="scss">
.wrap-auth {
    width: 100%;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #1d1e23;

    .p-panel {
        border-radius: 15px;
        .p-panel-header, .p-panel-content {
            padding: 0;
        }
    }

}

.auth {
    width: 100%;
    max-width: 750px;
    display: flex;
    overflow: hidden;
    border-radius: 15px;

    &__link {
        margin-left: auto;
        color: var(--primary-color);
        width: max-content;
    }

    &__img {
        width: 50%;
        overflow: hidden;

        img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    }

    &__form {
        width: 60%;
        background: #fff;
        display: flex;
        justify-content: center;
        flex-direction: column;
        padding: 45px;
        text-align: center;
    }

    h1,h2,h3,h4,h5,h6 {
        font-size: 1.5rem;
        letter-spacing: 2px;
        margin-bottom: 20px;
        color: #000 !important;
    }

    .input-wrap {
        position: relative;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;

        &_icon_not {
            input {
                margin-left: inherit;
            }

            label {
                padding-left: 21px
            }
        }
    }

    &__input {
        position: relative;
        z-index: 2;
        display: block;
        width: calc(100% - 44px);
        height: 56px;
        border: none;
        outline: none;
        padding: 20px 0;
        font-size: 16px;
        margin-left: auto;
        border-bottom: 1px solid #1d1e23;

        &:focus {
            border-bottom: 1px solid var(--primary-color);
        }
    }

    &__icon {
        position: absolute;
        top: 0;
        left: 0;
        width: 44px;
        height: 56px;
        display: flex;
        opacity: 0.15;

        svg {
            width: 30px;
            height: 30px;
            margin: auto;
            fill: none;

            path {
                fill: #000;
            }
        }
    }

    &__label {
        position: absolute;
        z-index: 1;
        top: calc(50% - 7px);
        left: 0;
        opacity: 0;
        transition: all 0.3s ease;
        padding-left: 44px;
        font-size: 12.5px;
    }

    &__input:not(:placeholder-shown) {
        padding: 28px 0px 12px 0px;
    }

    &__input:not(:placeholder-shown) + &__label {
        transform: translateY(-12px);
        z-index: 3;
        opacity: 0.7;
    }

    &__input:valid:not(:placeholder-shown) {
        border-bottom: 1px solid var(--primary-color) !important;
    }

    &__input:valid:not(:placeholder-shown) + &__label + &__icon {
        opacity: 1;

        svg {
            path {
                fill: var(--primary-color);
            }
        }
    }

    &__input:not(:valid):not(:focus) + &__label + &__icon {
        animation-name: shake-shake;
        animation-duration: 0.3s;
    }

    &__errors {
        list-style: none;
        background-color: #5d5d5d;
        color: #fff;
        padding: 20px;
        border-radius: 5px;
        margin-top: 20px;
        opacity: 0.9;
    }

    $displacement: 3px;

    @keyframes shake-shake {
        0% {
            transform: translateX(-$displacement);
        }

        20% {
            transform: translateX($displacement);
        }

        40% {
            transform: translateX(-$displacement);
        }

        60% {
            transform: translateX($displacement);
        }

        80% {
            transform: translateX(-$displacement);
        }

        100% {
            transform: translateX(0px);
        }
    }

    input:-webkit-autofill {
        -webkit-background-clip: text;
    }
}

@media screen and (max-width: 670px) {
    .auth {
        &__img {
            display: none;
        }

        &__form {
            width: 100%;
            padding: 30px 20px;
        }
    }
}
</style>
