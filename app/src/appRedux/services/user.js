import {axiosCatch} from '../../util/Helper';
import {axiosAuth} from '../../util/Api';

export const getUsers = async (payload) => {
  return await axiosAuth().get('users', {
    params: payload
  }).catch(axiosCatch);
}

export const deleteUSer = async (id) => {
  return await axiosAuth().delete(`users/${id}`).catch(axiosCatch);
}
export const createUser = async (user) => {
  return await axiosAuth().post(`users`, user).catch(axiosCatch);
}

export const updateUser = async (user) => {
  return await axiosAuth().put(`users/${user.id}`, user).catch(axiosCatch);
}

export async function profileUser() {
  return await axiosAuth().get(`profile/index`).catch(axiosCatch);
}

export async function profileUpdate({full_name}) {
  return await axiosAuth()
    .post(`profile/update`, {
      full_name,
    }).catch(axiosCatch);
}

export async function profileUpdatePassword(user) {
  return await axiosAuth().post(`profile/update-password`, user).catch(axiosCatch);
}
