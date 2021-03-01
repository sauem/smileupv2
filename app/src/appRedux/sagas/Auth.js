import axios from 'axios';

import {all, call, fork, put, takeEvery} from "redux-saga/effects";
import {
  SIGNIN_USER,
  SIGNOUT_USER,
} from "constants/ActionTypes";
import {
  showAuthLoader,
  showAuthMessage,
  userSignInSuccess,
  userSignOutSuccess,
} from '../../appRedux/actions/Auth';
import {message} from "antd";
import {doGetCities} from "./Common";
import {checkToken} from "../services/common";
import {axiosCatch, getVersion} from "../../util/Helper";
import {actionGetMenu, fetchStart} from '../actions';

const signInUserAuth = async (email, password) => {
  return await axios
    .post('/api/login', {
      email,
      password,
    })
    .catch(axiosCatch);
};

const signOutRequest = async () => {
  return undefined;
};

function* signInUserWithEmailPassword({payload}) {
  const {email, password} = payload;
  try {
    yield put(fetchStart);
    const signInUser = yield call(signInUserAuth, email, password);
    if (signInUser.message) {
      yield put(showAuthMessage(signInUser.message));
    } else {
      localStorage.setItem('user_id', JSON.stringify(signInUser.data.user));
      localStorage.setItem('token', signInUser.data.token);
      yield put(userSignInSuccess(signInUser.data));
      yield put(actionGetMenu(signInUser.data.role.item_name));
      yield doGetCities();
    }
  } catch (error) {
    yield put(showAuthMessage(error.message));
  }
}

function* signOut() {
  try {
    const signOutUser = yield call(signOutRequest);
    if (signOutUser === undefined) {
      localStorage.removeItem('user_id');
      localStorage.removeItem('token');
      yield put(userSignOutSuccess());
    } else {
      yield put(showAuthMessage(signOutUser.message));
    }
  } catch (error) {
    yield put(showAuthMessage(error));
  }
}

export function* signInUser() {
  yield takeEvery(SIGNIN_USER, signInUserWithEmailPassword);
}

export function* signOutUser() {
  yield takeEvery(SIGNOUT_USER, signOut);
}

export function* doCheckToken() {
  try {
    const token = localStorage.getItem('token');
    if (token) {
      const res = yield call(checkToken);
      yield put(
        userSignInSuccess({
          user: res.data.user,
          token: res.data.token ? res.data.token : token,
          role: res.data.role,
        }),
      );
      if (res.data.token) {
        localStorage.setItem('token', res.data.token);
      }

      const clientVersion = getVersion();
      if (res.data.client_version !== clientVersion) {
        message.warn(
          `Phiên bản ${clientVersion} hiện tại cần năng cấp lên phiên bản ${res.data.client_version}. Xin vui lòng nhấn "Ctrl + F5" để cập nhập`,
          30
        );
      }
      yield put(actionGetMenu(res.data.role.item_name));
      yield doGetCities();
    } else {
      yield put(userSignOutSuccess());
    }
  } catch (e) {
    yield put(userSignOutSuccess());
    yield put(showAuthMessage(e.message));
  }
}

export default function* rootSaga() {
  yield all([fork(signInUser), fork(signOutUser), yield doCheckToken()]);
}
