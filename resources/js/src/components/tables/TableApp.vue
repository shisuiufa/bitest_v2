<template>
    <div class="wrap-table">
        <table class="table">
            <thead>
            <tr>
                <th class="text-center" scope="col">Тест</th>
                <th class="text-center" scope="col">Статус</th>
                <th class="text-center" scope="col">Попытка</th>
                <th class="text-center" scope="col">Балл</th>
                <th class="text-center" scope="col">Процент</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            <TableItemApp :item="item"
                            v-for="(item, index) in items" :key="index">
            </TableItemApp>
            </tbody>
        </table>
    </div>
</template>

<script>
import TableItemApp from "./TableItemApp.vue";
 export default {
     name: "TableApp",
     props: {
         items: {
             type: Array,
             required: true,
         }
     },
     components: {
         TableItemApp,
     }
 }
</script>

<style scoped lang="scss">
.wrap-table {
    //overflow: auto;
    //max-height: 450px;
    scroll-behavior: smooth;
}

.table {
    margin: 0;

    thead {
        position: sticky;
        top: 0;
        z-index: 1;
        color: var(--main-color);
    }

    tbody {
        color: var(--main-color);
    }

    th, td {
        color: inherit;
        background-color: inherit;
        vertical-align: middle;
    }

    .media {
        display: flex;
        align-items: center;
        gap: 10px;

        &__img {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            overflow: hidden;

            img {
                object-fit: cover;
                width: inherit;
                height: inherit;
            }
        }

    }

    .badge-dot {
        font-size: inherit;
        font-weight: 400;
        padding-right: 0;
        padding-left: 0;
        text-transform: none;
        background: transparent;
        color: var(--main-color);

        i {
            display: inline-block;
            width: 0.375rem;
            height: 0.375rem;
            margin-right: 0.375rem;
            vertical-align: middle;
            border-radius: 50%;
        }
    }
}
</style>
