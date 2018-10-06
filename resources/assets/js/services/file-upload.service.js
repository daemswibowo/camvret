import * as axios from 'axios';
const BASE_URL = 'http://localhost:3000';

function upload(url, formData, method='post') {
    // const url = `${BASE_URL}/photos/upload`;
    return axios[method](url, formData)
        // get data
        .then(x => x.data)
        // add url field
        .then(x => x.map(img => Object.assign({},
            img, { url: `${BASE_URL}/images/${img.id}` })));
}

export { upload }