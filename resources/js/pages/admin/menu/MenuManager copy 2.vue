<template>
  <div class="menu-manager">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2 class="mb-0">Menu Manager</h2>
      <div class="input-group input-group-sm" style="max-width: 360px;">
        <input v-model="newMenuName" class="form-control form-control-sm" placeholder="New menu name"
          @keyup.enter="addMenu" />
        <div class="input-group-append">
          <button class="btn btn-primary" @click="addMenu">Add Menu</button>
        </div>
      </div>
    </div>

    <!-- Root Menu Items -->
    <draggable v-model="menuItems" item-key="id" :animation="150" group="menu" @end="onRootReorder">
      <template #item="{ element }">
        <MenuItem :item="element"  :toggleStatus="toggleStatus" :updateName="updateName" :addSubmenu="addSubmenu" :openEditPanel="openEditPanel"
          :deleteItem="deleteItem" :onChildrenReorder="onChildrenReorder" />
      </template>
    </draggable>

    <!-- Edit Modal -->
    <div v-if="editModal.open" class="modal fade show" tabindex="-1" role="dialog"
      style="display: block; background: rgba(0,0,0,0.5);" @click.self="closeEditPanel">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Menu Item</h5>
            <button type="button" class="close" @click="closeEditPanel">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <form @submit.prevent>
              <div class="form-group">
                <label for="edit-name">Name</label>
                <input id="edit-name" v-model="editForm.name" type="text" class="form-control form-control-sm"
                  placeholder="Enter menu name" />
              </div>

              <div class="form-group">
                <label for="edit-icon">Icon (FontAwesome)</label>
                <input id="edit-icon" v-model="editForm.icon" type="text" class="form-control form-control-sm"
                  placeholder="e.g. fas fa-home" />
                <small v-if="editForm.icon" class="form-text text-muted d-flex align-items-center mt-1">
                  <i :class="editForm.icon" class="mr-1"></i> Preview
                </small>
              </div>

              <div class="form-group">
                <label for="edit-link-type">Link Type</label>
                <select id="edit-link-type" v-model="editForm.link_type" class="form-control form-control-sm">
                  <option value="">-- Select Type --</option>
                  <option value="page">Page</option>
                  <option value="category">Category</option>
                  <option value="url">URL</option>
                </select>
              </div>

              <div v-if="editForm.link_type === 'page'" class="form-group">
                <label for="edit-page">Page</label>
                <Multiselect v-model="editForm.page_id" :options="pagesOptions" :reduce="option => option.value"
                  placeholder="Select parent category" searchable />
              </div>

              <div v-if="editForm.link_type === 'category'" class="form-group">
                <label for="edit-category">Category</label>
                <Multiselect v-model="editForm.category_id" :options="categoriesOptions"
                  :reduce="option => option.value" placeholder="Select parent category" searchable />
              </div>

              <div v-if="editForm.link_type === 'url'" class="form-group">
                <label for="edit-url">URL</label>
                <input id="edit-url" v-model="editForm.url" type="text" class="form-control form-control-sm"
                  placeholder="https://example.com" />
              </div>

              <div class="form-check mt-2">
                <input id="edit-enabled" v-model="editForm.enabled" type="checkbox" class="form-check-input" />
                <label for="edit-enabled" class="form-check-label">Enabled</label>
              </div>
            </form>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" @click="closeEditPanel">Cancel</button>
            <button type="button" class="btn btn-primary btn-sm" @click="applyEdit">
              <i class="fas fa-save mr-1"></i> Save
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script setup>
import axios from 'axios';
import { computed, onMounted, ref } from 'vue'
import draggable from 'vuedraggable'
let uid = 0;
const uniqueId = () => `client-${++uid}`;
const newMenuName = ref('')
const categories = ref()

const pages = ref([])

import { useRoute } from 'vue-router'
import MenuItem from './MenuItem.vue';
import Multiselect from '@vueform/multiselect';

const route = useRoute()

// This gives you the id from the URL
const menuId = route.params.id
const menuItems = ref([]);
const fetchMenuItems = async (menuId = 1) => {
  try {

    const { data } = await axios.get('/api/menu-items', {
      params: { menu_id: menuId }
    })
    console.log({ data });
    // apiResponse::success returns { success, data, message }
    // data.data will already be shaped with "elements"
    return data.data
  } catch (err) {
    console.error('Error fetching menu items', err)
    return []
  }
}

// Recursive search for item by id
const findItemById = (nodes, id) => {
  for (const node of nodes) {
    if (node.id === id) return node
    if (node.elements && node.elements.length) {
      const found = findItemById(node.elements, id)
      if (found) return found
    }
  }
  return null
}

