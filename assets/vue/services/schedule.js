import axios from 'axios';

export default {
  list: function (params) {
    return axios.get('/api/schedule', params).then(data => data.data);
  },
  arrival: function (params) {
    return axios.post('/api/schedule/arrival', params).then(data => data.data)
  },
  check: function (params) {
    return axios.post('/api/schedule/check', params).then(data => data.data)
  },
  create: function (params) {
    return axios.post('/api/schedule', params).then(data => data.data)
  }
}