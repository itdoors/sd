//var Common = require('./common.js');

var MongoDB  = require('./mongodb'),
    Ami    = require("./ami"),
    Socket    = require("./socket"),
    fs       = require('fs'),
    util     = require("util")
EventEmitter = require('events').EventEmitter;

exports = module.exports = Application;

util.inherits(Application, EventEmitter);

var app;

function Application (){

    app = this;

    //this.environment = 'local';
    this.environment = 'dev';
    //this.environment = 'production';

    this.loadConfiguration();

    this.mongodb = new MongoDB(this);
    this.ami = new Ami(this);
    this.socket = new Socket(this);
}

Application.prototype.loadConfiguration = function()
{
    try {
        this.config = JSON.parse(fs.readFileSync('./config/' + this.environment + '.json').toString());
        console.log('Config loaded successfully', this.config);
    } catch(e) {
        throw new Error('Configuration file not found for environment ' + this.environment);
    }
}

Application.prototype.init = function()
{
    console.log('start application');

    app.socket.init();

    /*app.sockets.init();
    app.rpc.init();*/
}