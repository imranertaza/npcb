// src/composables/usePagination.js
import { ref } from 'vue';
import axios from 'axios';

export function usePagination(apiEndpoint, perPage = 10) {
  const data = ref([]);
  const pagination = ref({});
  const currentPage = ref(1);
  const isLoading = ref(false);

  const fetchPage = async (page = 1) => {
    isLoading.value = true;
    try {
      const res = await axios.get(`${apiEndpoint}?page=${page}&per_page=${perPage}`);
      data.value = res.data.data;
      pagination.value = res.data;
      currentPage.value = res.data.current_page;
    } catch (err) {
      console.error('Pagination fetch failed:', err);
    } finally {
      isLoading.value = false;
    }
  };

  return {
    data,
    pagination,
    currentPage,
    isLoading,
    fetchPage
  };
}
