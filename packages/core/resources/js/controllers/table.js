import $ from 'jquery';
import _ from 'lodash';
import {__} from "@wordpress/i18n";
import {alert2} from '../utils/sweetalert'
import {Controller} from 'stimulus';

export default class TableController extends Controller {
  static targets = ['action'];

  get action() {
    return this.actionTarget.value || '';
  }

  /**
   * On the DOM ready.
   */
  connect() {
    this.table = this.element;
    this.checks = this.checked = this.lastClicked = null;

    if (this.table.tagName.toLocaleLowerCase() !== 'table') {
      this.table = this.element.querySelector('table');
    }

    $(this.table).on(
      'click',
      'tbody > tr > .check-column :checkbox',
      this.onClickCheckbox
    );

    $(this.table).on(
      'click.toggle-checkboxes',
      'thead .check-column :checkbox, tfoot .check-column :checkbox',
      this.onBulkCheckboxClick
    );
  }

  /**
   * Run the action.
   */
  runAction(e) {
    e.preventDefault();

    if (!this.action) {
      return;
    }

    const resources = this.getSelectedResources();
    if (_.isEmpty(resources)) {
      alert2(__('Please select a resource to perform this action on.'), 'warning');
      return;
    }

    axios.post(this.data.get('actionUrl'), {
      action: this.action,
      resources,
    }).then(response => {
      Turbolinks.visit(window.location)
    });
  }

  getSelectedResources() {
    const checked = $(this.table).find('tbody .check-column :checkbox').filter(':checked');

    return _.values(_.map(checked, (input) => input.value));
  }

  /**
   * Checks a checkbox
   *
   * @param {Events} e The event object.
   *
   * @returns {boolean} Returns whether a checkbox is checked or not.
   */
  onClickCheckbox(e) {
    // Shift click to select a range of checkboxes.
    if (e.shiftKey === 'undefined') {
      return true;
    }

    if (e.shiftKey) {
      if (!this.lastClicked) {
        return true;
      }

      this.checks = $(this.lastClicked).closest('form').find(':checkbox').filter(':visible:enabled');
      const first = this.checks.index(this.lastClicked);
      const last = this.checks.index(this);
      this.checked = $(this).prop('checked');

      if (0 < first && 0 < last && first !== last) {
        const sliced = (last > first) ? checks.slice(first, last) : checks.slice(last, first);

        sliced.prop('checked', function () {
          if ($(this).closest('tr').is(':visible')) {
            return this.checked;
          }

          return false;
        });
      }
    }

    this.lastClicked = this;

    // Toggle the "Select all" checkboxes depending if the other ones are all checked or not.
    const unchecked = $(this).closest('tbody').find(':checkbox').filter(':visible:enabled').not(':checked');

    /**
     * Determines if all checkboxes are checked.
     *
     * @returns {boolean} Returns true if there are no unchecked checkboxes.
     */
    $(this).closest('table').children('thead, tfoot').find(':checkbox').prop('checked', function () {
      return (0 === unchecked.length);
    });

    return true;
  }

  /**
   * Controls all the toggles on bulk toggle change.
   *
   * When the bulk checkbox is changed, all the checkboxes in the tables are changed accordingly.
   * When the shift-button is pressed while changing the bulk checkbox the checkboxes in the table are inverted.
   *
   * @param {Events} e The event object.
   *
   * @returns {boolean}
   */
  onBulkCheckboxClick(e) {
    const $this = $(this);
    const $table = $this.closest('table');
    const controlChecked = $this.prop('checked');
    const toggle = e.shiftKey || $this.data('wp-toggle');

    $table.children('tbody')
      .filter(':visible')
      .children().children('.check-column').find(':checkbox')
      .prop('checked', function () {
        if ($(this).is(':hidden,:disabled')) {
          return false;
        }

        if (toggle) {
          return !$(this).prop('checked');
        } else if (controlChecked) {
          return true;
        }

        return false;
      });

    $table.children('thead,  tfoot').filter(':visible')
      .children().children('.check-column').find(':checkbox')
      .prop('checked', function () {
        if (toggle) {
          return false;
        } else if (controlChecked) {
          return true;
        }

        return false;
      });
  }
}
