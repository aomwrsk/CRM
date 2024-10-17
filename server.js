// server.js
const express = require('express');
const bodyParser = require('body-parser');
var  config = require('./dbConfig');
const sql = require('mssql');
const moment = require('moment-timezone');
const app = express();

app.use(bodyParser.json());

// Database configuration


// Connect to SQL Server
sql.connect(config).then(() => {
    console.log('Connected to the database');
}).catch(err => {
    console.error('Database connection failed:', err);
});

app.post('/api', async (req, res) => {
    const tripData = req.body;
    console.log('Received trip data:', tripData);
    const record_datetime = moment.tz('Asia/Bangkok').format('YYYY-MM-DD HH:mm:ss');
    const query = `
        INSERT INTO transport_gps_distance (
            record_date, vehicle_id, start_timestamp, end_timestamp, 
            trip_duration, start_location, end_location, trip_distance, 
            start_geofence_name, end_geofence_name, start_coordinates_lat, 
            start_coordinates_long, end_coordinates_lat, end_coordinates_long
        ) 
        VALUES (
            @record_date, @registration, @start_timestamp, @end_timestamp, 
            @trip_duration, @start_location, @end_location, @trip_distance, 
            @start_geofence_name, @end_geofence_name, @start_coordinates_lat, 
            @start_coordinates_long, @end_coordinates_lat, @end_coordinates_long
        )
    `;

    try {
        let  pool = await  sql.connect(config);
        let  insertProduct = await  pool.request()  
        const request = new sql.Request();
        request.input('record_date', sql.DateTime, record_datetime);
        request.input('registration', sql.NVarChar, tripData.registration);
        request.input('start_timestamp', sql.DateTime, tripData.start_timestamp);
        request.input('end_timestamp', sql.DateTime, tripData.end_timestamp);
        request.input('trip_duration', sql.Int, tripData.trip_duration);
        request.input('start_location', sql.NVarChar, tripData.start_location);
        request.input('end_location', sql.NVarChar, tripData.end_location);
        request.input('trip_distance', sql.Float, tripData.trip_distance);
        request.input('start_geofence_name', sql.NVarChar, tripData.start_geofence_name);
        request.input('end_geofence_name', sql.NVarChar, tripData.end_geofence_name);
        request.input('start_coordinates_lat', sql.Float, tripData.start_coordinates_lat);
        request.input('start_coordinates_long', sql.Float, tripData.start_coordinates_long);
        request.input('end_coordinates_lat', sql.Float, tripData.end_coordinates_lat);
        request.input('end_coordinates_long', sql.Float, tripData.end_coordinates_long);

        const result = await request.query(query);
        res.status(200).send({ message: 'Data inserted successfully', result });
    } catch (err) {
        console.error('Error inserting data:', err);
        res.status(500).send('Error inserting data');
    }
});

const PORT = process.env.PORT || 3000; // Change to 3000 or any other port
app.listen(PORT, () => {
    console.log(`Server is running on http://localhost:${PORT}`);
});
