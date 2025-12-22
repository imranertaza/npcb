<template>
  <DashboardHeader title="Update Blog" />

  <section class="content">
    <div class="container-fluid">
      <div class="row row-cols-1">
        <div class="card card-purple">
          <div class="card-header">
            <h3 class="card-title">Edit Blog</h3>
          </div>

          <form @submit.prevent="updateBlog">
            <div class="card-body">
              <div class="row">
                <div class="col-md-8">
                  <!-- Title & Slug -->
                  <div class="form-group">
                    <label>Blog Title</label>
                    <input v-model="form.title" @input="form.slug = generateSlug(form.title)" type="text"
                      class="form-control" required />
                  </div>
                  <div class="form-group">
                    <label>Slug</label>
                    <input v-model="form.slug" type="text" class="form-control" required />
                  </div>

                  <!-- Short Description -->
                  <div class="form-group">
                    <label>Short Description</label>
                    <input v-model="form.short_des" type="text" class="form-control" required />
                  </div>

                  <!-- Description -->
                  <div class="form-group">
                    <label>Description</label>
                    <RichTextEditor v-model="form.description" placeholder="Write your blog details here..."
                      class="editor">
                    </RichTextEditor>
                  </div>

                  <!-- SEO Meta -->
                  <div class="form-group">
                    <label>Meta Title</label>
                    <input v-model="form.meta_title" type="text" class="form-control" />
                  </div>
                  <div class="form-group">
                    <label>Meta Keywords</label>
                    <input v-model="form.meta_keyword" type="text" class="form-control"
                      placeholder="comma, separated, keywords" />
                  </div>
                  <div class="form-group">
                    <label>Meta Description</label>
                    <textarea v-model="form.meta_description" class="form-control"></textarea>
                  </div>
                </div>

                <div class="col-md-4">

                  <!-- Category Selector -->
                  <div class="form-group">
                    <label class="font-weight-bold">Categories</label>
                    <div class="category-list">
                      <div v-for="cat in categories" :key="cat.id" class="icheck-primary category-item border-bottom">
                        <!-- Parent Category -->
                        <input type="checkbox" class="form-check-input" :id="'cat-' + cat.id" :value="cat.id"
                          v-model="form.categories" />
                        <label class="form-check-label d-block mb-3" :for="'cat-' + cat.id">
                          {{ cat.category_name }}
                        </label>

                        <!-- Child Categories -->
                        <div v-if="cat.children && cat.children.length" class="ml-2 mt-1">
                          <div v-for="child in cat.children" :key="child.id" class="icheck-primary form-check category-child">
                            <input type="checkbox" class="form-check-input" :id="'cat-' + child.id" :value="child.id"
                              v-model="form.categories" />
                            <label class="form-check-label d-block mb-2" :for="'cat-' + child.id">
                              {{ child.category_name }}
                            </label>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>

                  <!-- Image Upload -->
                  <div class="form-group">
                    <label>Upload Banner Image</label>
                    <Vue3Dropzone v-model="imageFile" v-model:previews="previews" mode="edit"
                      :allowSelectOnPreview="true" />
                  </div>
                  <!-- Image Upload -->
                  <div class="form-group">
                    <label>Upload Featured Image</label>
                    <Vue3Dropzone v-model="f_imageFile" v-model:previews="f_previews" mode="edit"
                      :allowSelectOnPreview="true" />
                  </div>

                  <!-- Alt Name -->
                  <div class="form-group">
                    <label>Alt Name</label>
                    <input v-model="form.alt_name" type="text" class="form-control" />
                  </div>

                  <!-- Publish Date -->
                  <div class="form-group">
                    <p><b>Publish Date</b> <br> <span>{{ form.publish_date }}</span></p>
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
                    <button type="submit" class="btn btn-success btn-block">Update</button>
                    <RouterLink :to="{ name: 'News' }" class="btn btn-secondary btn-block mt-2">Cancel</RouterLink>
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
import axios from 'axios';
import { computed, onMounted, reactive, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import DashboardHeader from '@/components/DashboardHeader.vue';
import { useToast } from '@/composables/useToast';
import { getImageUrl } from '@/layouts/helpers/helpers';
import Vue3Dropzone from '@jaxtheprime/vue3-dropzone';
import '@jaxtheprime/vue3-dropzone/dist/style.css';
import RichTextEditor from '../../../components/RichTextEditor.vue';
import { generateSlug } from '../../../layouts/helpers/helpers';

const toast = useToast();
const route = useRoute();
const router = useRouter();
const newsSlug = route.params.slug;
const previews = ref();
const f_previews = ref();

const form = reactive({
  news_title: '',
  slug: '',
  short_des: '',
  description: '',
  image: '',
  f_image: '',
  alt_name: '',
  publish_date: '',
  status: '1',
  meta_title: '',
  meta_keyword: '',
  meta_description: '',
  createdBy: 1,
  updatedBy: null,
  categories: [],
});

const imageFile = ref(null);
const f_imageFile = ref(null);

const fetchNews = async () => {
  try {
    const res = await axios.get(`/api/blogs/${newsSlug}`);
    Object.assign(form, res.data.data);

    form.categories = res.data.data.categories.map(c => c.id);

    previews.value = [getImageUrl(form.image)];
    f_previews.value = [getImageUrl(form.f_image)];
  } catch (err) {
    toast.error('Failed to load news');
    console.error(err);
  }
};

const updateBlog = async () => {
  const payload = new FormData();

  for (const key in form) {
    if (key !== 'image') {
      payload.append(key, form[key]);
    }
  }
  if (imageFile.value && imageFile.value[0]) {
    payload.append('image', imageFile.value[0].file);
  }
  if (f_imageFile.value && f_imageFile.value[0]) {
    payload.append('f_image', f_imageFile.value[0].file);
  }
  form.categories.forEach(catId => {
    payload.append('categories[]', catId);
  });
  try {
    await axios.post(`/api/blogs/${form.id}?_method=PUT`, payload, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });
    router.push({ name: 'Blog', query: { toast: 'Blog updated successfully' } });
  } catch (err) {
    toast.validationError(err);
  }
};


const categories = ref([]);

const fetchCategories = async () => {
  try {
    const res = await axios.get('/api/blog-categories?per_page=0'); // your category index API
    categories.value = res.data.data;
  } catch (err) {
    toast.error('Failed to load categories');
  }
};

const formattedDate = computed({
  get: () => form.publish_date?.slice(0, 10) || '',
  set: (val) => form.publish_date = val
});

defineProps({
  slug: {
    type: [Number, String],
    required: false
  }
});

onMounted(() => {
  fetchCategories();
  fetchNews();
});
</script>

<style scoped>
.category-list {
  border: 1px solid #ddd;
  padding: 10px;
  border-radius: 6px;
  background: #f9f9f9;
  max-height: 350px;
  overflow-y: auto;
}

.category-item {
  margin-bottom: 6px;
}

</style>
