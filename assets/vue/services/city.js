import axios from 'axios';

export default {
  list: function (params) {
    return axios.get('/api/city', params).then(data => data.data);
  }
}