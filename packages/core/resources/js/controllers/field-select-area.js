import $ from 'jquery'
import { Controller } from 'stimulus'

export default class extends Controller {
  static targets = ['province', 'district']

  connect () {
    setTimeout(this.initSelectize.bind(this), 0)
  }

  disconnect () {
    if (this.provinceTarget.selectize) {
      this.provinceTarget.selectize.destroy()
    }

    if (this.districtTarget.selectize) {
      this.districtTarget.selectize.destroy()
    }
  }

  initSelectize () {
    const { provinceTarget, districtTarget } = this

    let districtSelect

    const $districtSelect = $(districtTarget).selectize({
      valueField: 'code',
      labelField: 'name_with_type',
      searchField: ['name', 'code', 'slug', 'name_with_type'],
      placeholder: 'Chọn Quận/Huyện',
    })

    $(provinceTarget).selectize({
      valueField: 'code',
      labelField: 'name',
      searchField: ['name', 'code', 'slug', 'name_with_type'],
      placeholder: 'Chọn Tỉnh/Thành Phố',

      onInitialize () {
        window.axios('/data/subdivision.json').then(response => {
          this.addOption(response.data)
          this.refreshItems()
        })
      },

      onChange (province) {
        if (!province.length) {
          return
        }

        // noinspection EqualityComparisonWithCoercionJS
        if (province != $(provinceTarget).data('value')) {
          districtSelect.clear()
        }

        districtSelect.clearOptions()
        districtSelect.disable()

        districtSelect.load(callback => {
          window.axios(`/data/subdivision/${province}.json`).
            then(response => {
              districtSelect.enable()
              callback(response.data)
              $(provinceTarget).data('value', province)
            }).
            catch(err => {
              callback()
            })
        })
      },
    })

    districtSelect = $districtSelect[0].selectize
    districtSelect.disable()
  }
}
