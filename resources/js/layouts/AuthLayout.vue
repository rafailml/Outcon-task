<template>
    <div class="layout-content ma-auto w-full">
        <slot></slot>
    </div>
</template>

<script>
export default {};
</script>

<style scoped>
.layout-side {
    width: 420px;
}

.layout-content {
    min-width: 480px;
}
</style>
