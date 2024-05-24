<template>
    <div class="d-card"><slot /></div>
</template>

<style>
    .d-card {
        background-color: #f9f9f9;
        box-shadow: 0 2px 4px 0 rgba(0,0,0,.05);
        border-radius: .5rem
    }

    .d-card-panel {
        height: 150px;
        position: relative;
    }
</style>
