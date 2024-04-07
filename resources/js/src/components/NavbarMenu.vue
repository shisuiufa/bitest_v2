<template>
    <nav class="navbar">
        <ul class="navbar__menu">
            <li class="navbar__item">
                <router-link class="navbar__link" :to="{name: 'home'}">
                    <i class="bi bi-house-door"></i>
                    <span>Главная</span>
                </router-link>
            </li>
            <li class="navbar__item">
                <router-link class="navbar__link" :to="{name: 'created-tests'}">
                    <i class="bi bi-ui-checks-grid"></i>
                    <span>Мои тесты</span>
                </router-link>
            </li>
            <li class="navbar__item">
                <router-link class="navbar__link" :to="{name: 'home'}">
                    <i class="bi bi-card-checklist"></i>
                    <span>Результаты</span>
                </router-link>
            </li>
            <li class="navbar__item">
                <router-link class="navbar__link" :to="{name: 'home'}">
                    <i class="bi bi-sliders"></i>
                    <span>Настройки</span>
                </router-link>
            </li>
        </ul>
    </nav>
</template>

<script>
export default {
    name: 'NavbarMenu',
}
</script>

<style scoped lang="scss">
$borderRadius: 10px;
$primary: #406ff3;
$text: #6a778e;
$timing: 250ms;
$transition: $timing ease all;

@mixin gooeyEffect($i) {
    @keyframes gooeyEffect-#{$i} {
        0% {
            transform: scale(1, 1);
        }
        50% {
            transform: scale(0.5, 1.5);
        }
        100% {
            transform: scale(1, 1);
        }
    }
}
.navbar {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    border-radius: 10px;
    box-shadow: rgba(0, 0, 0, 0.05) 0px 6px 24px 0px, rgba(0, 0, 0, 0.08) 0px 0px 0px 1px;
    background: var(--button-bg);
    padding: 0.5rem 1rem;
    z-index: 200;
    &__menu {
        position: relative;
        list-style: none;
        display: flex;
        margin: 0;
        padding: 0;
    }

    &__link {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 2.5rem;
        width: 5.5rem;
        color: #6a778e;
        transition: 250ms ease all;

        &:hover {
            color: #fff;
            span {
                opacity: 1;
                bottom: calc(100% + 0.5rem);
            }
        }

        i {
            font-size: 24px;
        }

        span {
            display:inline-block;
            text-wrap: nowrap;
            position: absolute;
            bottom: 0;
            text-align: center;
            margin-bottom: 0.5rem;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0;
            pointer-events: none;
            color: var(--navbar-color);
            background: var(--button-bg);
            padding: 10px 20px;
            transition: $transition;
            border-radius: 10px;
            font-weight: 700;
            box-shadow: rgba(0, 0, 0, 0.05) 0px 6px 24px 0px, rgba(0, 0, 0, 0.08) 0px 0px 0px 1px;
        }

    }
    &__item{
        &:last-child{
            &:before{
                content: '';
                position: absolute;
                opacity: 0;
                z-index: -1;
                top: 0;
                left: 0;
                height: 2.5rem;
                width: 5.5rem;
                background: var(--brand-color);
                border-radius: $borderRadius * 1.75;
                transition: $timing cubic-bezier(1, 0.2, 0.1, 1.2) all;

            }
        }

        @for $i from 1 to 12 {
            &:first-child:nth-last-child(#{$i}),
            &:first-child:nth-last-child(#{$i}) ~ li {
                &:hover {
                    ~ li:last-child:before {
                        opacity: 1;
                    }
                }
                &:last-child:hover:before {
                    opacity: 1;
                }
                @for $j from 1 to $i {
                    &:nth-child(#{$j}):hover, {
                        ~ li:last-child:before {
                            @include gooeyEffect($j);
                            left: (100% / $i) * ($j - 1);
                            animation: gooeyEffect-#{$j} $timing 1;
                        }
                    }
                }
                &:last-child:hover:before, {
                    @include gooeyEffect($i);
                    left: (100% / $i) * ($i - 1);
                    animation: gooeyEffect-#{$i} $timing 1;
                }
            }
        }

    }

}

</style>
