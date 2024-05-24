<template>
    <div class="el-form" :class="[
        labelPosition ? 'el-form--label-' + labelPosition : '',
        { 'el-form--inline': inline }]"
    >
        <slot></slot>
    </div>
</template>

<script>
    import {Form} from 'element-ui';

    export default {
        name: 'ElForm',

        extends: Form,
    };
</script>
