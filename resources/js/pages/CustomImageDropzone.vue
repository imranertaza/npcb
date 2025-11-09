<template>
    <div
      class="styled-dropzone"
      :class="{ 'has-file': !!preview, 'drag-over': isDragging }"
      @click="openFileDialog"
      @dragover.prevent="isDragging = true"
      @dragleave.prevent="isDragging = false"
      @drop.prevent="onDrop"
    >
      <!-- Hidden vue3-dropzone (no default UI) -->
      <Vue3Dropzone
        ref="dropzone"
        :options="dropzoneOptions"
        :use-custom-slot="true"
        @vdropzone-file-added="onFileAdded"
        @vdropzone-removed-file="onFileRemoved"
        class="d-none"
      />
  
      <!-- Custom Empty State -->
      <div v-if="!preview" class="empty-state">
        <i class="bi bi-cloud-upload fs-1 text-primary"></i>
        <p class="mt-2 mb-0">Drop image here or <strong>click to upload</strong></p>
        <small class="text-muted">{{ acceptText }} (Max {{ maxSizeMB }}MB)</small>
      </div>
  
      <!-- Custom Preview -->
      <div v-else class="preview-container position-relative">
        <img :src="preview" alt="Preview" class="preview-img" />
        <div class="overlay-actions">
          <button
            type="button"
            class="btn btn-sm btn-outline-light shadow-sm"
            @click.stop="openFileDialog"
            title="Replace"
          >
            <i class="bi bi-arrow-repeat"></i>
          </button>
          <button
            type="button"
            class="btn btn-sm btn-outline-danger shadow-sm"
            @click.stop="removeFile"
            title="Remove"
          >
            <i class="bi bi-trash"></i>
          </button>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, computed, onMounted, watch } from 'vue';
  import Vue3Dropzone from '@jaxtheprime/vue3-dropzone';
  import '@jaxtheprime/vue3-dropzone/dist/style.css'; // Keep for internal logic
  
  const props = defineProps({
    modelValue: File,
    preview: String,
    maxFiles: { type: Number, default: 1 },
    maxSize: { type: Number, default: 4 * 1024 * 1024 }, // 4 MB
    accept: { type: String, default: 'image/*' },
  });
  
  const emit = defineEmits(['update:modelValue', 'update:preview']);
  
  const dropzone = ref(null);
  const isDragging = ref(false);
  const currentFile = ref(props.modelValue || null);
  const currentPreview = ref(props.preview || '');
  
  // Sync v-model
  watch(() => props.modelValue, (val) => currentFile.value = val);
  watch(() => props.preview, (val) => currentPreview.value = val);
  
  // Dropzone config
  const dropzoneOptions = computed(() => ({
    url: '#', // dummy – we handle manually
    maxFiles: props.maxFiles,
    maxFilesize: props.maxSize / (1024 * 1024), // MB
    acceptedFiles: props.accept,
    autoProcessQueue: false,
    addRemoveLinks: false,
    createImageThumbnails: true,
    thumbnailWidth: 200,
    thumbnailHeight: 200,
  }));
  
  const maxSizeMB = computed(() => (props.maxSize / (1024 * 1024)).toFixed(0));
  const acceptText = computed(() => {
    const map = {
      'image/*': 'PNG, JPG, SVG',
      'image/png,image/jpg,image/jpeg,image/x-icon': 'PNG, JPG, ICO'
    };
    return map[props.accept] || 'Images心灵';
  });
  
  // Open file dialog
  const openFileDialog = () => dropzone.value?.open();
  
  // File added
  const onFileAdded = (file) => {
    if (file.manuallyAdded) return;
  
    currentFile.value = file;
    emit('update:modelValue', file);
  
    const reader = new FileReader();
    reader.onload = (e) => {
      currentPreview.value = e.target.result;
      emit('update:preview', e.target.result);
    };
    reader.readAsDataURL(file);
  };
  
  // File removed
  const onFileRemoved = () => {
    currentFile.value = null;
    currentPreview.value = '';
    emit('update:modelValue', null);
    emit('update:preview', '');
  };
  
  const removeFile = () => {
    dropzone.value?.removeAllFiles();
  };
  
  // Drag & drop
  const onDrop = (e) => {
    isDragging.value = false;
    const files = e.dataTransfer.files;
    if (files.length > 0) {
      dropzone.value?.manuallyAddFile(files[0], '#');
    }
  };
  
  onMounted(() => {
    if (props.preview) {
      // If preview exists from server, show it
      currentPreview.value = props.preview;
    }
  });
  </script>
  
  <style scoped>
  .styled-dropzone {
    border: 2.5px dashed #adb5bd;
    border-radius: 12px;
    background: #f8f9fa;
    padding: 1.5rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    min-height: 160px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  .styled-dropzone:hover,
  .styled-dropzone.drag-over {
    border-color: #0d6efd;
    background: #e3f2fd;
  }
  
  .empty-state strong {
    color: #0d6efd;
  }
  
  .preview-container {
    width: 100%;
    height: 100%;
    border-radius: 8px;
    overflow: hidden;
  }
  
  .preview-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  
  .overlay-actions {
    position: absolute;
    top: 8px;
    right: 8px;
    display: flex;
    gap: 6px;
    opacity: 0;
    transition: opacity 0.2s;
  }
  
  .preview-container:hover .overlay-actions {
    opacity: 1;
  }
  
  .btn-sm {
    padding: 4px 8px;
  }
  </style>