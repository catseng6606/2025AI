// Node.js + Express + EJS 範例
// app.js
const express = require('express');
const mysql = require('mysql2');
const escapeHtml = require('escape-html');
const app = express();
app.set('view engine', 'ejs');

const db = mysql.createConnection({ /* ... */ });

app.get('/search', (req, res) => {
    const q = req.query.q || '';
    const searchTerm = `%${q}%`;
    db.query('SELECT * FROM products WHERE name LIKE ?', [searchTerm], (err, results) => {
        res.render('search', { q, results });
    });
});

// views/search.ejs
/*
<form method="get" action="/search">
    <input type="text" name="q" value="<%= q %>">
    <button type="submit">搜尋</button>
</form>
<% if (q) { %>
    <p>您搜尋的關鍵字是: <%= q %></p>
    <ul>
    <% results.forEach(function(product) { %>
        <li><%= product.name %></li>
    <% }); %>
    </ul>
<% } %>
*/
