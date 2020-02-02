/**
 * Initial state
 */
export const state = {
    savedFilm: {}
}

/**
 * Mutations
 */
export const mutations = {
    setSavedFilm(state, payload) {
        state.savedFilm = payload;
    }
}

/**
 * Actions
 */
export const actions = {
    async saveFilm({commit}, payload) {

        let response = await axiosRequest.post('film.create', payload);

        commit('setSavedFilm', response.details);
        return response;
    },
    async fetchAllFilms({commit}, payload) {
        if (_.isNil(payload)) {
            let response = await axiosRequest.get('guestFilm.all');
            return response.details;
        }
        let response = await axiosRequest.get('film.all');
        return response.details;
    },
    async create_comment({commit}, payload) {
        let response = await axiosRequest.post('film.create_comment', payload);
        return response.details;
    },
    async getFilmBySlug({commit}, payload) {
        if (_.isNil(payload.auth)) {
            let response = await axiosRequest.post('guestFilm.getFilmBySlug', payload);
            return response.details;
        }
        let response = await axiosRequest.post('film.getFilmBySlug', payload);
        return response.details;
    },
    async getFilmComments({commit}, payload, auth) {
        if (_.isNil(payload.auth)) {
            let response = await axiosRequest.post('guestFilm.getFilmComments', payload);
            return response.details;
        }
        let response = await axiosRequest.post('film.getFilmComments', payload);
        return response.details;
    },
}

/**
 * Getters
 */
export const getters = {
    savedFilm: state => state.savedFilm
}



