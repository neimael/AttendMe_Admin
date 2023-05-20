import { defineStore } from "pinia";
import axios from "axios";
export const useMainStore = defineStore("main", {
  state: () => ({
    /* User */
    /* Field focus with ctrl+k (to register only once) */
    isFieldFocusRegistered: false,
    /* Sample data (commonly used) */
    clients: [],
    history: [],
  }),
  actions: {
    setUser(payload) {
      if (payload.name) {
        this.userName = payload.name; 
      }
    
    },

    fetchAdminData() {
      axios
        .get("/api/admin") // Replace with your API endpoint for fetching admin data
        .then((response) => {
          const adminData = response.data.admin;
          this.setUser({
            name: adminData.first_name + " " + adminData.last_name,

          });
        })
        .catch((error) => {
          alert(error.message);
        });
    },

    fetch(sampleDataKey) {
      axios
        .get(`data-sources/${sampleDataKey}.json`)
        .then((r) => {
          if (r.data && r.data.data) {
            this[sampleDataKey] = r.data.data;
          }
        })
        .catch((error) => {
          alert(error.message);
        });
    },

    
  },
});
