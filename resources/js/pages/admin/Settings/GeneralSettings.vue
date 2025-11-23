<template>
  <DashboardHeader title="General Settings" />

  <section class="container py-4">
    <div class="card shadow-sm">
      <div class="card-body">

        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="branding-tab" data-toggle="tab" href="#branding" role="tab">Branding</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="general-tab" data-toggle="tab" href="#general" role="tab">General</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="mail-tab" data-toggle="tab" href="#mail" role="tab">Mail</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="social-tab" data-toggle="tab" href="#social" role="tab">Social</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="seo-tab" data-toggle="tab" href="#seo" role="tab">SEO</a>
          </li>
        </ul>

        <!-- Tabs Content -->
        <form @submit.prevent="submitSettings" class="tab-content mt-4">

          <!-- Branding Tab -->
          <div class="tab-pane fade show active" id="branding" role="tabpanel">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Store Logo</label>
                <Vue3Dropzone v-model="logoFile" v-model:previews="logoPreview" mode="edit" :allowSelectOnPreview="true" :maxFiles="1" />
                <div v-if="logoPreview.length" class="mt-2">
                  <img :src="logoPreview[0]" alt="Logo Preview" class="img-thumbnail" style="width:120px;height:auto;" />
                </div>
              </div>
              <div class="col-md-6">
                <label class="form-label">Store Icon (Favicon)</label>
                <Vue3Dropzone v-model="faviconFile" v-model:previews="faviconPreview" mode="edit" :allowSelectOnPreview="true" :maxFiles="1" />
                <div v-if="faviconPreview.length" class="mt-2">
                  <img :src="faviconPreview[0]" alt="Favicon Preview" class="img-thumbnail" style="width:40px;height:40px;" />
                </div>
              </div>
            </div>
          </div>

          <!-- General Tab -->
          <div class="tab-pane fade" id="general" role="tabpanel">
            <div class="row g-3">
              <div class="col-md-12 mb-3">
                <label class="form-label">Address</label>
                <textarea v-model="form.address" class="form-control" rows="2"></textarea>
              </div>
              <div class="col-md-6">
                <label class="form-label">Email</label>
                <input v-model="form.email" type="email" class="form-control" />
              </div>
              <div class="col-md-6">
                <label class="form-label">Phone</label>
                <input v-model="form.phone" type="text" class="form-control" />
              </div>
            </div>
          </div>

          <!-- Mail Tab -->
          <div class="tab-pane fade" id="mail" role="tabpanel">
            <div class="row g-3">
              <div class="col-md-6 mb-3">
                <label class="form-label">Mail Protocol</label>
                <select v-model="form.mail_protocol" class="form-control">
                  <option value="smtp">SMTP</option>
                  <option value="mail">Mail</option>
                  <option value="sendmail">Sendmail</option>
                </select>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Mail Address</label>
                <input v-model="form.mail_address" type="email" class="form-control" />
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">SMTP Host</label>
                <input v-model="form.smtp_host" type="text" class="form-control" />
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">SMTP Username</label>
                <input v-model="form.smtp_username" type="text" class="form-control" />
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">SMTP Password</label>
                <input v-model="form.smtp_password" type="password" class="form-control" />
              </div>
              <div class="col-md-3 mb-3">
                <label class="form-label">SMTP Port</label>
                <input v-model="form.smtp_port" type="text" class="form-control" />
              </div>
              <div class="col-md-3 mb-3">
                <label class="form-label">SMTP Timeout</label>
                <input v-model="form.smtp_timeout" type="text" class="form-control" />
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">SMTP Crypto</label>
                <select v-model="form.smtp_crypto" class="form-control">
                  <option value="ssl">SSL</option>
                  <option value="tls">TLS</option>
                  <option value="">None</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Social Tab -->
          <div class="tab-pane fade" id="social" role="tabpanel">
            <div class="row g-3">
              <div class="col-md-6 mb-3">
                <label class="form-label">Facebook URL</label>
                <input v-model="form.fb_url" type="url" class="form-control" placeholder="https://facebook.com/..." />
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Twitter URL</label>
                <input v-model="form.twitter_url" type="url" class="form-control" placeholder="https://twitter.com/..." />
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">TikTok URL</label>
                <input v-model="form.tiktok_url" type="url" class="form-control" placeholder="https://tiktok.com/..." />
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Instagram URL</label>
                <input v-model="form.instagram_url" type="url" class="form-control" placeholder="https://instagram.com/..." />
              </div>
            </div>
          </div>

          <!-- SEO Tab -->
          <div class="tab-pane fade" id="seo" role="tabpanel">
            <div class="row g-3">
              <div class="col-md-4">
                <label class="form-label">Meta Title</label>
                <input v-model="form.meta_title" type="text" class="form-control" />
              </div>
              <div class="col-md-4">
                <label class="form-label">Meta Keyword</label>
                <input v-model="form.meta_keyword" type="text" class="form-control" />
              </div>
              <div class="col-md-4">
                <label class="form-label">Meta Description</label>
                <input v-model="form.meta_description" type="text" class="form-control" />
              </div>
            </div>
          </div>

          <!-- Submit -->
          <div class="mt-5 text-end">
            <button type="submit" class="btn btn-primary px-5">Save Settings</button>
          </div>
        </form>
      </div>
    </div>
  </section>
