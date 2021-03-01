import {message} from "antd";
import {call, put, takeEvery, takeLatest, all} from 'redux-saga/effects';
import {createUser, deleteUSer, getUsers, profileUpdatePassword} from "../services/user";
import {
  COMMON_GET_MENU,
  CREATE_USER,
  CREATE_USER_SUCCESS, DELETE_USER, DELETE_USER_SUCCESS,
  GET_USER,
  GET_USER_SUCCESS, PROFILE_CHANGE_PASSWORD
} from "../../constants/ActionTypes";
import {getMenu} from "./Common";

function* doGetUsers({payload}) {
  try {
    const res = yield call(getUsers, payload);

    yield put({
      type: GET_USER_SUCCESS,
      payload: {users: res.data}
    })
  } catch (e) {
    message.error(e.message);
  }
}

function* doCreateUser({payload}) {
  try {
    const res = yield call(createUser, payload.user);
    yield put({
      type: GET_USER,
      payload: {
        page: 1
      }
    });
    message.success('Tạo tài khoản thành công!');
  } catch (e) {
    message.error(e.message);
  }
}

function* doDeleteUser({payload}) {
  try {
    const res = yield call(deleteUSer, payload.id);
    yield put({
      type: DELETE_USER_SUCCESS,
      payload: {
        id: payload.id
      }
    });
    message.success('Xoá tài khoản thành công!');
  } catch (e) {
    message.error(e.message);
  }
}

function* doUpdatePassword({payload}) {
  try {
    const res = yield call(profileUpdatePassword, payload.user);
    message.success('Cập nhật mật khẩu thành công!');
  } catch (e) {
    message.error(e.message);
  }
}

export default function* rootSaga() {
  yield all([
    takeEvery(GET_USER, doGetUsers),
    takeLatest(CREATE_USER, doCreateUser),
    takeLatest(DELETE_USER, doDeleteUser),
    takeLatest(PROFILE_CHANGE_PASSWORD, doUpdatePassword),
  ]);
}
