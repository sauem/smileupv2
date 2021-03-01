import {all} from "redux-saga/effects";
import authSagas from "./Auth";
import notesSagas from "./Notes";
import commonSagas from './Common';
import userSaga from './User';

export default function* rootSaga(getState) {
  yield all([
    authSagas(),
    notesSagas(),
    commonSagas(),
    userSaga(),
  ]);
}
