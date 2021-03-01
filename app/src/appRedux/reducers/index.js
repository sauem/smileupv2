import {combineReducers} from "redux";
import Settings from "./Settings";
import Auth from "./Auth";
import Common from "./Common";
import User from './User';
import {connectRouter} from 'connected-react-router'

export default (history) => combineReducers({
  router: connectRouter(history),
  settings: Settings,
  auth: Auth,
  common: Common,
  user: User
});
