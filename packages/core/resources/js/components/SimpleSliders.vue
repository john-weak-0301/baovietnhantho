<template>
    <div>
        <el-tabs v-model="currentTab" type="border-card" editable @edit="handleTabsEdit">
            <el-tab-pane
                v-for="(tab, index) in tabs"
                :key="tab.name"
                :label="tab.title"
                :name="tab.name"
            >
                <simple-slider-form :slide="tab.slide" :index="index" />
            </el-tab-pane>
        </el-tabs>
    </div>
</template>

<script>
  import SimpleSliderForm from './SimpleSliderForm';

  export default {
    props: ['sliders'],

    components: {
      SimpleSliderForm,
    },

    data: () => ({
      tabs: [],
      tabIndex: 0,
      currentTab: '1',
    }),

    mounted() {
      _.forEach(this.sliders, (slide) => {
        this.addTabs(slide);
      });

      this.ensureHaveTab();
    },

    methods: {
      addTabs(slide) {
        let name = ++this.tabIndex + '';

        this.tabs.push({
          name,
          slide,
          title: 'Slide ' + name,
        });
      },

      handleTabsEdit(targetName, action) {
        let tabs = this.tabs;

        if (action === 'add') {
          this.addTabs({});
          this.currentTab = this.tabIndex + '';
        }

        if (action === 'remove') {
          if (!confirm('Bạn có chắc muốn xóa?')) {
            return false;
          }

          let activeName = this.currentTab;

          if (activeName == targetName) {
            tabs.forEach((tab, index) => {
              if (tab.name == targetName) {
                let nextTab = tabs[index + 1] || tabs[index - 1];
                if (nextTab) {
                  activeName = nextTab.name;
                }
              }
            });
          }

          this.currentTab = activeName;
          this.tabs = tabs.filter(tab => tab.name != targetName);

          this.ensureHaveTab();
        }
      },

      ensureHaveTab() {
        if (this.tabs.length === 0) {
          this.tabIndex = 0;
          this.currentTab = '1';
          this.addTabs({});
        }
      },
    },
  };
</script>
