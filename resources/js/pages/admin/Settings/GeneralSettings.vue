<template>
    <DashboardHeader title="General Settings" />
    <section class="p-4">
        <div class="bg-white rounded-lg shadow">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Label</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Value</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr v-for="setting in settings" :key="setting.id">
                        <td class="px-6 py-4 whitespace-nowrap">{{ setting.label }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ setting.title }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ setting.value }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import DashboardHeader from '../../../components/DashboardHeader.vue';
import axios from 'axios';

const settings = ref([]);

const fetchSettings = async () => {
    try {
        const response = await axios.get('/api/settings');
        settings.value = response.data.data;
    } catch (error) {
        console.error('Error fetching settings:', error);
    }
};

onMounted(() => {
    fetchSettings();
});
</script>