const updateName = async (item) => {
  try {
    const payload = {
      name: item.name,
      icon: item.icon,
      link_type: item.link_type,
      page_id: item.page_id || null,
      category_id: item.category_id || null,
      url: item.url || null,
      enabled: item.enabled
    }

    const { data } = await axios.put(`/api/menu-items/${item.id}`, payload)

    // Sync local state with backend response
    Object.assign(item, data.data)

    console.log(`Menu item ${item.id} updated successfully`)
  } catch (err) {
    console.error('Error updating menu item', err)
    // Rollback if needed
    // Optionally show a toast/alert here
  }
}
const toggleStatus = async (menuId) => {
  const item = findItemById(menuItems.value, menuId) // ✅ works for nested children too
  if (!item) return

  // Flip locally first
  item.enabled = !item.enabled

  try {
    const { data } = await axios.put(`/api/menu-items/${menuId}`, {
      enabled: item.enabled
    })
    // Sync with API response (in case backend modifies other fields)
    Object.assign(item, data.data)
  } catch (err) {
    console.error('Error toggling status', err)
    // Rollback if API fails
    item.enabled = !item.enabled
  }
}
const onRootReorder = async () => {
  try {
    const reordered = menuItems.value.map((item, index) => ({
      id: item.id,
      order: index + 1,   // ✅ start at 1
      parent_id: null     // ✅ root items always have no parent
    }))

    await axios.post('/api/menu-items/reorder', { items: reordered })

    console.log('Root reorder synced successfully', reordered)
  } catch (err) {
    console.error('Error syncing root reorder', err)
  }
}


const onChildrenReorder = async (parent) => {
  try {
    const reordered = parent.elements.map((child, index) => ({
      id: child.id,
      order: index + 1,          // ✅ start at 1
      parent_id: parent.id ?? null // ✅ keep correct parent
    }))

    await axios.post('/api/menu-items/reorder', { items: reordered })

    console.log('Reorder synced successfully', reordered)
  } catch (err) {
    console.error('Error syncing reorder', err)
  }
}


const fetchCategories = async () => {
  try {
    const res = await axios.get('/api/categories?all=1');
    categories.value = res.data.data;
  } catch (error) {
    toast.error('Failed to load categories');
  }
};


const categoriesOptions = computed(() => {
  return [
    { label: '-- None --', value: '' },
    ...categories.value.map(cat => ({
      label: cat.category_name,
      value: cat.id
    }))
  ];
});

const fetchPages = async (page = 1, searchTerm = "") => {
  try {
    const res = await axios.get(`/api/pages?all=1`);
    pages.value = res.data.data;
  } catch (error) {
    console.error(error);
  }
};


const pagesOptions = computed(() => {
  return [
    { label: '-- None --', value: '' },
    ...pages.value.map(page => ({
      label: page.page_title,
      value: page.id
    }))
  ];
});
// Usage in your component
onMounted(async () => {
  menuItems.value = await fetchMenuItems(menuId)
  fetchCategories()
  fetchPages()
})


const editModal = ref({ open: false, target: null })
const editForm = ref({
  name: '',
  icon: '',
  link: '',
  link_type: '',
  page_id: '',
  category_id: '',
  enabled: true
})

const addMenu = async () => {
  const name = newMenuName.value.trim()
  if (!name) return

  try {
    // Build payload for backend
    const payload = {
      menu_id: menuId,       // current menu context
      parent_id: null,       // root menu
      name,
      icon: '',
      link_type: 'url',      // default type
      url: '/',              // default link
      enabled: true
    }

    // Call API
    const { data } = await axios.post('/api/menu-items', payload)

    // Push normalized response into menuItems
    menuItems.value.unshift({
      ...data.data,          // backend fields (id, etc.)
      _newChildName: '',     // Vue helper field
      elements: []           // ensure nested array exists
    })

    // Reset input
    newMenuName.value = ''
  } catch (err) {
    console.error('Error adding menu', err)
    // Optionally show a toast/alert here
  }
}


const addSubmenu = async (parent) => {
  const name = (parent._newChildName || '').trim()
  if (!name) return

  try {
    // Build payload for backend
    const payload = {
      menu_id: parent.menu_id || 1,   // or pass dynamic menu id
      parent_id: parent.id,           // attach to parent
      name,
      icon: '',
      link_type: 'url',               // default type
      url: '/',                       // default link
      enabled: true
    }

    // Call API
    const { data } = await axios.post('/api/menu-items', payload)

    // Push the normalized response into parent's children
    parent.elements.push({
      ...data.data,                   // backend fields
      _newChildName: '',              // keep Vue helper field
      elements: []                    // ensure nested array exists
    })

    // Reset input
    parent._newChildName = ''
  } catch (err) {
    console.error('Error adding submenu', err)
  }
}


const deleteItem = async (id) => {
  try {
    // Call backend to delete
    await axios.delete(`/api/menu-items/${id}`)

    // ✅ Remove locally only if backend succeeds
    const removeNode = (nodes) => {
      const idx = nodes.findIndex(n => n.id === id)
      if (idx !== -1) {
        nodes.splice(idx, 1)
        return true
      }
      for (const n of nodes) {
        if (n.elements && removeNode(n.elements)) return true
      }
      return false
    }

    removeNode(menuItems.value)
  } catch (err) {
    console.error('Error deleting menu item', err)
    // Optionally show a toast/alert here
  }
}


