<template>
    <div v-if="props.isActive" class="full-screen-loader">
        <div class="loader-container">
            <img src="/images/pizza-spinner.png" alt="loading" class="logo-spinner" />
        </div>
    </div>
</template>

<script>
export default {
    name: "LoadingComponent",
    props: ['props']
}
</script>

<style scoped>
.full-screen-loader {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}
.loader-container {
    text-align: center;
}
.logo-spinner {
    width: 120px;
    animation: spin 2s linear infinite;
}
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
