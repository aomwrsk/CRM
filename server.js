// server.js
const express = require('express');
const cors = require('cors');
const dbOperations = require('./dbOperations');

const app = express();
app.use(cors());

app.get('/api/sales', async (req, res) => {
    try {
        const data = await dbOperations.getLoginDetails();
        res.json(data);
    } catch (error) {
        console.error(error);
        res.status(500).send('Server Error');
    }
});

const HOST = '127.0.0.1'; // specify the IP address to listen on
app.listen( HOST, () => console.log(`Server running on http://${HOST}`));
