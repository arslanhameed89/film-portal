export default class axiosRequest {

    static post(path, payload) {
        return axios.post(api.path(path), payload)
            .then(resp => {
                return {
                    status: 200,
                    details: resp.data.details
                };
            })
            .catch(err => {
                return {
                    status: err.response.status,
                    details: err.response.data.errors
                };
            });
    }

    static put(path, payload) {
        return axios.put(api.path(path), payload)
            .then(resp => {
                return {
                    status: 200,
                    details: resp.data.details
                };
            })
            .catch(err => {
                return {
                    status: err.response.status,
                    details: err.response.data.errors
                };
            });
    }

    static get(path, payload = null) {
        let getUrl = api.path(path);

        if (!_.isNil(payload)) {
            getUrl = api.path(path) + payload;
        }
        return axios.get(getUrl)
            .then(function (resp) {
                return {
                    status: 200,
                    details: resp.data.details
                };
            })
            .catch(err => {
                return {
                    status: err.response.status,
                    details: err.response.data.errors
                };
            });
    }

};

