<template>
    <div class="new">
        <div class="new__inner">
            <div class="new__media" :style="inlineStyle">
                <img :src="product.image_url" :alt="product.title">
                <a class="new__cat" v-if="category" :href="category.url" :title="category.name">{{ category.name }}</a>
            </div>

            <div class="new__body">
                <h3 class="new__title">
                    <a :href="url" :title="product.title">{{ product.title }}</a>
                </h3>

                <p class="new__text">{{ product.excerpt }}</p>

                <a class="btn btn-secondary" :href="url">Tìm hiểu ngay</a>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'product-grid',

        props: {
            product: {
                type: Object,
                required: true,
            },
        },

        computed: {
            url () {
                return this.product.url || '/san-pham/' + this.product.slug
            },

            category () {
                return this.product.category || null
            },

            inlineStyle() {
                const imageUrl = this.product.image_url || this.product.image;

                return {
                    backgroundImage: `url('${imageUrl}')`
                }
            },
        },
    }
</script>
