import axios from "axios";

const authApi = axios.create({
    baseURL: `${import.meta.env.APP_URL}`,
    headers: {
        "X-Requested-With": "XMLHttpRequest",
        "Accept": "application/json",
    },
    withCredentials: true,
});

authApi.defaults.withXSRFToken=true;

export default authApi;