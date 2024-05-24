import Swal from 'sweetalert2/dist/sweetalert2'

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
    text: message || 'Bạn có chắc muốn thực hiện hành động này?',
    customClass: 'confirm-dialog',
    position: 'center',
    animation: false,
    backdrop: 'rgba(0,0,0,.8)',
    reverseButtons: true,
    buttonsStyling: false,
    showCancelButton: true,
    cancelButtonClass: '',
    confirmButtonClass: '',
    cancelButtonText: 'Bỏ qua',
    confirmButtonText: 'OK',
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

export const SwalAlert = (message) => {
  return Swal.fire({
    text: message,
    customClass: 'confirm-dialog confirm-dialog--alert',
    position: 'center',
    animation: false,
    backdrop: 'rgba(0,0,0,.8)',
    reverseButtons: true,
    buttonsStyling: false,
    showCancelButton: false,
    confirmButtonClass: '',
  });
};
