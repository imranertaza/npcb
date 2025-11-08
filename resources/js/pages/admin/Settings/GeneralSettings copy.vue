<template>
    <DashboardHeader title="General Settings" />
    <section class="p-6">
      <div class="">
        <div
          v-for="setting in settings"
          :key="setting.id"
          class="flex items-center justify-between border-b pb-4"
        >
          <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700">
              {{ setting.title }}
            </label>
            <input
              v-model="setting.value"
              type="text"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
            />
          </div>
          <div class="ml-4">
            <button
              @click="updateSetting(setting)"
              class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
            >
              Save
            </button>
          </div>
        </div>
      </div>
    </section>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import DashboardHeader from '../../../components/DashboardHeader.vue';
  import axios from 'axios';
  import { toast } from 'vue3-toastify';
  
  const settings = ref([]);
  
  const fetchSettings = async () => {
    try {
      const response = await axios.get('/api/settings');
      settings.value = response.data.data;
    } catch (error) {
      console.error('Error fetching settings:', error);
    }
  };
  
  const updateSetting = async (setting) => {
    try {
      await axios.put(`/api/settings/${setting.label}`, {
        title: setting.title,
        value: setting.value,
      });
      toast.success(`${setting.title} updated successfully`);
    } catch (error) {
      toast.error(`Failed to update ${setting.title}`);
      console.error(error);
    }
  };
  
  onMounted(() => {
    fetchSettings();
  });
  </script>
  