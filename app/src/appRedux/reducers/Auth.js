import {
  INIT_URL,
  SIGNIN_USER_SUCCESS,
  SIGNOUT_USER_SUCCESS,
  USER_DATA,
  USER_TOKEN_SET
} from "../../constants/ActionTypes";

const INIT_STATE = {
  loader: false,
  alertMessage: '',
  showMessage: false,
  initURL: '',
  authUser: null,
  token: localStorage.getItem('token'),
  role: ""
};
try {
  INIT_STATE.authUser = JSON.parse(localStorage.getItem('user_id'));
} catch (e) {
  INIT_STATE.authUser = null;
}

export default (state = INIT_STATE, action) => {
  switch (action.type) {
    case SIGNIN_USER_SUCCESS: {
      return {
        ...state,
        loader: false,
        authUser: action.payload.user,
        token: action.payload.token,
        role: action.payload.role.item_name
      }
    }

    case INIT_URL: {
      return {...state, initURL: action.payload};
    }

    case SIGNOUT_USER_SUCCESS: {
      return {
        ...state,
        token: null,
        authUser: null,
        initURL: ''
      }
    }

    case USER_DATA: {
      return {
        ...state,
        authUser: action.payload,
      };
    }

    case USER_TOKEN_SET: {
      return {
        ...state,
        token: action.payload,
      };
    }

    default:
      return state;
  }
}
