<template>
    <div>
        <section ref="selection" class="md-section md-skin-dark md-text-center section-giaiphap" style="background-color:#0095da;">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-xs-offset-0 col-sm-offset-0 col-md-offset-0 col-lg-offset-2 ">
                        <div class="select-plan__title h2">
                            <select2 class="select-plan__select select2Js" v-model="targetName" @change="show = false">
                                <option value="" v-if="!targetName">Lựa chọn kế hoạch</option>
                                <option v-for="(args, key) in objectives" :value="key">{{ args.name }}</option>
                            </select2>
                        </div>

                        <button class="btn btn-secondary btn-lg btn-shadow" type="submit" @click.prevent="showMainLayout">
                            Lập kế hoạch
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <section v-if="show && targetName" ref="main" class="md-section accumulationbox" style="background-color:#0095da;">
            <div class="container">
                <planning-form
                    :objectives="objectives"
                    :target-name="targetName"
                    @scroll-top="scrollToTop"
                />
            </div>
        </section>
    </div>
</template>

<script>
  const staticData = window._financePlanStatic || {};

  export default {
    name: 'tools',

    data: () => ({
      show: false,
      targetName: '',
      objectives: {},
    }),

    mounted() {
      this.objectives = staticData.objectives || {};

      setTimeout(() => {
        this.targetName = staticData.current || '';

        if (this.targetName) {
          this.showMainLayout();
        }
      }, 10);
    },

    computed: {
      selectedTargetName() {
        if (this.targetName) {
          return this.objectives[this.targetName].name;
        }
      },
    },

    methods: {
      showMainLayout() {
        if (!this.targetName) {
          this.$toasted.show('Bạn cần lựa chọn khoản tích lũy của mình');
          return;
        }

        this.show = true;
        let title = this.selectedTargetName + ' - Bảo Việt Nhân Thọ';
        document.title = title;

        window.history.pushState(
          {}, title, '/lap-ke-hoach/' + this.targetName,
        );

        setTimeout(() => { window.scrollToElement(this.$refs.main, { duration: 350 }); }, 150);
      },

      scrollToTop() {
        window.scrollToElement(this.$refs.selection, { duration: 350 });
      },
    },
  };
</script>
