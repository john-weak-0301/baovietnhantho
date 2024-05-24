<template>
    <div class="branch-map__wrap">
        <div class="branch-map__map">
            <GmapMap
                ref="maps"
                :center="center"
                :zoom="zoom"
                style="width:100%; height:100%">
                <GmapInfoWindow
                    :opened="infoWinOpen"
                    :options="infoOptions"
                    :position="infoWindowPos"
                    @closeclick="infoWinOpen=false">
                    <BranchInfoWindow v-if="activeBranch" :branch="activeBranch" />
                </GmapInfoWindow>

                <GmapMarker
                    v-if="branches"
                    v-for="(branch, index) in branches"
                    :key="index"
                    :position="branch.position"
                    :clickable="true"
                    :draggable="false"
                    @mouseout="statusText = null"
                    @mouseover="statusText = branch.address || ''"
                    @click="activeBranchOnMap(branch, index)"
                />

                <div slot="visible" v-show="statusText">
                    <div class="gmap-status-bar">
                        {{ statusText }}
                    </div>
                </div>
            </GmapMap>

            <div class="branch-map__locationinfo">
                <span><img src="/img/svg/icon-info-location.svg" alt="Trụ sở chính">Trụ sở chính</span>
                <span><img src="/img/svg/icon-info-location-2.svg" alt="Điểm giao dịch">Điểm giao dịch</span>
            </div>
        </div>

        <div class="branch-map__content">
            <div class="branch-map__search">
                <input class="form-control" v-model="search" placeholder="Nhập tên thành phố...">
                <span @click.prevent="getLocation()" style="cursor: pointer;"><img src="/img/svg/icon-search-map.svg" alt="Search"></span>
            </div>

            <ul class="branch-map__inner">
                <ul class="branch-map__list">
                    <li v-if="!branches" v-for="i in [1, 2, 3, 4]" style="display: block;">
                        <content-loader
                            :height="115"
                            :width="480"
                            :speed="2"
                            primaryColor="#f3f3f3"
                            secondaryColor="#ddd"
                        >
                            <rect x="0" y="5" rx="3" ry="3" width="280" height="12" />
                            <rect x="0" y="40" rx="3" ry="3" width="350" height="7" />
                            <rect x="0" y="55" rx="3" ry="3" width="380" height="7" />
                            <rect x="0" y="85" rx="0" ry="0" width="80" height="10" />
                            <rect x="120" y="85" rx="0" ry="0" width="80" height="10" />
                        </content-loader>
                    </li>

                    <li v-if="branches" v-for="(branch, index) in filteredList">
                        <div class="branch-map__left">
                            <h2>{{ branch.name }}</h2>
                            <p>{{ branch.address }}</p>

                            <div class="branch-map__social">
                                <a href="#"><img src="/img/svg/chinhanh.svg" alt="Phone">{{ branch.phone_number }}</a>
                                <a href="#"><img src="/img/svg/chinhanh2.svg" alt="Fax">{{ branch.fax_number }}</a>
                            </div>
                        </div>

                        <div class="branch-map__right">
                            <a class="branch-map__direct" href="#" @click.prevent="activeBranchOnMap(branch, index)">
                                <img src="/img/svg/icon-direct.svg" alt="Chỉ đường"><span>Chỉ đường</span>
                            </a>
                        </div>
                    </li>
                </ul>
            </ul>
        </div>
    </div>
</template>

<script>
  import _ from 'lodash';
  import { ContentLoader } from 'vue-content-loader';
  import { gmapApi } from 'vue2-google-maps';

  import minimum from '../utils/minimum';
  import BranchInfoWindow from './BranchInfoWindow';

  export default {
    name: 'branch-locator',

    components: {
      ContentLoader,
      BranchInfoWindow,
    },

    mounted() {
      this.fetchData();
    },

    data() {
      return {
        loading: true,
        initialLoading: true,
        branches: null,

        search: '',

        zoom: 10,
        center: {
          lat: 0,
          lng: 0,
        },

        statusText: '',
        activeBranch: null,

        infoWindowPos: null,
        infoWinOpen: false,
        currentMidx: null,

        infoOptions: {
          pixelOffset: {
            width: 0,
            height: -45,
          },
        },
      };
    },

    computed: {
      google: gmapApi,

      filteredList() {
        if (!this.search) {
          return this.branches;
        }

        const term = this.search.toLowerCase();

        return this.branches.filter(branch => {
          return branch.name.toLowerCase().includes(term) ||
            branch.address.toLowerCase().includes(term);
        });
      },
    },

    methods: {
      /**
       * Initialize the compnent's data.
       */
      async fetchData() {
        await this.getResource();
        this.initialLoading = false;
      },

      /**
       * Get the branches information.
       */
      getResource() {
        this.branches = null;

        return minimum(
          axios.get('/json/branches.json'),
        )
          .then((response) => {
            this.branches = _.map(response.data, (branch) => {
              branch.position = {
                lat: parseFloat(branch.position.lat),
                lng: parseFloat(branch.position.lng),
              };

              return branch;
            });

            this.center = _.first(this.branches).position;

            this.loading = false;
          })
          .catch(error => {
            console.error(error);
          });
      },

      getLocation() {
        if (!navigator.geolocation) {
          return;
        }

        navigator.geolocation.getCurrentPosition((position) => {
          this.zoom = 12;
          this.center = {
            lat: position.coords.latitude,
            lng: position.coords.longitude,
          };
        }, function() {
          window.alert(
            'Trình duyệt của bạn không cho phép truy cập GeoLocation, vui lòng kiểm tra lại.');
        });
      },

      activeBranchOnMap(branch, idx) {
        this.zoom = 15;
        this.center = branch.position;

        this.activeBranch = branch;
        this.infoWindowPos = branch.position;

        // Handle open/close the infowindow.
        if (this.currentMidx === idx) {
          this.infoWinOpen = !this.infoWinOpen;
        } else {
          this.infoWinOpen = true;
          this.currentMidx = idx;
        }
      },
    },
  };
</script>

<style>
    .gmap-status-bar {
        left: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, .75);
        color: white;
        position: absolute;
        z-index: 1001;
        padding: 3px 10px;
        font-size: 12px;
    }
</style>
