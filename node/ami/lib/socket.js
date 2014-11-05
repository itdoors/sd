exports = module.exports = Socket;

var io = require('socket.io');

var app;

function Socket(application)
{
    app = application;

    console.log(app.config.socket);

    this.io = io.listen(app.config.socket.listen);
}

Socket.prototype.init = function()
{
    console.log('socket init');

    app.socket.io.on('connection', app.socket.onConnection)
}

Socket.prototype.onConnection = function(socket)
{
    console.log('Socket.onConnection');

    socket.on('ami', app.ami.onClientData);
    /*socket.on('chat',               app.sockets.onChat);
    socket.on('markIsRead',         app.sockets.onMarkIsRead);
    socket.on('events_queue',       app.sockets.onEventsQueue);
    socket.on('checkIfBusy',        app.sockets.onCheckIfBusy);
    socket.on('setIsBusy',          app.sockets.onSetIsBusy);
    socket.on('deleteVideoChatKey', app.sockets.onDeleteVideoChatKey);
    socket.on('disconnect', __bind(app.sockets.onDisconnect, socket));
    socket.on('deleteIncomingCall', app.sockets.onDeleteIncomingCall);
    socket.on('uncaughtException', app.sockets.onUncaughtException);*/
}