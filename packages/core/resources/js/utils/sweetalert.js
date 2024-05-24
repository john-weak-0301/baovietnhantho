import Swal from 'sweetalert2/dist/sweetalert2'
import {__} from '@wordpress/i18n';

window.SwalToast = Swal.mixin({
  toast: true,
  position: 'top-end',
  buttonsStyling: false,
  showCancelButton: false,
  showConfirmButton: true,
  confirmButtonClass: 'btn btn-default',
  timer: 3000
});

export function confirm2(message, callback) {
  const confirm = Swal.fire({
    text: message || __('Bạn có chắc muốn thực hiện hành động này?', 'default'),
    customClass: 'confirm-dialog',
    position: 'center',
    animation: false,
    backdrop: 'rgba(0,0,0,.8)',
    reverseButtons: true,
    buttonsStyling: false,
    showCancelButton: true,
    cancelButtonClass: '',
    confirmButtonClass: '',
    cancelButtonText: __('Bỏ qua', 'default'),
    confirmButtonText: __('OK', 'default'),
  });

  if (callback) {
    return confirm.then(function (result) {
      if (result.value) callback(result)
    });
  }

  return confirm;
}

export function alert2(message, type = 'error') {
  return SwalToast.fire({
    type: type,
    text: message,
    position: 'center',
    animation: false,
  });
}
