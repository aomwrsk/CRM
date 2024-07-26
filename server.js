// server.js
const express = require('express');
const cors = require('cors');
const dbOperations = require('./dbOperations');

const app = express();
app.use(cors());

app.get('./api/sales', async (req, res) => {
    try {
        const data = await dbOperations.getLoginDetails();
        res.json(data);
    } catch (error) {
        console.error(error);
        res.status(500).send('Server Error');
    }
});

const PORT = process.env.PORT || 5000;
app.listen(PORT, () => console.log(`Server running on http://localhost:${PORT}`));
