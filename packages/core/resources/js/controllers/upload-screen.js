import $ from 'jquery'
import Dropzone from 'dropzone'
import { Controller } from 'stimulus'

export default class extends Controller {
  get dropname () {
    return '#dropzone'
  }

  connect () {
    this.initDropZone()
  }

  initDropZone () {
    const dropname = this.dropname

    new Dropzone(dropname, {
      url: '/dashboard/resource/media',
      method: 'POST',
      parallelUploads: 2,
      uploadMultiple: false,
      paramName: 'file',
      // acceptedFiles: 'image/*',
      maxFiles: this.data.get('max-files'),
      maxFilesize: this.data.get('max-file-size'),
      previewTemplate: $('#html-dropzone-preview').html(),
      previewsContainer: `${dropname} .dz-preview`,
      addRemoveLinks: false,
      autoDiscover: false,

      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content'),
      },

      init () {
        this.element.classList.add('dropzone-ready')

        this.on('success', (file, response) => {
          if (response.success && response.data) {
            file.data = response.data
          }
        })

        this.on('error', (file, response) => {
          const message = _.isString(response) ? response : response.message

          SwalToast.fire({
            type: 'error',
            text: message,
          })

          $(file.previewElement).find('[data-dz-errormessage]').text(message)
        })

        this.on('removedfile', file => {
          axios.delete(`/dashboard/resource/media/${file.data.id}`).then()
        })
      },
    })
  }
}
