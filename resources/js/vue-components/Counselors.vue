<template>
    <div>
        <form-wizard ref="wizard" :title="''" :subtitle="''" @on-change="onStepChange">
            <tab-content :beforeChange="validateStep1">
                <div class="tool-panel">
                    <div class="tool-panel__header">
                        <div class="tool-panel__header-fix">
                            <h3 class="tool-panel__title">Chọn chuyên viên tư vấn cho riêng bạn</h3>
                            <p class="tool-panel__subtitle">Chủ động đặt hẹn với chuyên viên tài chính Bảo Việt để được lắng nghe và thấu hiểu</p>
                        </div>
                    </div>

                    <div class="tool-panel__content">
                        <div class="tool-panel__item">
                            <div class="form-info-user first">
                                <div class="form-info-user__item">
                                    <span class="form-info-user__label">và tôi đang sống tại</span>

                                    <select class="form-control" v-model="guestProvinceCode" @change="onProvinceChange">
                                        <option v-if="provinces" v-for="province in provinces" :value="province.code">{{ province.name }}</option>
                                    </select>

                                    <select class="form-control" v-model="guestDistrictCode" ref="district">
                                        <option value="" selected>Quận/Huyện</option>
                                        <option v-if="districts" v-for="district in districts" :value="district.code">{{ district.name_with_type }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="tool-panel__item">
                            <div class="tool-panel__itemtitle">Bạn muốn người tư vấn cho mình là</div>

                            <div class="row">
                                <div class="col-sm-6 col-md-4 col-lg-4 col-xs-offset-0 col-sm-offset-3 col-md-offset-4 col-lg-offset-4">
                                    <div class="row">
                                        <div class="col-xs-6 ">
                                            <label class="gendercheck">
                                                <input class="gendercheck__input" type="radio" name="gender" value="men" checked v-model="selectedGender">
                                                <div class="gendercheck__inner">
                                                    <span class="gendercheck__check"></span>
                                                    <div class="gendercheck__icon">
                                                        <img src="/img/svg/icon-nam.svg" alt="Men">
                                                    </div>
                                                </div>
                                            </label>
                                        </div>

                                        <div class="col-xs-6 ">
                                            <label class="gendercheck">
                                                <input class="gendercheck__input" type="radio" name="gender" value="women" v-model="selectedGender">
                                                <div class="gendercheck__inner">
                                                    <span class="gendercheck__check"></span>
                                                    <div class="gendercheck__icon">
                                                        <img src="/img/svg/icon-nu.svg" alt="Woman">
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </tab-content>

            <tab-content :beforeChange="validateStep2">
                <div class="tool-panel">
                    <div class="tool-panel__header">
                        <div class="tool-panel__header-fix">
                            <h3 class="tool-panel__title title-inline">
                                Bạn đã chọn nhân viên tư vấn là

                                <select class="form-control" name="gender" v-model="selectedGender" @change="onSelectedGenderChange">
                                    <option value="men" :selected="selectedGender === 'men'">Nam</option>
                                    <option value="women" :selected="selectedGender === 'women'">Nữ</option>
                                </select>

                                để tư vấn cho mình
                            </h3>
                        </div>
                    </div>

                    <div class="tool-panel__content">
                        <div class="tool-panel__item">
                            <div class="tool-panel__itemtitle">Một số chuyên gia được gợi ý cho bạn</div>

                            <div class="expert-selectbox-wrap">
                                <div v-if="loading" class="row">
                                    <div class="col-xs-6 col-lg-3" v-for="i in [1, 2, 3, 4]">
                                        <content-loader
                                            :height="250"
                                            :width="210"
                                            :speed="1"
                                            primaryColor="#f3f3f3"
                                            secondaryColor="#dddddd"
                                        >
                                            <rect x="0" y="0" rx="0" ry="0" width="210" height="210" />
                                            <rect x="30" y="230" rx="0" ry="0" width="150" height="15" />
                                        </content-loader>
                                    </div>
                                </div>

                                <div v-if="isEmptyCounselors">
                                    <p class="text-center">Xin lỗi, không tìm thấy chuyên viên phù hợp với lựa chọn của bạn, vui lòng thử lại!</p>
                                </div>

                                <div class="row" v-else>
                                    <div class="col-xs-6 col-lg-3" v-for="(_counselor, index) in counselors" :key="index">
                                        <label class="expert-selectbox">
                                            <input class="expert-selectbox__input" type="radio" :value="_counselor.id" v-model="counselor">

                                            <div
                                                :style="{backgroundImage: 'url('+_counselor.avatar+')'}"
                                                class="expert-selectbox__inner">
                                                <span class="expert-selectbox__check"></span>
                                            </div>

                                            <h6 class="expert-selectbox__name">{{ _counselor.display_name }}</h6>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </tab-content>

            <tab-content :before-change="validateStep3">
                <div class="tool-panel">
                    <div class="tool-panel__header">
                        <div class="tool-panel__header-fix">
                            <h3 class="tool-panel__title title-inline">Chọn một thời điểm thích hợp để buổi tư vấn đạt được hiệu quả nhất</h3>
                        </div>
                    </div>

                    <div class="tool-panel__content">
                        <div class="tool-panel__item">
                            <div class="tool-panel__itemtitle">Chúng tôi có thể liên lạc với bạn vào lúc nào</div>
                            <div class="calendar-book">
                                <ul class="calendar-book__times">
                                    <li><span class="calendar-book__time">Sáng <span></span>(8h -11h)</span>
                                    </li>
                                    <li><span class="calendar-book__time">Chiều <span></span>(14h -17h)</span>
                                    </li>
                                    <li><span class="calendar-book__time">Tối <span></span>(18h -21h)</span>
                                    </li>
                                </ul>

                                <ul class="calendar-book__days">
                                    <li v-for="(name, key) in days" :data-title="name">
                                        <div class="calendar-book__list">
                                            <label class="checkbox">
                                                <input class="checkbox__input" type="checkbox" value="1" v-model="selectedDates[key].morning">
                                                <div class="checkbox__inner"></div>
                                            </label>
                                        </div>

                                        <div class="calendar-book__list">
                                            <label class="checkbox">
                                                <input class="checkbox__input" type="checkbox" value="1" v-model="selectedDates[key].afternoon">
                                                <div class="checkbox__inner"></div>
                                            </label>
                                        </div>

                                        <div class="calendar-book__list">
                                            <label class="checkbox">
                                                <input class="checkbox__input" type="checkbox" value="1" v-model="selectedDates[key].night">
                                                <div class="checkbox__inner"></div>
                                            </label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </tab-content>

            <tab-content>
                <div class="tool-panel">
                    <div class="tool-panel__header">
                        <div class="tool-panel__header-fix">
                            <h3 class="tool-panel__title title-inline">Điền một số thông tin của bạn sẽ giúp chúng tôi hỗ trợ tư vấn tốt hơn</h3>
                        </div>
                    </div>

                    <div class="tool-panel__content">
                        <div class="tool-panel__item">
                            <div class="form-info-user">
                                <div class="form-info-user__item">
                                    <span class="form-info-user__label">Tên tôi là</span>
                                    <input type="text" required class="form-control" placeholder="Nguyễn Van Anh" v-model="guestName">
                                </div>

                                <div class="form-info-user__item">
                                    <span class="form-info-user__label">Có thể liên lạc với tôi theo số</span>
                                    <input type="text" class="form-control" required placeholder="(+84) 911 2233 444" v-model="guestPhoneNumber">
                                </div>

                                <div class="form-info-user__item">
                                    <span class="form-info-user__label">Nội dung cần tư vấn</span>
                                    <input type="text" class="form-control" required v-model="selectedConsult">
                                </div>

                                <div v-if="recaptchaSiteKey" style="display: flex; justify-content: center; padding-top: 15px;">
                                    <vue-recaptcha
                                        ref="captcha"
                                        :sitekey="recaptchaSiteKey"
                                        @verify="verifyCaptcha"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </tab-content>

            <template slot="footer" slot-scope="props">
                <div class="tool-panel__btn" style="margin-top: 50px;">
                    <wizard-button class="btn btn-light" v-if="props.activeTabIndex > 0" @click.native="props.prevTab()">
                        Quay lại
                    </wizard-button>

                    <wizard-button class="btn btn-secondary" type="submit" :disabled="disabledNext" v-if="!props.isLastStep" @click.native="props.nextTab()">
                        Tiếp theo
                    </wizard-button>

                    <wizard-button v-else @click.native="submit" class="wizard-footer-right btn btn-secondary finish-button">
                        Hoàn tất
                    </wizard-button>
                </div>
            </template>
        </form-wizard>
    </div>
</template>

<script>
  import _ from 'lodash';
  import VueRecaptcha from 'vue-recaptcha';
  import { Errors } from 'form-backend-validation';
  import { ContentLoader } from 'vue-content-loader';

  import minimum from '../utils/minimum';

  function allFalse(data) {
    let result = true;
    data = Object.values(data);

    for (let i in data) {
      if (data[i] === true) {
        result = false;
        break;
      }
    }

    return result;
  }

  export default {
    components: {
      VueRecaptcha,
      ContentLoader,
    },

    data() {
      const provinces = window._counselorsStaticData.provinces || [];

      return {
        loading: true,
        disabledNext: false,
        errors: new Errors(),
        recaptchaSiteKey: window._recaptchaSiteKey,
        recaptchaResponseKey: null,

        counselor: null,
        counselors: null,
        provinces: provinces,
        districts: null,

        guestName: '',
        guestPhoneNumber: '',
        guestProvinceCode: '01',
        guestDistrictCode: '001',
        selectedConsult: '',
        forceShowConsults: false,
        selectedGender: 'men',
        selectedDates: {
          'monday': {},
          'tuesday': {},
          'wednesday': {},
          'thursday': {},
          'friday': {},
          'saturday': {},
        },

        days: {
          'monday': 'Thứ 2',
          'tuesday': 'Thứ 3',
          'wednesday': 'Thứ 4',
          'thursday': 'Thứ 5',
          'friday': 'Thứ 6',
          'saturday': 'Thứ 7',
        },
      };
    },

    mounted() {
      setTimeout(() => {
        this.onProvinceChange(this.guestProvinceCode);
      }, 10);
    },

    computed: {
      isEmptyCounselors() {
        return !this.loading && (!this.counselors || this.counselors.length === 0);
      },

      isEmptySelectedDates() {
        return _.isEmpty(
          _.filter(this.selectedDates, (d) => !allFalse(d)),
        );
      },

      formData() {
        return {
          counselor_id: this.counselor,
          customer_name: this.guestName,
          customer_phone: this.guestPhoneNumber,
          customer_address: this.guestProvinceCode + '|' + this.guestDistrictCode,
          private_note: this.selectedConsult,
          'data': this.selectedDates,
          'g-recaptcha-response': this.recaptchaResponseKey,
        };
      },
    },

    methods: {
      onStepChange(prevIndex, nextIndex) {
        // console.log(prevIndex, nextIndex)
      },

      async validateStep1() {
        if (!this.guestProvinceCode) {
          alert('Vui lòng lựa chọn khu vực của bạn!');
          return false;
        }

        if (!this.guestDistrictCode) {
          alert('Vui lòng lựa chọn khu vực quận/huyện của bạn!');
          return false;
        }

        if (!this.selectedGender) {
          this.selectedGender = 'men';
        }

        this.counselor = null;
        await this.getCounselor();

        return true;
      },

      validateStep2() {
        if (this.counselors.length && !this.counselor) {
          alert('Bạn cần chọn cho mình một chuyên viên');
          return false;
        }

        return true;
      },

      validateStep3() {
        if (this.isEmptySelectedDates) {
          alert('Vui lòng chọn ít nhất 01 khoảng thời gian');
          return false;
        }

        return true;
      },

      verifyCaptcha(token) {
        this.recaptchaResponseKey = token;
      },

      submit() {
        this.loading = true;
        this.errors.clear();

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

        let redirectTimeout = null;
        const redirectSuccess = () => {
          if (redirectTimeout) {
            clearTimeout(redirectTimeout);
          }

          redirectTimeout = setTimeout(function() {
            window.location.href = '/thank-you';
          }, 500);
        };

        minimum(
          window.axios.post('/api/v1/consultants', this.formData)
        ).then(() => {
          this.loading = false;

          if (this.validationErrors) {
            this.validationErrors.clear();
          }

          redirectSuccess();
        }).catch(error => {
          this.recaptchaResponseKey = '';

          if (this.$refs.captcha) {
            this.$refs.captcha.reset();
          }

          if (error.response) {
            handleErrors(error);
          }
        });
      },

      async getCounselor() {
        this.loading = true;
        this.counselors = null;

        const params = {
          gender: this.selectedGender,
          province: this.guestProvinceCode,
          district: this.guestDistrictCode,
        };

        return minimum(
          axios.post('/api/v1/counselors', params),
        ).then((response) => {
          this.counselors = response.data;
          this.loading = false;

          setTimeout(() => {
            if (this.isEmptyCounselors && !this.forceShowConsults) {
              this.$refs.wizard.changeTab(0, 2);
            }
          }, 100);
        }).catch(error => {
          console.error(error);
        });
      },

      async onSelectedGenderChange() {
        if (this.forceShowConsults === false) {
          this.forceShowConsults = true;
        }

        await this.getCounselor();
      },

      async onProvinceChange(e) {
        const province = e.target ? e.target.value : e;
        const districtSelect = this.$refs.district;

        districtSelect.disabled = true;

        await this.refreshDistricts(province).then(res => {
          this.districts = res.data;
        }).catch(err => {
          this.districts = [];
        });

        districtSelect.disabled = false;
      },

      async refreshDistricts(province) {
        return minimum(
          window.axios(`/data/subdivision/${province}.json`),
        );
      },
    },
  };
</script>

<style>
    .wizard-header,
    .wizard-nav {
        display: none;
    }

    .swal2-popup {
        font-size: 14px;
    }
</style>
