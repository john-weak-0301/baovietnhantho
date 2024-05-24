<template>
    <div class="row" v-if="targetName">
        <div class="col-lg-10 col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-1">
            <div class="row">
                <div class="col-lg-6 col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-3">
                    <div class="tool-header-section-fix md-skin-dark">
                        <h2 class="section-title-min text-center">Lập kế hoạch tài chính</h2>

                        <h3 v-if="isPensions" class="accumulationbox__title">
                            Khoản lương hưu bạn muốn nhận theo định kỳ
                        </h3>

                        <h3 v-else class="accumulationbox__title">
                            Bạn dự kiến sẽ tích luỹ ra số tiền là?
                        </h3>
                    </div>
                </div>
            </div>

            <div class="accumulationbox__form">
                <div class="accumulationbox__header">
                    <div class="form-control-group">
                        <cleave
                            v-model="targetAmount"
                            :options="cleaveOptions"
                            :class="getInputClass('targetAmount')"
                            class="form-control"
                            placeholder="500.000.000" />
                        <span class="form-unit">đ</span>
                    </div>
                </div>

                <div class="accumulationbox__content">
                    <div class="accumulationbox__item">
                        <div class="row">
                            <div class="col-lg-8 col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-2 ">
                                <div class="accumulationbox__form-item">
                                    <input-money
                                        v-model="currentAccumulationAmount"
                                        :label="'Bạn đã tích luỹ được số tiền'"
                                        :hasError="this.validationErrors.has('currentAccumulationAmount')"
                                        placeholder="0"
                                    />

                                    <input-money
                                        v-model="currentDebtAmount"
                                        :label="'Giá trị các khoản nợ hiện tại'"
                                        :hasError="this.validationErrors.has('currentDebtAmount')"
                                        placeholder="0"
                                    />
                                </div>

                                <div class="accumulationbox__form-item" v-if="!isPensions">
                                    <label>Thời gian Bạn cần chuẩn bị để đạt được mục tiêu</label>

                                    <div class="rangerbox">
                                        <div class="rangerbox-handle">{{ estimatedYear }}</div>
                                        <input type="range" class="range-input" min="1" step="1" max="50" v-model.number="estimatedYear">
                                    </div>
                                </div>

                                <div class="accumulationbox__form-item" v-if="isPensions">
                                    <div class="form-input-group">
                                        <label>Định kỳ nhận lương hưu</label>

                                        <div class="inners">
                                            <div class="form-checkbox-group">
                                                <label class="form-checkbox" v-for="(name, key) in subscriptions">
                                                    <input type="radio" name="subscription" :value="key" v-model="subscription">
                                                    <span>{{ name }}</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-input-group">
                                        <label>Tuổi hiện tại:</label>

                                        <div class="inners">
                                            <div class="form-control-min-input">
                                                <input
                                                    type="number"
                                                    class="form-control"
                                                    placeholder="55"
                                                    :class="getInputClass('currentAge')"
                                                    v-model.number="currentAge"
                                                    min="35"
                                                    max="75"
                                                    step="1">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-input-group">
                                        <label>Nhận lương hưu từ:</label>

                                        <div class="inners">
                                            <div class="form-input-range">
                                                <div class="form-control-min-input">
                                                    <input
                                                        type="number"
                                                        class="form-control"
                                                        placeholder="80"
                                                        v-model.number="pensionsAgeFrom"
                                                        :class="getInputClass('pensionsAgeFrom')"
                                                        min="50"
                                                        max="99"
                                                        step="1">
                                                </div>

                                                <span>đến</span>

                                                <div class="form-control-min-input">
                                                    <input
                                                        type="number"
                                                        class="form-control"
                                                        placeholder="35"
                                                        v-model.number="pensionsAgeTo"
                                                        :class="getInputClass('pensionsAgeTo')"
                                                        min="16"
                                                        max="80"
                                                        step="1">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accumulationbox__form-item">
                                    <label>Tỉ lệ lạm phát hằng năm</label>

                                    <div class="rangerbox">
                                        <div class="rangerbox-handle">{{ inflationRate }}</div>
                                        <input type="range" class="range-input" min="5" step="1" max="10" v-model.number="inflationRate">
                                    </div>
                                </div>

                                <div class="accumulationbox__form-item" style="display: flex;">
                                    <label class="mb-0">Lãi xuất giả định:
                                        <strong>{{ interest }}%</strong></label>

                                    <div class="text-right" style="flex-grow: 1;">
                                        <button class="btn btn-secondary btn-lg btn-shadow" type="button" @click="submit">
                                            Lập kế hoạch
                                        </button>
                                    </div>
                                </div>

                                <collapse-transition>
                                    <div v-if="resultAmount" class="pt-20">
                                        <div v-if="resultAmount != 0" class="total-amount">
                                            <span class="total-amount__subtitle">Bạn cần phải tiết kiệm<br>từ bây giờ với số tiền</span>

                                            <div class="total-amount__price">
                                                <span>{{ resultAmount }} đ</span>/

                                                <i v-if="!isPensions">tháng</i>
                                                <i v-else>{{ subscriptions[subscription] }}</i>
                                            </div>

                                            <p class="total-amount__subtext">
                                                Với kế hoạch tài chính giành cho
                                                <span>{{ selectedTargetName }}</span>
                                                <br>để đạt được khoản tích lũy
                                                <span>{{ formattedTargetAmount }}đ</span>
                                            </p>

                                            <p class="total-amount__text">
                                                <span>*</span> Công cụ báo giá chỉ cung cấp ước
                                                tính. Ước tính dựa trên bảo hiểm y tế tư nhân được ban hành
                                                theo mức phí bảo hiểm tiêu chuẩn
                                            </p>
                                        </div>

                                        <div v-else class="total-amount">
                                            <p class="total-amount__subtitle mb-0" style="color: #0a9e10;">
                                                {{ resultMessage }}
                                            </p>
                                        </div>
                                    </div>
                                </collapse-transition>
                            </div>
                        </div>
                    </div>

                    <div class="accumulationbox__item" v-if="relatedProducts.length > 0">
                        <div class="row">
                            <div class="col-lg-10 col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-1">
                                <h5 class="accumulationbox__itemtitle">Gói bảo hiểm phù hợp với bạn</h5>

                                <div class="row row-eq-height">
                                    <div class="col-lg-6" v-for="(product, index) in relatedProducts" :key="index">
                                        <product-grid :product="product" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accumulationbox__footer">
                    <div class="accumulationbox__btn">
                        <a class="btn btn-light btn-shadow" href="/tu-van" target="_blank">
                            Đăng ký tư vấn
                        </a>

                        <a class="btn btn-light btn-shadow" href="#" @click.prevent="$emit('scroll-top')">
                            Sửa tính toán
                        </a>
                    </div>

                    <p class="accumulationbox__hotline">
                        Tổng đài Chăm sóc Khách
                        hàng:<br><a href="tel:1900 558899" style="color: #fff;"><strong>1900 558899</strong></a>
                        nhánh số 4
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  import _ from 'lodash';
  import numeral from 'numeral';
  import Cleave from 'vue-cleave-component';
  import { Errors } from 'form-backend-validation';
  import minimum from '../../utils/minimum';
  import InputMoney from './MoneyInput';
  import ProductGrid from '../ProductGrid';

  export default {
    props: {
      targetName: {
        type: String,
        required: true,
      },

      objectives: {
        type: Object,
        required: true,
      },

      runImmediate: {
        type: Boolean,
        default: false,
      },

      defaultTargetAmount: {
        // type: Number,
        required: false,
      },

      defaultEstimatedYear: {
        type: Number,
        required: false,
      },

      defaultSubscription: {
        type: String,
        required: false,
      },
    },

    components: {
      Cleave,
      InputMoney,
      ProductGrid,
    },

    data() {
      const isPensions = ['huu-tri', 'huu_tri'].includes(this.targetName);

      return {
        targetAmount: this.defaultTargetAmount || (isPensions ? 8000000 : 500000000),
        currentAccumulationAmount: 0,
        currentDebtAmount: 0,
        inflationRate: 6,
        estimatedYear: this.defaultEstimatedYear || 15,
        currentAge: 35,
        pensionsAgeFrom: 55,
        pensionsAgeTo: 80,
        subscription: this.defaultSubscription || 'monthly',

        resultAmount: null,
        resultMessage: null,
        relatedProducts: [],

        loading: false,
        validationErrors: new Errors(),
      };
    },

    mounted() {
      setTimeout(() => {
        if (this.runImmediate && this.targetAmount && !this.isPensions) {
          this.submit();
        }
      }, 500);
    },

    watch: {
      targetName() {
        this.resultAmount = null;
        this.resultMessage = null;
        this.relatedProducts = [];
        this.validationErrors.clear();

        if (this.isPensions) {
          this.targetAmount = 8000000;
        } else {
          this.targetAmount = 500000000;
        }
      },

      subscription() {
        if (this.resultAmount) {
          this.submit();
        }
      },
    },

    computed: {
      interest() {
        return parseInt(this.inflationRate, 10) + 2;
      },

      selectedTargetName() {
        if (this.targetName) {
          return this.objectives[this.targetName].name;
        }
      },

      formattedTargetAmount() {
        return numeral(this.targetAmount).format('0,0');
      },

      isPensions() {
        return ['huu-tri', 'huu_tri'].includes(this.targetName);
      },

      cleaveOptions: () => ({
        numeral: true,
        numeralPositiveOnly: true,
        numeralDecimalScale: 0,
        numeralDecimalMark: ',',
        delimiter: '.',
      }),

      subscriptions: () => ({
        monthly: 'Tháng',
        quarterly: 'Quý',
        half_yearly: '6 Tháng',
        yearly: 'Năm',
      }),
    },

    methods: {
      async submit() {
        await this.request();
      },

      getInputClass(name) {
        return {
          'input-has-error': this.validationErrors.has(name),
        };
      },

      request() {
        this.loading = true;
        this.resultAmount = null;
        this.resultMessage = null;

        const data = {
          targetName: this.targetName,
          targetAmount: this.targetAmount,
          currentAccumulationAmount: this.currentAccumulationAmount,
          currentDebtAmount: this.currentDebtAmount,
          inflationRate: this.inflationRate,
        };

        if (this.isPensions) {
          data.currentAge = this.currentAge;
          data.subscription = this.subscription;
          data.pensionsAgeFrom = this.pensionsAgeFrom;
          data.pensionsAgeTo = this.pensionsAgeTo;
        } else {
          data.estimatedYear = this.estimatedYear;
        }

        const handleErrors = (error) => {
          if (error.response.status >= 500) {
            this.$toasted.show(error.response.data.message);
            return;
          }

          if (error.response.status === 422) {
            this.validationErrors = new Errors(error.response.data.errors);

            _.each(Object.keys(this.validationErrors.all()), (key) => {
              this.$toasted.show(this.validationErrors.first(key));
            });
          }
        };

        return minimum(
          window.axios.post('/api/v1/finance-plan', data),
        ).then(({ data }) => {
          this.resultAmount = data.data.amount;
          this.resultMessage = data.message;

          if (Array.isArray(data.data.products)) {
            this.relatedProducts = data.data.products;
          }

          this.loading = false;
          this.validationErrors.clear();
        }).catch(error => {
          handleErrors(error);
        });
      },
    },
  };
</script>

<style>
    .input-has-error {
        border-color: #ff5956 !important;
    }

    .inners .form-checkbox {
        margin: 0 5px;
    }
</style>
