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

const PORT = process.env.PORT || 80;
const HOST = '127.0.0.1'; // specify the IP address to listen on
app.listen(PORT, HOST, () => console.log(`Server running on http://${HOST}:${PORT}`));
