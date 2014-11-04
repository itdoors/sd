var MongoClient = require('mongodb').MongoClient;
var format = require('util').format;

exports = module.exports = MongoDB;

var app;

function MongoDB(application) {
    app = application;

    var loginInfo = app.config.mongodb.username ? app.config.mongodb.username + ':' + app.config.mongodb.password + '@' : "";

    MongoClient.connect('mongodb://' + loginInfo + app.config.mongodb.host + ':' + app.config.mongodb.port + '/' + app.config.mongodb.db, this.onConnected);
}

MongoDB.prototype.onConnected = function(err, db) {

    if (err) {
        console.log('MongoDB connection error ', err);
        return;
    }

    console.log('MongoDB connected successfully');

    app.mongodb.db = db;
    app.mongodb.collectionCalls = db.collection('calls');

}

MongoDB.prototype.onInsert = function(err, docs) {

    console.log('MONGODB - ', 'onInsert', docs);
}

MongoDB.prototype.onUpdate = function(err, docs) {

    console.log('MONGODB - ', 'onUpdate', docs);
}