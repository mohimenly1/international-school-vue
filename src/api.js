// src/api.js
import axios from 'axios';
axios.defaults.headers.common['Access-Control-Allow-Origin'] = '*';
axios.defaults.withCredentials = true
const api = axios.create({
  baseURL: process.env.VUE_APP_API_BASE_URL || 'http://127.0.0.1:8000/api',

});

// Add a request interceptor to include the token in headers if available
api.interceptors.request.use(config => {
  const token = localStorage.getItem('access_token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
}, error => {
  return Promise.reject(error);
});

export const login = async (login, password) => {
  alert("ok")
  const response = await api.post('/login', { login, password });
  return response.data;
};
export const register = async (userData) => {
    const response = await api.post('/users', userData);
    return response.data;
  };

export const logout = async () => {
  const response = await api.post('/logout');
  return response.data;
};






export default api;
