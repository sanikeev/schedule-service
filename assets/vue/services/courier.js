import axios from 'axios';

export default {
  list: function (params) {
    return axios.get('/api/courier', params).then(data => data.data);
  }
}