<template>
  <DashboardHeader title="Create Event" />
  <section class="content">
    <div class="container-fluid">
      <div class="row row-cols-1">
        <div class="card card-purple">
          <div class="card-header">
            <h3 class="card-title">Create Event</h3>
          </div>

          <form @submit.prevent="submitEvent">
            <div class="card-body">
              <div class="row">
                <!-- Left Column -->
                <div class="col-md-8">
                  <!-- Title & Slug -->
                  <div class="form-group">
                    <label>Event Title</label>
                    <input
                      v-model="form.title"
                      @input="form.slug = generateSlug(form.title)"
                      type="text"
                      class="form-control"
                      required
                    />
                  </div>
                  <div class="form-group">
                    <label>Slug</label>
                    <input
                      v-model="form.slug"
                      type="text"
                      class="form-control"
                      required
                    />
                  </div>

                  <!-- Description -->
                  <div class="form-group">
                    <label>Description</label>
                    <RichTextEditor
                      v-model="form.description"
                      placeholder="Write event details here..."
                      class="editor"
                    ></RichTextEditor>
                  </div>

                  <!-- Category -->
                  <div class="form-group">
                    <label>Event Category</label>
                    <Multiselect
                      v-model="form.event_category_id"
                      :options="categoriesOptions"
                      :reduce="(option) => option.value"
                      placeholder="Select Event category"
                      searchable
                      allow-empty
                    />
                  </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-4">
                  <!-- File Upload -->
                  <div class="form-group">
                    <label>Upload Featured Image</label>
                    <Vue3Dropzone v-model="imageUpload" :allowSelectOnPreview="true" />
                    <small class="text-muted"
                      >Recommended: Running (262 x 230px) | Upcoming(252 x 515px)</small
                    >
                  </div>
                  <!-- File Upload -->
                  <div class="form-group">
                    <label>Upload Banner Image</label>
                    <Vue3Dropzone v-model="fileUpload" :allowSelectOnPreview="true" />
                    <small class="text-muted">Recommended: 1140 × 375px</small>
                  </div>

                  <!-- Event Type -->
                  <div class="form-group">
                    <label>Event Type</label>
                    <select v-model="form.type" class="custom-select">
                      <option value="1">Running</option>
                      <option value="0">Upcoming</option>
                    </select>
                  </div>
                  <!-- Status -->
                  <div class="form-group">
                    <label>Status</label>
                    <select v-model="form.status" class="custom-select">
                      <option value="1">Active</option>
                      <option value="0">Inactive</option>
                    </select>
                  </div>

                  <div>
                    <button type="submit" class="btn btn-success btn-block">
                      Submit
                    </button>
                    <RouterLink
                      :to="{ name: 'Events' }"
                      class="btn btn-secondary btn-block mt-2"
                    >
                      Cancel</RouterLink
                    >
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import DashboardHeader from "@/components/DashboardHeader.vue";
import Vue3Dropzone from "@jaxtheprime/vue3-dropzone";
import "@jaxtheprime/vue3-dropzone/dist/style.css";
import axios from "axios";
import { reactive, ref, onMounted, computed } from "vue";
import { useToast } from "@/composables/useToast";
import RichTextEditor from "@/components/RichTextEditor.vue";
import Multiselect from "@vueform/multiselect";
import { generateSlug } from "../../../layouts/helpers/helpers";

const toast = useToast();
const fileUpload = ref(null);
const categories = ref([]);
const imageUpload = ref(null);

const form = reactive({
  title: "",
  slug: "",
  description: "",
  event_category_id: "",
  status: "1",
  banner_image: null,
  featured_image: null,
  type: 1,
});

const categoriesOptions = computed(() => {
  return [
    { label: "-- None --", value: "" },
    ...categories.value.map((cat) => ({
      label: cat.category_name,
      value: cat.id,
    })),
  ];
});

// Fetch categories for dropdown
const fetchCategories = async () => {
  try {
    const res = await axios.get("/api/events-categories?all=1");
    categories.value = res.data.data;
  } catch (error) {
    toast.error("Failed to load categories");
  }
};

const submitEvent = async () => {
  const payload = new FormData();

  for (const key in form) {
    if (key !== "banner_image") {
      payload.append(key, form[key]);
    }
  }

  if (fileUpload.value && fileUpload.value[0]) {
    payload.append("banner_image", fileUpload.value[0].file);
  }

  if (imageUpload.value && imageUpload.value[0]) {
    payload.append("featured_image", imageUpload.value[0].file);
  }

  try {
    await axios.post("/api/events", payload, {
      headers: { "Content-Type": "multipart/form-data" },
    });
    toast.success("Event created successfully!");
  } catch (error) {
    toast.error("Failed to create event");
    console.error(error);
  }
};

onMounted(() => {
  fetchCategories();
});
</script>
