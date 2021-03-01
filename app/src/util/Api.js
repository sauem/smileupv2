import axios from 'axios';

export const axiosAuth = (headers = {}) => {
  const {REACT_APP_API_URL} = process.env;
  const opts = {
    baseURL: REACT_APP_API_URL + '/api',
    headers: {
      ...{
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('token')}`,
      },
      ...headers,
    },
  };
  return axios.create(opts);
};

export default axios.create({
  baseURL: process.env.REACT_APP_API_URL + '/api',
  headers: {
    'Content-Type': 'application/json',
  },
});
