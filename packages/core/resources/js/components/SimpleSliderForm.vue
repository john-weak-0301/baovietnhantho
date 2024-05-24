<template>
    <div>
        <el-form label-position="left" label-width="230px" :model="form">
            <el-form-item label="Tiêu đề">
                <el-input
                    placeholder="Please input"
                    :name="inputName('title')"
                    v-model="form.title"
                />
            </el-form-item>

            <el-form-item label="Mô tả ngắn">
                <el-input
                    type="textarea"
                    placeholder="Please input"
                    v-model="form.description"
                    :name="inputName('description')"
                    :rows="2"
                />
            </el-form-item>

            <el-form-item label="Link">
                <el-input
                    v-model="form.link"
                    :name="inputName('link')"
                    style="width: calc(100% - 155px); float: left;"
                />

                <el-input
                    type="text"
                    :rows="2"
                    placeholder="Link"
                    v-model="form.text_link"
                    :name="inputName('text_link')"
                    style="width: 150px; float: left; margin-left: 5px;"
                />
            </el-form-item>

            <el-form-item label="Ảnh nền">
                <el-upload
                    class="avatar-uploader"
                    action="/dashboard/resource/media"
                    list-type="picture-card"
                    :show-file-list="false"
                    :on-success="handleUploadSuccess"
                    :headers="{ 'X-CSRF-TOKEN': csrf }"
                >
                    <img v-if="form.background" :src="form.background" class="img-avatar" alt="">
                    <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                </el-upload>

                <button class="btn btn-default" @click.prevent="selectImage">Chọn ảnh</button>

                <input type="hidden" :name="inputName('background')" :value="form.background" readonly>
            </el-form-item>

            <el-form-item label="Ảnh nền mobile">
                <el-upload
                    class="avatar-uploader"
                    action="/dashboard/resource/media"
                    list-type="picture-card"
                    :show-file-list="false"
                    :on-success="handleUploadMobileSuccess"
                    :headers="{ 'X-CSRF-TOKEN': csrf }"
                >
                    <img v-if="form.background_mobile" :src="form.background_mobile" class="img-avatar" alt="">
                    <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                </el-upload>

                <button class="btn btn-default" @click.prevent="selectImageMobile">Chọn ảnh</button>
                <input type="hidden" :name="inputName('background_mobile')" :value="form.background_mobile" readonly>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
  import $ from 'jquery';

  const createWPMediaPicker = function(callback) {
    const media = wp.media({
      title: 'Chọn ảnh',
      multiple: false,
      library: {
        type: 'image',
      },
      button: {
        text: 'Chọn',
      },
    });

    media.on('select', () => {
      let attachment = media.state().get('selection').first().toJSON();
      callback(attachment);
    });

    return media;
  };

  const defaultForm = {
    title: '',
    description: '',
    background: '',
    background_mobile: '',
    text_link: 'Tìm hiểu',
    link: '',
  };

  export default {
    props: ['slide', 'index'],

    data() {
      let form = {};
      const csrf = $('meta[name="csrf_token"]').attr('content');

      Object.assign(form, defaultForm, this.slide || {});

      return {
        csrf,
        form,
        mediaPicker: null,
        mediaPickerMobile: null,
      };
    },

    mounted() {
      const { wp } = window;

      if (wp.media) {
        this.mediaPicker = createWPMediaPicker((attachment) => {
          this.form.background = attachment.url;
        });

        this.mediaPickerMobile = createWPMediaPicker((attachment) => {
          this.form.background_mobile = attachment.url;
        });
      }
    },

    methods: {
      inputName(input) {
        return `sliders[${this.index}][${input}]`;
      },

      handleUploadSuccess({ data }) {
        this.form.background = data.url;
      },

      handleUploadMobileSuccess({ data }) {
        this.form.background_mobile = data.url;
      },

      selectImage() {
        if (this.mediaPicker) {
          this.mediaPicker.open();
        }
      },

      selectImageMobile() {
        if (this.mediaPickerMobile) {
          this.mediaPickerMobile.open();
        }
      },
    },
  };
</script>

<style scoped>
    .el-upload--picture-card {
        overflow: hidden;
    }

    .avatar-uploader .img-avatar {
        width: 148px;
        height: 148px;
        display: block;
        object-fit: cover;
        object-position: 50% 50%;
    }
</style>