</template>

<script setup>
import DashboardHeader from '@/components/DashboardHeader.vue';
import { useToast } from '@/composables/useToast';
import { getImageUrl } from '@/layouts/helpers/helpers';
import Vue3Dropzone from '@jaxtheprime/vue3-dropzone';
import '@jaxtheprime/vue3-dropzone/dist/style.css';
import axios from 'axios';
import { onMounted, ref } from 'vue';
const toast = useToast();
// ---------- Reactive form ----------
const form = ref({
  // General
  address: '',
  email: '',
  phone: '',
  // Mail
  mail_protocol: '',
  mail_address: '',
  smtp_host: '',
  smtp_username: '',
  smtp_password: '',
  smtp_port: '',
  smtp_timeout: '',
  smtp_crypto: '',
  // Social
  fb_url: '',
  twitter_url: '',
  tiktok_url: '',
  instagram_url: '',
  // SEO
  meta_title: '',
  meta_keyword: '',
  meta_description: '',
});

// ---------- Dropzone refs ----------
const logoFile = ref([]);
const logoPreview = ref([]);
const faviconFile = ref([]);
const faviconPreview = ref([]);
// ---------- Fetch existing settings ----------
const fetchSettings = async () => {
  try {
    const { data } = await axios.get('/api/settings');
    const settings = data.data || [];

    settings.forEach(s => {
      const key = s.label;               // e.g. "invoice_prefix"
      const value = s.value;

      // Fill text / select fields
      if (form.value.hasOwnProperty(key)) {
        form.value[key] = value;
      }

      // Pre-populate image previews
      if (key === 'store_logo' && value) {
        logoPreview.value = [getImageUrl(value)];
      }
      if (key === 'store_icon' && value) {
        faviconPreview.value = [getImageUrl(value)];
      }

      // Convert checkbox values ("1"/"0") → boolean for UI
      if (['new_account_alert_mail', 'new_order_alert_mail'].includes(key)) {
        form.value[key] = value === '1' ? true : false;
      }
    });
  } catch (e) {
    toast.validationError(e);
  }
};

// ---------- Submit ----------
const submitSettings = async () => {
  const payload = new FormData();

  // === 1. Append all text / select / checkbox fields ===
  Object.entries(form.value).forEach(([key, value]) => {
    if (['new_account_alert_mail', 'new_order_alert_mail'].includes(key)) {
      payload.append(key, value ? '1' : '0');
    } else {
      payload.append(key, value ?? '');
    }
  });
  // Append image files ONLY if a new file is selected
  if (logoFile.value[0]?.file) {
    payload.append('store_logo', logoFile.value[0].file);
  }
  if (faviconFile.value[0]?.file) {
    payload.append('store_icon', faviconFile.value[0].file);
  }

  // === 3. Submit ===
  try {
    await axios.post('/api/settings/update', payload, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
    toast.success('Settings saved successfully');
  } catch (error) {
    toast.validationError(error);
  }
};
onMounted(fetchSettings);
</script>

<style scoped>
/* Optional: make checkboxes a bit larger */
.form-check-input {
  transform: scale(1.2);
}
</style>