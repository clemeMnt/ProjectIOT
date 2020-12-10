exports.pool = function(mysql) {

    var pool = mysql.createPool({
        host     : 'localhost',
        user     : 'peatfr  ',
        password : 'dtQ3Iq86m0',
        database : 'ajax',
        charset  : 'UTF8_UNICODE_CI',
        multipleStatements: true
    });

    return pool;
}

exports.requete = function(mysql, sql, requete_sql, callback) {

    sql.pool(mysql).getConnection(function(err, connection) {

        connection.query(requete_sql, function(err, results) {

            sql.query_error(err);

            if(typeof(callback) !== 'undefined') {

                callback(results);
            }

            connection.destroy();
        });
    });
}

exports.query_error = function(erreur) {
    if (erreur) {
        console.log('query error : ' + erreur.stack);
    }
}