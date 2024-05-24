import $ from 'jquery';
import { SwalAlert } from './sweetalert';

export async function sendRequest(url, data, method = 'POST') {
  try {
    return await $.ajax({
      url,
      data,
      type: method,
      dataType: 'json',
      processData: !(data instanceof FormData),
      contentType: (data instanceof FormData)
        ? false
        : 'application/x-www-form-urlencoded; charset=UTF-8',
    });
  } catch (e) {
    let error = e.responseJSON || {};
    let message = '';

    if (e.status === 422 && error.errors) {
      message = Object.values(error.errors)[0][0] || '';
    }

    if (!message && error.message) {
      message = error.message;
    }

    if (message) {
      SwalAlert(message);
    }

    throw e;
  }
}
