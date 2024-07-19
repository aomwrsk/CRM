const sql = require('msnodesqlv8');
const config = require('./dbConfig');

function getLoginDetails() {
    return new Promise((resolve, reject) => {
        const query = `
        SELECT A.staff_id, B.fname_e, B.nick_name 
        FROM xuser AS A
        LEFT JOIN hr_staff B ON A.staff_id = B.staff_id
        WHERE gid = '16387' 
          AND usrid NOT IN ('16387', '36', '42', '47', '50', '79', '80', '96', '97', '101', 
                            '104', '105', '107', '110', '112', '115', '122', 124, 125, 126, 
                            128, 129, 131, 132, 133, 135, 140, 150) 
          AND isactive = 'Y' 
          AND A.staff_id <> ''
    `;

        sql.query(config, query, (err, rows) => {
            if (err) {
                console.error('Database query error:', err); // More detailed error logging
                reject(err);
            } else {
                resolve(rows);
            }
        });
    });
}

module.exports = {
    getLoginDetails
};
