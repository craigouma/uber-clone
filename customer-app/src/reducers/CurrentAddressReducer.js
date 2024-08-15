import * as Actions from '../actions/ActionTypes'
const CurrentAddressReducer = (state = { current_address: undefined }, action) => {

    switch (action.type) {
        case Actions.UPDATE_CURRENT_ADDRESS:
            return Object.assign({}, state, {
                current_address: action.data
            });

        default:
            return state;
    }
}

export default CurrentAddressReducer;
