import { combineReducers } from 'redux';
import CurrentAddressReducer from './CurrentAddressReducer.js';
import BookingReducer from './BookingReducer.js';
import RegisterReducer from './RegisterReducer.js';
import PaymentReducer from './PaymentReducer.js';

const allReducers = combineReducers({
  current_location:CurrentAddressReducer,
  booking:BookingReducer,
  register:RegisterReducer,
  payment:PaymentReducer,
});

export default allReducers;