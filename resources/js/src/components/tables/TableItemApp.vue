<template>
    <tr>
        <td>
            <div class="table__media media justify-content-center">
                <div class="media__img">
                    <img :src="item.image" alt="">
                </div>
                <div class="media__text">
                    {{ item.title }}
                </div>
            </div>
        </td>
        <td>
            <span class="badge badge-dot mr-4 d-flex align-items-center justify-content-center gap-2">
                <template v-if="item.test_user.status === TestStatus.Pending">
                    <i class="bg-warning"></i>
                    Не проверен
                </template>
                <template v-else>
                    <i class="bg-success"></i>
                    Проверен
                </template>
            </span>
        </td>
        <td class="text-center fw-medium">
            {{ item.test_user.attempt}}
        </td>
        <td class="text-center fw-medium">
            {{ item.test_user.score}}
        </td>
        <td class="text-center fw-medium">
            {{ item.test_user.percent}}
        </td>
        <td>
            <button-link :to="{ name:'show-result', params: {id: item.id}}">Перейти</button-link>
        </td>
    </tr>
</template>

<script>
import ButtonLink from "@/components/UI/ButtonLink.vue";
import {TestStatus} from "@/models/test.ts";

export default {
    name: "TableItemApp",
    computed: {
        TestStatus() {
            return TestStatus
        }
    },
    components: {ButtonLink},
    props: {
        item: {
            required: true
        }
    },
}
</script>

<style lang="scss">

</style>
