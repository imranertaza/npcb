import axios from "axios";
import { defineStore } from "pinia";

export const useAuthStore = defineStore('auth', {
    state: () => ({
      token: null,
      role: null,
      permissions: [],
    }),
    actions: {
      async fetchRoleAndPermissions() {
        const res = await axios.get('/api/admin/me');
        this.role = res.data.role;
        this.permissions = res.data.permissions;
      },
      hasPermission(permission) {
        return this.permissions.includes(permission);
      }
    }
  });
  