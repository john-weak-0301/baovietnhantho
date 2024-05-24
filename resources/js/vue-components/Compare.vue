<template>
    <div>
        <div ref="products">
            <div class="tool-sosanh">
                <h3 class="tool-sosanh__title">Các sản phẩm đã chọn</h3>

                <div class="row" v-if="selectedCount > 0">
                    <div
                        v-for="(product, index) in selectedProducts"
                        :key="index"
                        class="col-xs-6 col-sm-6 col-md-6 col-lg-3 col-xsx-12">
                        <label class="checkbox-02">
                            <input
                                type="checkbox"
                                v-model="selectedProducts"
                                :value="product"
                                name="products[]"
                                class="checkbox-02__input"
                            />

                            <div class="checkbox-02__inner">
                                <span class="checkbox-02__check"></span>
                                <i style="display: none;">{{ product.category.name }}</i>

                                {{ product.title }}
                            </div>
                        </label>
                    </div>
                </div>

                <div v-else>
                    <p class="text-muted" style="color: #999;">
                        Chưa có sản phẩm nào được chọn. <br />
                        Vui lòng lựa chọn ít nhất 02 sản phẩm để so sánh trong danh sách dưới đây.
                    </p>
                </div>
            </div>

            <div class="tool-sosanh-acc">
                <div class="tool-sosanh-acc__item">
                    <h3
                        class="tool-sosanh-acc__title"
                        :class="{'tool-sosanh-acc__title--active': showHiddenItems}"
                        @click="showHiddenItems=!showHiddenItems">
                        Danh sách các sản phẩm
                        <i class="fa fa-angle-down"></i>
                    </h3>

                    <collapse-transition :duration="200">
                        <div class="tool-sosanh-acc__body" style="display: block;" v-if="showHiddenItems">

                            <div class="tool-sosanh"
                                 v-for="(_products, group) in productsWithCategory"
                                 :key="group"
                            >
                                <h3 class="tool-sosanh__title">{{ group }}</h3>

                                <div class="row">
                                    <div
                                        v-for="(product, i) in _products"
                                        :key="i"
                                        class="col-xs-6 col-sm-6 col-md-6 col-lg-3 col-xsx-12"
                                    >
                                        <label class="checkbox-02">
                                            <input
                                                type="checkbox"
                                                :value="product"
                                                :disabled="shouldDisableCheckbox(product)"
                                                v-model="selectedProducts"
                                                name="products[]"
                                                class="checkbox-02__input"
                                            />

                                            <div class="checkbox-02__inner">
                                                <span class="checkbox-02__check"></span>
                                                {{ product.title }}
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </collapse-transition>
                </div>
            </div>
        </div>

        <div class="text-center mb-50">
            <button
                type="button"
                class="btn"
                :class="{'btn-gray': !isReady, 'btn-secondary': isReady}"
                :disabled="!isReady"
                @click.prevent="compare()"
            >
                So sánh ngay
            </button>
        </div>

        <div rel="table" class="table-sosanh-responsive" v-if="show && isReady">
            <div class="table-sosanh accor-table">
                <table class="table">
                    <thead>
                        <tr>
                            <th></th>

                            <th v-for="(product, index) in products" :key="'product'+product.id">
                                {{ product.title }}

                                <span class="table-sosanh-close" @click.prevent="remove(index)">
                                    <i class="fa fa-close"></i>
                                </span>
                            </th>

                            <th v-if="selectedCount < 3">
                                <span @click.prevent="add()" class="table-sosanh-add">
                                    <i class="fa fa-plus-circle"></i> Thêm sản phẩm
                                </span>
                            </th>
                        </tr>
                    </thead>
                </table>

                <div class="table-sosanh-content">
                    <compare-item
                        v-for="(name, key) in attributes"
                        :key="key"
                        :name="name"
                        :keyname="key"
                    />

                    <table class="table table-footer">
                        <thead>
                            <th><a class="btn-link" href="#" @click.prevent="flush()">Hủy so sánh</a></th>

                            <th v-for="(product, index) in products" :key="'product'+product.id">
                                <a class="btn btn-light btn-lg" :href="product.register_link || '/tu-van'">
                                    Đăng ký ngay
                                </a>
                            </th>

                            <th v-if="selectedCount < 3" class="empty"></th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <div class="table-sosanh-mobile" v-if="show && isReady">
            <ul class="item" v-for="(product, index) in products" :key="'product'+product.id">
                <li>{{ product.title }}</li>

                <li v-for="(name, key) in attributes" :key="'mobile'+product.id+key">
                    <span>{{ name }}</span>
                    <div v-html="getAttributeBy(product, key)"></div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
  import _ from 'lodash';
  import scrollToElement from 'scroll-to-element';
  import {CollapseTransition} from 'vue2-transitions';
  import CompareItem from './Compare/CompareItem';

  export default {
    name: 'compare',
    props: ['attributes'],

    components: {
      CompareItem,
      CollapseTransition,
    },

    data() {
      const data = window._compareData || {};

      return {
        show: false,
        showHiddenItems: false,

        selectedProducts: data.compareProducts || [],
        productsWithCategory: data.productsWithCategory || [],
      };
    },

    mounted() {
      if (this.selectedCount === 0) {
        this.showHiddenItems = true;
      }
    },

    watch: {
      products() {
        if (this.selectedCount < 2) {
          this.show = false;
        }
      },
    },

    computed: {
      products() {
        return this.selectedProducts.slice(0, 3);
      },

      selectedCount() {
        return this.selectedProducts.length;
      },

      isReady() {
        return this.selectedCount >= 2;
      },
    },

    methods: {
      compare() {
        if (this.selectedCount <= 1) {
          alert('Bạn cần chọn ít nhất 1 sản phẩm để so sánh!');
          return;
        }

        this.show = true;
        this.showHiddenItems = false;
      },

      add() {
        if (this.selectedCount >= 3) {
          alert('Hãy chọn tối đa 3 sản phẩm');
          return;
        }

        this.showHiddenItems = true;
        this.scrollTop();
      },

      flush() {
        this.show = false;
        this.selectedProducts = [];
        this.scrollTop();
      },

      remove(index) {
        this.$delete(this.selectedProducts, index);
      },

      shouldDisableCheckbox(product) {
        if (this.selectedCount >= 3) {
          return !_.find(this.selectedProducts, {id: product.id});
        }

        return false;
      },

      scrollTop() {
        setTimeout(() => scrollToElement(this.$refs.products, {duration: 200}), 50);
      },

      getAttributeBy(product, key) {
        const attributes = product.compare_attributes;

        if (attributes && attributes.hasOwnProperty(key)) {
          return attributes[key] ? attributes[key] : '-';
        }

        return '-';
      },
    },
  };
</script>

<style scoped>
    .checkbox-02__inner > i {
        position: absolute;
        top: 0;
        right: 0;
        font-size: 11px;
        font-style: normal;
        display: inline-block;
        padding: 6px 10px;
        color: #ccecfb;
        font-weight: normal;
        text-transform: uppercase;
    }

    .checkbox-02__input[disabled] + .checkbox-02__inner {
        color: #999;
        background-color: #f9f9f9;
    }
</style>
