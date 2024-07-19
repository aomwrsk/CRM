const { reset } = require('nodemon');
const dboperations = require('./dbOperations');

dboperations.getLoginDetails()
    .then(result => {
        console.log('details:', result);
    })
    .catch(err => {
        console.error('Error fetching login details:', err);
    });
