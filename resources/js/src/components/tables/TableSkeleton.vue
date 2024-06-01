<script>
export default {
    name: "TableSkeleton",
    data() {
        return {
            products: new Array(6)
        };
    }
}
</script>

<template>
    <DataTable :value="products">
        <Column field="code" header="">
            <template #body>
                <Skeleton></Skeleton>
            </template>
        </Column>
        <Column field="name" header="">
            <template #body>
                <Skeleton></Skeleton>
            </template>
        </Column>
        <Column field="category" header="">
            <template #body>
                <Skeleton></Skeleton>
            </template>
        </Column>
        <Column field="quantity" header="">
            <template #body>
                <Skeleton></Skeleton>
            </template>
        </Column>
    </DataTable>
</template>
