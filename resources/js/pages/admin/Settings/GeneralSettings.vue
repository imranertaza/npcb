<template>
  <DashboardHeader title="General Settings" />

  <section class="container py-4">
    <div class="card shadow-sm">
      <div class="card-body">
        <form @submit.prevent="submitSettings">
          <div class="row g-3">

            <!-- ==================== LOGO & FAVICON ==================== -->
            <div class="col-md-6">
              <label class="form-label">Store Logo</label>
              <Vue3Dropzone v-model="logoFile" v-model:previews="logoPreview" mode="edit" :allowSelectOnPreview="true"
                :maxFiles="1" />
              <div v-if="logoPreview.length" class="mt-2">
                <img :src="logoPreview[0]" alt="Logo Preview" class="img-thumbnail" style="width:120px;height:auto;" />
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label">Store Icon (Favicon)</label>
              <Vue3Dropzone v-model="faviconFile" v-model:previews="faviconPreview" mode="edit"
                :allowSelectOnPreview="true" :maxFiles="1" />
              <div v-if="faviconPreview.length" class="mt-2">
                <img :src="faviconPreview[0]" alt="Favicon Preview" class="img-thumbnail"
                  style="width:40px;height:40px;" />
              </div>
            </div>

            <!-- ==================== GENERAL ==================== -->
            <div class="col-md-6">
              <label class="form-label">Invoice Prefix</label>
              <input v-model="form.invoice_prefix" type="text" class="form-control" />
            </div>

            <div class="col-md-3">
              <label class="form-label">Currency Symbol</label>
              <input v-model="form.currency_symbol" type="text" class="form-control" />
            </div>

            <div class="col-md-3">
              <label class="form-label">Currency</label>
              <input v-model="form.currency" type="text" class="form-control" />
            </div>

            <div class="col-md-6">
              <label class="form-label">Theme</label>
              <input v-model="form.theme" type="text" class="form-control" />
            </div>

            <!-- ==================== STORE INFO ==================== -->
            <div class="col-md-6">
              <label class="form-label">Store Name</label>
              <input v-model="form.store_name" type="text" class="form-control" />
            </div>

            <div class="col-md-6">
              <label class="form-label">Store Owner</label>
              <input v-model="form.store_owner" type="text" class="form-control" />
            </div>

            <div class="col-md-12">
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

            <div class="col-md-4">
              <label class="form-label">Country (ID)</label>
              <input v-model="form.country" type="text" class="form-control" placeholder="e.g. 18" />
            </div>

            <div class="col-md-4">
              <label class="form-label">State (ID)</label>
              <input v-model="form.state" type="text" class="form-control" placeholder="e.g. 322" />
            </div>

            <div class="col-md-4">
              <label class="form-label">Language</label>
              <input v-model="form.language" type="text" class="form-control" />
            </div>

            <div class="col-md-6">
              <label class="form-label">Length Class</label>
              <input v-model="form.length_class" type="text" class="form-control" />
            </div>

            <div class="col-md-6">
              <label class="form-label">Weight Class</label>
              <input v-model="form.weight_class" type="text" class="form-control" />
            </div>

            <!-- ==================== MAIL SETTINGS ==================== -->
            <div class="col-12">
              <hr class="my-4">
            </div>

            <div class="col-md-6">
              <label class="form-label">Mail Protocol</label>
              <select v-model="form.mail_protocol" class="custom-select rounded-0">
                <option value="smtp">SMTP</option>
                <option value="mail">Mail</option>
                <option value="sendmail">Sendmail</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Mail Address</label>
              <input v-model="form.mail_address" type="email" class="form-control" />
            </div>

            <div class="col-md-6">
              <label class="form-label">SMTP Host</label>
              <input v-model="form.smtp_host" type="text" class="form-control" />
            </div>

            <div class="col-md-6">
              <label class="form-label">SMTP Username</label>
              <input v-model="form.smtp_username" type="text" class="form-control" />
            </div>

            <div class="col-md-6">
              <label class="form-label">SMTP Password</label>
              <input v-model="form.smtp_password" type="password" class="form-control" />
            </div>

            <div class="col-md-3">
              <label class="form-label">SMTP Port</label>
              <input v-model="form.smtp_port" type="text" class="form-control" />
            </div>

            <div class="col-md-3">
              <label class="form-label">SMTP Timeout</label>
              <input v-model="form.smtp_timeout" type="text" class="form-control" />
            </div>

            <div class="col-md-6">
              <label class="form-label">SMTP Crypto</label>
              <select v-model="form.smtp_crypto" class="custom-select rounded-0">
                <option value="ssl">SSL</option>
                <option value="tls">TLS</option>
                <option value="">None</option>
              </select>
            </div>

            <!-- ==================== ALERTS ==================== -->
            <div class="col-12">
              <hr class="my-4">
            </div>

            <div class="col-md-6">
              <div class="form-check">
                <input v-model="form.new_account_alert_mail" class="form-check-input" type="checkbox" value="1"
                  id="newAccAlert">
                <label class="form-check-label" for="newAccAlert">New Account Alert Mail</label>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-check">
                <input v-model="form.new_order_alert_mail" class="form-check-input" type="checkbox" value="1"
                  id="newOrderAlert">
                <label class="form-check-label" for="newOrderAlert">New Order Alert Mail</label>
              </div>
            </div>

            <!-- ==================== SOCIAL LINKS ==================== -->
            <div class="col-12">
              <hr class="my-4">
            </div>

            <div class="col-md-6">
              <label class="form-label">Facebook URL</label>
              <input v-model="form.fb_url" type="url" class="form-control" placeholder="https://facebook.com/..." />
            </div>

            <div class="col-md-6">
              <label class="form-label">Twitter URL</label>
              <input v-model="form.twitter_url" type="url" class="form-control" placeholder="https://twitter.com/..." />
            </div>

            <div class="col-md-6">
              <label class="form-label">TikTok URL</label>
              <input v-model="form.tiktok_url" type="url" class="form-control" placeholder="https://tiktok.com/..." />
            </div>

            <div class="col-md-6">
              <label class="form-label">Instagram URL</label>
              <input v-model="form.instagram_url" type="url" class="form-control"
                placeholder="https://instagram.com/..." />
            </div>

            <!-- ==================== CATALOG ==================== -->
            <div class="col-12">
              <hr class="my-4">
            </div>

            <div class="col-md-6">
              <label class="form-label">Category Product Limit</label>
              <input v-model="form.category_product_limit" type="number" class="form-control" min="1" />
            </div>

            <!-- ==================== SEO ==================== -->
            <div class="col-12">
              <hr class="my-4">
            </div>

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

            <div class="col-12">
              <hr class="my-4">
            </div>

          </div>

          <!-- ==================== SUBMIT ==================== -->
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
  invoice_prefix: '',
  currency_symbol: '',
  currency: '',
  theme: '',
  // Store info
  store_name: '',
  store_owner: '',
  address: '',
  email: '',
  phone: '',
  country: '',
  state: '',
  language: '',
  length_class: '',
  weight_class: '',
  // Branding (images are handled separately)
  // Mail
  mail_protocol: '',
  mail_address: '',
  smtp_host: '',
  smtp_username: '',
  smtp_password: '',
  smtp_port: '',
  smtp_timeout: '',
  smtp_crypto: '',
  // Alerts (checkboxes → "1" / "0")
  new_account_alert_mail: '',
  new_order_alert_mail: '',
  // Social
  fb_url: '',
  twitter_url: '',
  tiktok_url: '',
  instagram_url: '',
  // Catalog
  category_product_limit: '',
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