const openEditPanel = (item) => {
  editModal.value.open = true
  editModal.value.target = item
  editForm.value = {
    name: item.name || '',
    icon: item.icon || '',
    link_type: item.link_type || '',
    link: item.link || '',
    category_id: item.category_id || '',
    page_id: item.page_id || '',
    url: item.url || '',
    enabled: item.enabled !== false
  }
}

const closeEditPanel = () => {
  editModal.value.open = false
  editModal.value.target = null
}

const applyEdit = async () => {
  const target = editModal.value.target
  if (!target) return

  try {
    // Build payload from editForm
    const payload = {
      name: editForm.value.name,
      icon: editForm.value.icon,
      link_type: editForm.value.link_type,
      page_id: editForm.value.page_id || null,
      category_id: editForm.value.category_id || null,
      url: editForm.value.url || null,
      enabled: editForm.value.enabled
    }

    // Call backend to update
    const { data } = await axios.put(`/api/menu-items/${target.id}`, payload)

    // Sync local state with backend response
    Object.assign(target, data.data)

    // Close modal
    closeEditPanel()
  } catch (err) {
    console.error('Error updating menu item', err)
    // Optionally show a toast/alert here
  }
}

</script>
<style>
.menu-manager {
  width: 100%;
  max-width: 740px;
  margin: 0 auto;
  font-family: 'Segoe UI', sans-serif;
}

.menu-item {
  border: 1px solid #dfe3e8;
  padding: 12px;
  margin-bottom: 12px;
  background: #fafbfc;
  border-radius: 6px;
}

.menu-title {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  flex-wrap: wrap;
}

.submenu-list {
  margin-top: 10px;
  padding-left: 24px;
}

.submenu-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  border: 1px solid #e6e9ed;
  padding: 6px 8px;
  margin-bottom: 6px;
  background: #fff;
  border-radius: 4px;
}

.name-input {
  min-width: 180px;
  flex-grow: 1;
}

.badge {
  display: inline-block;
  padding: 0.25em 0.5em;
  font-size: 75%;
  border-radius: 0.25rem;
}

.badge-success {
  background-color: #28a745;
  color: #fff;
}

.badge-secondary {
  background-color: #6c757d;
  color: #fff;
}

.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background: rgba(0, 0, 0, 0.4);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 999;
}

.menu-manager {
  max-width: 800px;
  margin: 0 auto;
  padding: 1rem;
  font-family: 'Segoe UI', sans-serif;
}

.menu-title,
.submenu-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  flex-wrap: wrap;
  margin-bottom: 0.5rem;
}

.menu-item {
  background: #f9f9f9;
  border: 1px solid #dee2e6;
  border-radius: 6px;
  padding: 1rem;
  margin-bottom: 1rem;
}

.submenu-list {
  margin-top: 0.5rem;
  padding-left: 0.5rem;
}

.name-input {
  flex-grow: 1;
  min-width: 180px;
}

.badge {
  font-size: 0.75rem;
  padding: 0.3em 0.6em;
  border-radius: 0.25rem;
}

.badge-success {
  background-color: #28a745;
  color: #fff;
}

.badge-secondary {
  background-color: #6c757d;
  color: #fff;
}

.btn-group-sm>.btn {
  font-size: 0.75rem;
  padding: 0.25rem 0.5rem;
}

.modal.fade.show {
  display: block;
}

.modal-dialog {
  max-width: 500px;
}

.modal-content {
  border-radius: 6px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.modal-header,
.modal-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.modal-header h5 {
  margin: 0;
}

.close {
  background: none;
  border: none;
  font-size: 1.5rem;
  line-height: 1;
  cursor: pointer;
}

.form-group {
  margin-bottom: 1rem;
}

.form-check {
  margin-top: 0.5rem;
}

.icheck-success input[type="checkbox"] {
  accent-color: #28a745;
  width: 16px;
  height: 16px;
}

.icheck-success label {
  margin-left: 4px;
  font-size: 0.85rem;
}

.menu-title {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  flex-wrap: wrap;
  margin-bottom: 0.5rem;
}

.name-input {
  flex-grow: 1;
  min-width: 160px;
}

.submenu-input {
  min-width: 160px;
}

.badge {
  font-size: 0.75rem;
  padding: 0.3em 0.6em;
  border-radius: 0.25rem;
}

.badge-success {
  background-color: #28a745;
  color: #fff;
}

.badge-secondary {
  background-color: #6c757d;
  color: #fff;
}

.btn-group-sm>.btn {
  font-size: 0.75rem;
  padding: 0.25rem 0.5rem;
}
</style>