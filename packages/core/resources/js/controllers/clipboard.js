import {Controller} from 'stimulus'

export default class extends Controller {
  static targets = ['source'];

  select() {
    const target = this.sourceTarget;
    target.setSelectionRange(0, target.value.length);
  }

  copy(e) {
    e.preventDefault();

    try {
      this.sourceTarget.select();
      document.execCommand('copy');
    } catch (err) {
    }
  }
}
