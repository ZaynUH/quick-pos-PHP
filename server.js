const express = require('express');
const mysql = require('mysql');
const bodyParser = require('body-parser');

const app = express();
const port = 6306;

// Create a MySQL connection
const connection = mysql.createConnection({
    host: 'mudfoot.doc.stu.mmu.ac.uk',
    user: 'ulhaqzay',
    password: 'anSterey5',
    database: 'POS_Database' // Corrected database name (if necessary)
});

// Connect to MySQL
connection.connect((err) => {
    if (err) {
        console.error('Error connecting to MySQL:', err);
    } else {
        console.log('Connected to MySQL');
    }
});

// Middleware to parse JSON requests
app.use(bodyParser.json());

// Route to handle adding an item
app.post('/addItem', (req, res) => {
    const { name, price, tab } = req.body;
    const sql = 'INSERT INTO items (name, price, tab) VALUES (?, ?, ?)';
    connection.query(sql, [name, price, tab], (err, result) => {
        if (err) {
            console.error('Error adding item:', err);
            res.status(500).send('Error adding item');
        } else {
            console.log('Item added successfully');
            res.status(200).send('Item added successfully');
        }
    });
});

// Start the server
app.listen(port, () => {
    console.log(`Server is running on port ${port}`);
});
