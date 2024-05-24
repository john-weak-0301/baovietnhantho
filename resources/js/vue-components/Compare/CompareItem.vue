<template>
    <table class="table">
        <thead>
            <th>{{ name }}</th>
            <th :colspan="length === 3 ? 3 : length+1" @click.prevent="show=!show">
                <span class="table-min-btn">
                    <span v-if="show">
                        Thu gọn
                        <i class="fa fa-angle-down"></i>
                    </span>

                    <span v-if="!show">
                        Mở rộng
                        <i class="fa fa-angle-up"></i>
                    </span>
                </span>
            </th>
        </thead>

        <tbody v-show="show">
            <tr>
                <td></td>

                <td v-for="product in products" :key="'product'+product.id">
                    <div v-html="getAttributeBy(product, keyname)"></div>
                </td>

                <td v-if="length < 3"></td>
            </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        name: 'compare-item',

        props: ['name', 'keyname'],

        data: () => ({
            show: true,
        }),

        computed: {
            length() {
                return this.products.length;
            },

            products() {
                const products = this.$parent.products;

                return Array.isArray(products) ? products.slice(0, 3) : [];
            },
        },

        methods: {
            getAttributeBy(product, key) {
                const attributes = product.compare_attributes;

                if (attributes && attributes.hasOwnProperty(key)) {
                    return attributes[key] ? attributes[key] : '-';
                }

                return '-';
            },
        },
    }
</script>
