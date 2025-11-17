<template>
  <div v-if="pData?.last_page > 1" class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
    <!-- Showing info -->
    <div>
      <small>
        Showing {{ pData.from }} to {{ pData.to }} of {{ pData.total }} results
      </small>
    </div>

    <!-- Pagination links -->
    <nav aria-label="Page navigation" v-if="pData.links?.length">
      <ul class="pagination pagination-sm mb-0">
        <li
          v-for="(link, index) in pData.links"
          :key="index"
          class="page-item"
          :class="{ active: link.active, disabled: !link.url }"
        >
          <a
            class="page-link"
            href="#"
                v-html="index === 0 ? '&laquo;' : (index === pData.links.length - 1 ? '&raquo;' : link.label)"
            @click.prevent="handleClick(link)"
          ></a>
        </li>
      </ul>
    </nav>
  </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue'

const props = defineProps({
  pData: {
    type: Object,
    required: true
  }
})

const emit = defineEmits(['page-change'])

const handleClick = (link) => {
  if (!link.url || link.active) return

  // Extract page number from link.url (e.g. ?page=3)
  const url = new URL(link.url, window.location.origin)
  const page = url.searchParams.get('page')
  if (page) emit('page-change', Number(page))
}
</script>
