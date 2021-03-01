import React from 'react';
import { notification, Tag, Badge, message } from 'antd';
import moment from 'moment';

import IntlMessages from './IntlMessages';
import { getDistrictList } from '../appRedux/services/common';
import { axiosAuth } from './Api';

export const axiosCatch = (error) => {
  if (error.response) {
    // The request was made and the server responded with a status code
    // that falls out of the range of 2xx
    console.error('Response data', error.response.data);
    console.error('Response status', error.response.status);
    console.error('Response header', error.response.headers);
    const { data } = error.response;
    let msg = data.message;
    if (typeof msg === 'undefined' && Array.isArray(data)) {
      msg = data[0].message;
    }
    throw {
      data: error.response.data,
      message: msg,
    };
  } else if (error.request) {
    // The request was made but no response was received
    // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
    // http.ClientRequest in node.js
    console.error('Request', error.request);
    throw {
      message: error.message,
    };
  } else {
    // Something happened in setting up the request that triggered an Error
    console.error('Error', error.message);
    throw {
      message: error.message,
    };
  }
};

export const showNotify = (title = 'Wellcome', text = 'Notify', type = 'success') => {
  notification[type]({
    message: title,
    description: text,
    placement: 'topRight',
  });
};

export const findIndex = (args = [], searchValue, key = 'id') => {
  let index = false;
  args.forEach((item, i) => {
    if (item[key] === searchValue) {
      index = i;
    }
  });
  return index;
};
export const default_gift = () => {
  return 'data:image/png;base64,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';
};

export const date = (timestamp) => {
  const date = new Date(timestamp * 1000);
  return date.getDay() + '/' + (date.getMonth() - 1) + '/' + date.getFullYear();
};

export const limitText = (text, length = 15) => {
  if (length > 15) {
    return text.slice(0, length - 3) + '...';
  }
  return text;
};

export const dateFormat = (date) => {
  return moment.unix(date).format('DD/MM/YYYY');
};

export const renderEventStatus = (status, size = 'm', endDate = null) => {
  let color = '';
  let content = 'Unknown';

  if (endDate && endDate < new Date().getTime() / 1000) {
    status = -1;
  }

  switch (status) {
    case 0: {
      color = 'danger';
      content = <IntlMessages id={'in-active'} />;
      break;
    }
    case 1: {
      color = 'success';
      content = <IntlMessages id={'active'} />;
      break;
    }
    case -1: {
      color = 'default';
      content = 'Hết hạn';
      break;
    }
  }
  let fSize = '14px';
  switch (size) {
    case 's':
      fSize = '11px';
      break;
    case 'l':
      fSize = '20px';
      break;
  }
  return <Badge status={color} text={content} style={{ fontSize: fSize }} />;
};


export const renderEventType = (type, size = 'm') => {
  let content = 'Unknown';
  let fSize = '12px';
  switch (type) {
    case 0:
      content = <IntlMessages id={'er.type.manual'} />;
      break;
    case 1:
      content = <IntlMessages id={'er.type.auto'} />;
      break;
  }
  switch (size) {
    case 's':
      fSize = '10px';
      break;
    case 'l':
      fSize = '20px';
      break;
  }
  return <Tag style={{ fontSize: fSize }}>{content}</Tag>;
};

export const parseJwt = (token) => {
  try {
    return JSON.parse(atob(token.split('.')[1]));
  } catch (e) {
    return null;
  }
};
export const userLogin = () => {
  if (!localStorage.getItem('token')) {
    showNotify('Alert!', 'Phiên đăng nhập Hết hạn!');
    return null;
  }
  return parseJwt(localStorage.getItem('token'));
};

export const listCities = () => {
  if (localStorage.getItem('cities')) {
    return JSON.parse(localStorage.getItem('cities'));
  }
  return [];
};

export const listDistricts = (cityKey) => {
  const cityDistrictKey = `c_${cityKey}_d`;
  if (localStorage.getItem(cityDistrictKey)) {
    return JSON.parse(localStorage.getItem(cityDistrictKey));
  }
  return [];
};

export const saveLocalDistrict = (cityKey, districts) => {
  const cityDistrictKey = `c_${cityKey}_d`;
  localStorage.setItem(cityDistrictKey, JSON.stringify(districts));
};

export function pad(n) {
  return n < 10 ? '0' + n : n;
}

export function getDistrictsData(cityKey, callBack) {
  const districts = listDistricts(cityKey);
  if (districts.length === 0) {
    let saveDistricts = [];
    getDistrictList(cityKey)
      .then((res) => {
        let data = res.data;
        for (const [key, value] of Object.entries(data)) {
          saveDistricts.push(value);
        }
        saveLocalDistrict(cityKey, saveDistricts);
        callBack(saveDistricts);
      })
      .catch((e) => {
        message.error(e.message);
      });
  } else {
    callBack(districts);
  }
}

export const setDistrict = (city) => {
  let arr = [];
  let district = null;
  getDistrictList(city).then((res) => {
    district = res.data;
    for (const [key, value] of Object.entries(district)) {
      arr.push(value);
    }
  });
  return arr;
};

export const setCityKey = (city, district) => {};




export const getOpenTabs = () => {
  let openedTabs = JSON.parse(localStorage.getItem('openedTabs'));
  if (!openedTabs) {
    openedTabs = [];
  }
  return openedTabs;
};

export const addOpenTab = (giftTicketId) => {
  let openedTabs = getOpenTabs();
  if (openedTabs.findIndex((item) => item === giftTicketId) < 0) {
    openedTabs.push(giftTicketId);
  }
  localStorage.setItem('openedTabs', JSON.stringify(openedTabs));
};

export const removeOpenTab = (giftTicketId) => {
  let openedTabs = getOpenTabs();
  const idx = openedTabs.findIndex((item) => item === giftTicketId);
  if (idx >= 0) {
    if (idx === 0) {
      openedTabs = [];
    } else {
      openedTabs = openedTabs.splice(idx, 1);
    }
  }
  localStorage.setItem('openedTabs', JSON.stringify(openedTabs));
};


export const toUnicode = (str) => {
  str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, 'a');
  str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, 'e');
  str = str.replace(/ì|í|ị|ỉ|ĩ/g, 'i');
  str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, 'o');
  str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, 'u');
  str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, 'y');
  str = str.replace(/đ/g, 'd');
  str = str.replace(/\u0300|\u0301|\u0303|\u0309|\u0323/g, '');
  str = str.replace(/\u02C6|\u0306|\u031B/g, '');
  return str;
};

export const getVersion = () => {
  if (!process.env.REACT_APP_VERSION) {
    return 'v0.0';
  }
  return process.env.REACT_APP_VERSION;
};
