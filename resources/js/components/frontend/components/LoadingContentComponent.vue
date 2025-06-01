<template>
    <div v-if="props.isActive" class="content-loader">
        <div class="loader-container">
            <img src="/images/loader/Pizza_spinning.gif" alt="loading"  />
        </div>
    </div>
</template>

<script>
export default {
    name: "LoadingContentComponent",
    props: ['props'],
    computed: {
        setting: function () {
            return this.$store.getters['frontendSetting/lists'];
        }
    }
}
</script>

<style scoped>
.content-loader {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 999;
}
.loader-container {
    text-align: center;
}
.logo-spinner {
    width: 80px;
    animation: pulse 1.5s linear infinite;
}
@keyframes pulse {
    0% { transform: scale(1); opacity: 1; }
    50% { transform: scale(0.8); opacity: 0.8; }
    100% { transform: scale(1); opacity: 1; }
}
</style>
