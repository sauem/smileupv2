import {axiosCatch} from '../../util/Helper';
import {axiosAuth} from '../../util/Api';
import axios from 'axios';
import IntlMessages from '../../util/IntlMessages';
import React from 'react';
import {ROLE_ADMIN} from "../../constants/ActionTypes";

export const getCityList = async () => {
  return await axios.get('/hanh-chinh/tinh_tp.json').catch(axiosCatch);
};

export const getDistrictList = async (city) => {
  return await axios.get(`/hanh-chinh/quan-huyen/${city}.json`).catch(axiosCatch);
};

export const checkToken = async () => {
  return await axiosAuth().get(`profile/check-token`).catch(axiosCatch);
};

export const callGetMenu = async (role) => {
  switch (role) {
    case ROLE_ADMIN:
      return [
        {
          key: 'dashboard',
          path: 'dashboard',
          component: import('../../routes/Dashboard/index'),
          icon: 'icon-wysiwyg',
          displayName: 'Dashboard',
          isMenu: true,
        },
        {
          key: 'user',
          path: 'user',
          component: import('../../routes/User/index'),
          icon: 'icon-wysiwyg',
          displayName: 'Users',
          isMenu: true,
        },
      ];
    default:
      return [
        {
          key: 'user',
          path: 'user',
          component: import('../../routes/User/index'),
          icon: 'icon-wysiwyg',
          displayName: 'Users',
          isMenu: true,
        },
      ];
  }
};
