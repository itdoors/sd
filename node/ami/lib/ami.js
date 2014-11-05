exports = module.exports = Ami;

var app;

var exec = require('child_process').exec;

const VAR_SET_VARIABLE_ANSWEREDTIME = 'ANSWEREDTIME';
const VAR_SET_VARIABLE_DIALSTATUS = 'DIALSTATUS';
const VAR_SET_VARIABLE_CALLFILENAME = 'CALLFILENAME';

const ACCOUNT_CODE = 'ITdoor';

function Ami(application) {
    app = application;

    this.ami = new require('asterisk-manager')
        (app.config.ami.port, app.config.ami.host, app.config.ami.user, app.config.ami.password, true);

    // https://wiki.asterisk.org/wiki/display/AST/Asterisk+11+AMI+Events
    this.ami.on('dial', this.onDial);

    this.ami.on('varset', this.onVarSet);

    this.ami.on('hangup', this.onHangup);

    //this.ami.on('managerevent', this.onAll);
}

Ami.prototype.onDial = function(evt) {
   // console.log('dial', evt);

    if (evt.subevent == 'Begin') {
        if (evt.uniqueid && evt.destuniqueid && evt.calleridnum && evt.dialstring) {

            //console.log(app);

            var dialString = evt.dialstring

            var receiver = dialString.split('/');

            var updateSet = {
                callerId: evt.calleridnum,
                proxyId: receiver[0],
                receiverId: receiver[1],
                uniqueId: evt.uniqueid,
                destuniqueId: evt.destuniqueid
            };

            app.mongodb.collectionCalls.update(
                { uniqueId: evt.uniqueid },
                { $set: updateSet },
                { upsert: true },
                app.mongodb.onUpdate
            );
        }

        updateSet.event = "Dial";

        app.socket.io.emit('ami_' + evt.calleridnum, updateSet);
    }
}

Ami.prototype.onVarSet = function(evt) {
    /*console.log(' -------------------------------------------------- ');
    console.log(evt.variable, ' ---- ', evt.value);*/

    var uniqueid = evt.uniqueid;
    var updateSet = null;

    if (!evt.value) {
        return;
    }

    switch (evt.variable) {
        case VAR_SET_VARIABLE_DIALSTATUS:
            //console.log('VAR_SET_VARIABLE_DIALSTATUS ', evt.value);
            updateSet = { dialStatus: evt.value };
            break;
        case VAR_SET_VARIABLE_ANSWEREDTIME:
            //console.log('VAR_SET_VARIABLE_ANSWEREDTIME ', evt.value);
            updateSet = { answeredTime: evt.value };
            break;
        case VAR_SET_VARIABLE_CALLFILENAME:
            //console.log('VAR_SET_VARIABLE_CALLFILENAME ', evt.value);
            updateSet = { filename: evt.value };
            break;
    }

    //console.log(' -------------------------------------------------- ');

    if (updateSet) {
        app.mongodb.collectionCalls.update(
            { uniqueId: evt.uniqueid },
            { $set: updateSet },
            { upsert: true },
            app.mongodb.onUpdate
        );
    }
}

Ami.prototype.onHangup = function(evt) {

    console.log(evt);

    var accountcode = evt.accountcode;

    if (evt.uniqueid) {
        app.mongodb.collectionCalls.findAndRemove(
            { uniqueId: evt.uniqueid },
            function(err, doc) {

                if (!doc || evt.accountcode != ACCOUNT_CODE) {
                    return;
                }

                if (!doc.callerId || !doc.receiverId ) {
                    return;
                }

                var execString = app.config.global.exec.path;

                for (key in doc) {
                    if (doc.hasOwnProperty(key) && key != '_id') {
                        execString += ' --' + key + '=' + doc[key];
                    }
                }

                //console.log('Ami.prototype.onHangup was found', doc);

                //var docJson = JSON.stringify(doc);

                console.log('exec command = ', execString);

                exec(execString, function (error, stdout, stderr) {
                    console.log('exec std out', stdout);
                });
            }
        )
    }
}

/*{ _id: 5405b4a1dec7e2f23585f26a,
    uniqueId: '1409659581.14735',
    filename: '14-09/1409659581.14735',
    callerId: '8005',
    proxyId: '3590556',
    receiverId: '0636821588',
    destuniqueId: '1409659581.14736',
    modelName: 'contact',
    modelId: 2209,
    dialStatus: 'CANCEL' }*/


/*Ami.prototype.onAll = function(evt) {

    var newDate = new Date();
    //newDate.setTime(unixtime * 1000);
    dateString = newDate.toUTCString();

    console.log(dateString);
    console.log(' -------------------------------------------------- ');
    console.log(evt);
    console.log(' -------------------------------------------------- ');
}*/

Ami.prototype.onClientData = function(updateSet)
{
    //console.log(' Ami.prototype.onClientData ', updateSet);

    if (updateSet) {
        app.mongodb.collectionCalls.update(
            { uniqueId: updateSet.uniqueId },
            { $set: updateSet },
            { upsert: true },
            app.mongodb.onUpdate
        );
    }
}