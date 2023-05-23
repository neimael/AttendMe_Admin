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
    userName: "",
    userAvatar: "",
  }),

  actions: {
    setUser(payload) {
      if (payload.name) {
        this.userName = payload.name;
      }
      if (payload.avatar) {
        this.userAvatar = payload.avatar;
      }
    },

    fetchAdminData() {
      axios
        .get("/api/getAuthenticatedAdmin")
        .then((response) => {
          const adminData = response.data.admin;
          const fullName = adminData.first_name + " " + adminData.last_name;
          const avatar = adminData.avatar;
          this.setUser({ name: fullName, avatar: avatar });
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
