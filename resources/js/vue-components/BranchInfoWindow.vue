<template>
  <div v-if="branch" class="branch-locator">
    <h2 style="margin-top: 0; margin-bottom: 5px;">{{ branch.name }}</h2>
    <p><i class="fa fa-2x fa-map-marker" style="color: #dc4338;"></i> {{ branch.address }}</p>

    <table class="branch-locator__table">
      <tbody>
        <tr v-if="branch.email">
          <th>Email</th>
          <td><a :href="`mailto:${branch.email}`">{{ branch.email }}</a></td>
        </tr>

        <tr v-if="branch.phone_number">
          <th>Điện thoại</th>
          <td>{{ branch.phone_number }}</td>
        </tr>

        <tr v-if="branch.fax_number">
          <th>Fax</th>
          <td>{{ branch.fax_number }}</td>
        </tr>
      </tbody>
    </table>

    <h3 style="margin-bottom: 10px;">Các dịch vụ sẵn có</h3>
    <ul class="branch-locator__services" v-if="branch.services">
      <li v-for="service in branch.services">{{ service.name }}</li>
    </ul>

    <h3 style="margin-bottom: 10px;">Giờ mở cửa:</h3>
    <table class="branch-locator__table">
      <tbody>
        <tr style="font-weight:bold">
          <th>Ngày</th>
          <td>Thời gian</td>
        </tr>
        <tr>
          <th>Thứ hai</th>
          <td>{{ getWorkingDay('monday') }}</td>
        </tr>
        <tr>
          <th>Thứ ba</th>
          <td>{{ getWorkingDay('tuesday') }}</td>
        </tr>
        <tr>
          <th>Thứ tư</th>
          <td>{{ getWorkingDay('wednesday') }}</td>
        </tr>
        <tr>
          <th>Thứ năm</th>
          <td>{{ getWorkingDay('thursday') }}</td>
        </tr>
        <tr>
          <th>Thứ sáu</th>
          <td>{{ getWorkingDay('friday') }}</td>
        </tr>
        <tr>
          <th style="color:red;">Thứ bảy</th>
          <td style="color:red;">{{ getWorkingDay('saturday') }}</td>
        </tr>
        <tr>
          <th style="color:red;">Chủ nhật</th>
          <td style="color:red;">-</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
  export default {
    props: {
      branch: Object,
    },

    methods: {
      getWorkingDay(day) {
        const workingDays = this.branch.working_day || {};

        if (day === 'saturday' && !workingDays.hasOwnProperty(day)) {
          return '-';
        }

        if (!workingDays.hasOwnProperty(day)) {
          return '08:00 - 17:00';
        }

        return [
          (workingDays[0] || '08:00'),
          (workingDays[1] || '17:00')
        ].join(' - ');
      }
    }
  }
</script>

<style lang="scss">
  .branch-locator {
    font-size: 13px;
    font-weight: 400;
    width: 520px;
    max-height: 300px;
    overflow: auto;

    &::-webkit-scrollbar {
      width: 3px;
      background-color: #eee;
      display: none;
    }

    &::-webkit-scrollbar-thumb {
      background-color: #aaa;
    }

    &:hover::-webkit-scrollbar {
      display: block;
    }
  }

  .branch-locator__table {
    width: 100%;
    border-spacing: 0;
    table-layout: fixed;
    text-align: left;

    th {
      width: 100px;
      text-align: left;
    }

    th,
    td {
      border: none;
      padding: 5px 10px;
    }
  }

  .branch-locator__services {
    list-style-type: square;
    padding-left: 20px;
  }
</style>
