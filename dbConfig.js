const config = {
    user: 'sa',
    password: 'System2560',
    server: '203.151.66.176', // Update server details as needed
    database: 'EntechDB',
    options: {
        encrypt: true, // Use true for Azure
        trustServerCertificate: true ,
        instancename:  'SQLEXPRESS'// Use false in production
    },
    port: 55449
};
module.exports = config;
