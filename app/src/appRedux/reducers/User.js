import {CREATE_USER_SUCCESS, DELETE_USER_SUCCESS, GET_USER_SUCCESS} from "../../constants/ActionTypes";

const initialState = {
  users: null,
  crPage: 1,
  crLimit: 20,
  userPasswordUpdated: false,
}
export default (state = initialState, action) => {
  switch (action.type) {
    case CREATE_USER_SUCCESS:
      let {user} = action.payload;
      let users = state.users.push(user);
      return {
        ...state,
        ...{
          users
        }
      }
    case GET_USER_SUCCESS:
      return {
        ...state,
        users: action.payload.users,
      }
    case DELETE_USER_SUCCESS:
      //let userIndex = state.users.findIndex(item => item.id === payload.id);
      let ars = state.users.filter(item => item.id !== action.payload.id);
      return {
        ...state,
        ...{
          users: ars
        }
      }
    default:
      return state;
  }
}
