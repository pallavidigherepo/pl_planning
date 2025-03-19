import { createStore } from "vuex";

import {actions} from './actions';
import {mutations} from "./mutations";
import {getters} from "./getters";
import {states} from "./states";
import {modules} from "./modules";

const store = createStore({
    modules: modules,
    state: states,
    getters: getters,
    actions: actions,
    mutations: mutations,
});

export default store;
